// src/App.jsx
import React, { useState, useEffect, useMemo } from 'react';
import { Clock, Utensils, Flame, Martini, CheckCircle, AlertCircle } from 'lucide-react';

// Datos e Hooks
import { INITIAL_ITEMS, generateMassiveData } from './data/mockData';
import useCurrentTime from './hooks/useCurrentTime';

// Componentes
import Header from './components/Header/Header';
import KanbanColumn from './components/Kanban/KanbanColumn';
import MesasView from './components/Mesas/MesasView';
import ToastContainer from './components/Toast/ToastContainer';

export default function App() {
  const [items, setItems] = useState([]);
  const [activeView, setActiveView] = useState(() => localStorage.getItem('kds_view') || 'kanban');
  const [activeFilter, setActiveFilter] = useState(() => localStorage.getItem('kds_filter') || 'todo');
  const [toasts, setToasts] = useState([]);
  const [isFullscreen, setIsFullscreen] = useState(false);
  const [fullscreenError, setFullscreenError] = useState(false);
  const [sseStatus, setSseStatus] = useState('connecting'); // connecting, connected, error
  
  const currentTime = useCurrentTime(1000);

  // CONEXIÓN SSE REAL
  useEffect(() => {
    const connectSSE = () => {
      const eventSource = new EventSource('http://localhost/comandas-kds/backend/kds_api.php');
      
      setSseStatus('connecting');

      eventSource.onopen = () => {
        setSseStatus('connected');
        console.log("SSE: Conexión establecida");
      };

      eventSource.onmessage = (event) => {
        const newItems = JSON.parse(event.data);
        setItems(newItems);
      };

      eventSource.onerror = (err) => {
        setSseStatus('error');
        console.error("SSE: Error en conexión, reintentando...", err);
        eventSource.close();
        // El navegador reintentará automáticamente, pero podemos forzarlo tras un delay si queremos
        setTimeout(connectSSE, 3000);
      };

      return eventSource;
    };

    const es = connectSSE();
    return () => es.close();
  }, []);

  // Persistir solo filtros y vista (los items ya vienen del SSE)
  useEffect(() => {
    localStorage.setItem('kds_view', activeView);
  }, [activeView]);

  useEffect(() => {
    localStorage.setItem('kds_filter', activeFilter);
  }, [activeFilter]);

  // Lógica de Pantalla Completa... (resto del código igual)

  // Lógica de Pantalla Completa
  const handleToggleFullscreen = () => {
    if (!document.fullscreenElement) {
      const promise = document.documentElement.requestFullscreen();
      if (promise) {
        promise.catch(() => {
          setFullscreenError(true);
          setTimeout(() => setFullscreenError(false), 4000);
        });
      }
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      }
    }
  };

  useEffect(() => {
    const handleFullscreenChange = () => setIsFullscreen(!!document.fullscreenElement);
    document.addEventListener('fullscreenchange', handleFullscreenChange);
    return () => document.removeEventListener('fullscreenchange', handleFullscreenChange);
  }, []);

  // Handlers de Estado (Actualizan el Backend y el Frontend)
  const handleSimulate = (mode) => {
    // En modo SSE, la simulación real ocurre en el pos_simulator.php
    // Pero mantenemos la función para evitar errores de referencia en el Header
    console.log("Simulación solicitada:", mode);
    setToasts([]);
    // Si quisieras que estos botones hicieran algo real, tendrías que llamar a un endpoint PHP
    // que vacíe la base de datos e inyecte datos de prueba.
  };

  const handleAdvanceState = async (itemIds, newState) => {
    const idsToUpdate = Array.isArray(itemIds) ? itemIds : [itemIds];

    // 1. Avisar al Backend (IMPORTANTE)
    try {
      await fetch('http://localhost/comandas-kds/backend/update_comanda.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ids: idsToUpdate, estado: newState })
      });
    } catch (err) {
      console.error("Error actualizando backend:", err);
    }

    // 2. Actualizar Frontend (Optimista)
    setItems(prevItems => {
      const currentItem = prevItems.find(i => i.id === idsToUpdate[0]);

      if (newState === 'listo' && currentItem) {
        const toastId = Date.now() + Math.random();
        setToasts(prev => [...prev, {
          id: toastId, 
          itemIds: idsToUpdate,
          qty: idsToUpdate.length,
          mesa: currentItem.mesa, 
          producto: currentItem.producto,
          prevState: currentItem.estado, 
          prevTimestamp: currentItem.estado_timestamp
        }]);
        setTimeout(() => setToasts(current => current.filter(t => t.id !== toastId)), 5000);
      }

      return prevItems.map(item => 
        idsToUpdate.includes(item.id) 
          ? { ...item, estado: newState, estado_timestamp: Date.now() } 
          : item
      );
    });
  };

  const handleGoBackState = async (itemIds) => {
    const idsToUpdate = Array.isArray(itemIds) ? itemIds : [itemIds];
    
    setItems(prevItems => {
        const firstItem = prevItems.find(i => i.id === idsToUpdate[0]);
        if (!firstItem) return prevItems;

        let prevState = firstItem.estado === 'emplatado' ? firstItem.station : (firstItem.estado === firstItem.station ? 'pendiente' : firstItem.estado);
        
        if (prevState !== firstItem.estado) {
            // Actualizar Backend
            fetch('http://localhost/comandas-kds/backend/update_comanda.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: idsToUpdate, estado: prevState })
            }).catch(err => console.error(err));

            // Actualizar Frontend
            return prevItems.map(item => 
                idsToUpdate.includes(item.id) 
                    ? { ...item, estado: prevState, estado_timestamp: Date.now() } 
                    : item
            );
        }
        return prevItems;
    });
  };

  const handleUndoToast = async (itemIds, revertState, revertTimestamp, toastId) => {
    // 1. Avisar al Backend
    try {
      await fetch('http://localhost/comandas-kds/backend/update_comanda.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ids: itemIds, estado: revertState })
      });
    } catch (err) {
      console.error(err);
    }

    // 2. Actualizar Frontend
    setItems(prevItems => prevItems.map(item => 
      itemIds.includes(item.id) 
        ? { ...item, estado: revertState, estado_timestamp: revertTimestamp } 
        : item
    ));
    setToasts(prev => prev.filter(t => t.id !== toastId));
  };

  // Agrupación de datos para el Kanban (Memoizada para rendimiento)
  const groupedColumns = useMemo(() => {
    let filtered = items.filter(i => i.estado !== 'listo');
    if (activeFilter !== 'todo') filtered = filtered.filter(i => i.station === activeFilter);

    const map = new Map();
    const grouped = [];

    filtered.forEach(item => {
      const notesArray = Array.isArray(item.notes) ? item.notes : [];
      const key = `${item.orderId}-${item.producto}-${item.estado}-${notesArray.join(',')}`;

      if (map.has(key)) {
        const existing = map.get(key);
        existing.ids.push(item.id);
        existing.qty += 1;
        if (item.estado_timestamp < existing.estado_timestamp) existing.estado_timestamp = item.estado_timestamp;
      } else {
        const newItemGroup = { ...item, ids: [item.id], qty: 1 };
        map.set(key, newItemGroup);
        grouped.push(newItemGroup);
      }
    });

    return {
      pendiente: grouped.filter(i => i.estado === 'pendiente'),
      cocina: grouped.filter(i => i.estado === 'cocina'),
      horno: grouped.filter(i => i.estado === 'horno'),
      barra: grouped.filter(i => i.estado === 'barra'),
      emplatado: grouped.filter(i => i.estado === 'emplatado'),
    };
  }, [items, activeFilter]);

  return (
    <div className="h-screen w-full bg-[#0B1120] flex flex-col font-sans select-none overflow-hidden text-slate-200 relative">
      <style>{`
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #334155; border-radius: 10px; }   
        .toast-enter { animation: slideUpFade 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes slideUpFade { 0% { opacity: 0; transform: translateY(20px) scale(0.9); } 100% { opacity: 1; transform: translateY(0) scale(1); } }
      `}</style>

      {fullscreenError && (
        <div className="absolute top-24 left-1/2 -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded-full font-bold shadow-[0_0_25px_rgba(220,38,38,0.6)] z-50 flex items-center gap-2 toast-enter">
          <AlertCircle size={20} className="text-white" />
          La pantalla completa está bloqueada en esta vista previa.
        </div>
      )}

      <Header
        currentTime={currentTime} activeView={activeView} setActiveView={setActiveView}
        activeFilter={activeFilter} setActiveFilter={setActiveFilter}
        onSimulate={handleSimulate} isFullscreen={isFullscreen} toggleFullscreen={handleToggleFullscreen}
        sseStatus={sseStatus}
      />

      <main className="flex-1 p-6 overflow-hidden flex flex-col relative">
        {activeView === 'kanban' && (
          <div className="flex gap-6 h-full items-stretch overflow-x-auto pb-4 custom-scrollbar fade-in">
            <KanbanColumn title="Pendiente" icon={Clock} groupedItems={groupedColumns.pendiente} currentTime={currentTime} onAdvance={handleAdvanceState} onGoBack={handleGoBackState} />
            {(activeFilter === 'todo' || activeFilter === 'cocina') && <KanbanColumn title="Cocina" icon={Utensils} groupedItems={groupedColumns.cocina} currentTime={currentTime} onAdvance={handleAdvanceState} onGoBack={handleGoBackState} />}
            {(activeFilter === 'todo' || activeFilter === 'horno') && <KanbanColumn title="Horno" icon={Flame} groupedItems={groupedColumns.horno} currentTime={currentTime} onAdvance={handleAdvanceState} onGoBack={handleGoBackState} />}
            {(activeFilter === 'todo' || activeFilter === 'barra') && <KanbanColumn title="Barra" icon={Martini} groupedItems={groupedColumns.barra} currentTime={currentTime} onAdvance={handleAdvanceState} onGoBack={handleGoBackState} />}
            <KanbanColumn title="Emplatado" icon={CheckCircle} groupedItems={groupedColumns.emplatado} currentTime={currentTime} onAdvance={handleAdvanceState} onGoBack={handleGoBackState} />
          </div>
        )}

        {activeView === 'mesas' && <MesasView items={items} currentTime={currentTime} />}

        <ToastContainer toasts={toasts} onUndo={handleUndoToast} onClose={(id) => setToasts(p => p.filter(t => t.id !== id))} />
      </main>
    </div>
  );
}
