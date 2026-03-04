import React, { useState, useEffect, useMemo, useRef } from 'react';
import { Clock, CheckCircle, ChefHat, Flame, Martini, Utensils, AlertCircle, RotateCcw, X, LayoutGrid, KanbanSquare, Maximize, Minimize, Activity, Zap } from 'lucide-react';

// ============================================================================
// --- ARCHIVO: /utils/mockData.js ---
// ============================================================================
export const INITIAL_ITEMS = [
  { id: 'i1', orderId: 'O-101', mesa: 'Mesa 12', producto: 'Burger Smash', station: 'cocina', estado: 'pendiente', estado_timestamp: Date.now() - 1000 * 60 * 2, estimated_time: 10, notes: ['Sin cebolla'] },
  { id: 'i1-b', orderId: 'O-101', mesa: 'Mesa 12', producto: 'Burger Smash', station: 'cocina', estado: 'pendiente', estado_timestamp: Date.now() - 1000 * 60 * 2, estimated_time: 10, notes: ['Sin cebolla'] },
  { id: 'i2', orderId: 'O-101', mesa: 'Mesa 12', producto: 'Patatas Bravas', station: 'cocina', estado: 'pendiente', estado_timestamp: Date.now() - 1000 * 60 * 1, estimated_time: 8, notes: [] },
  { id: 'i3', orderId: 'O-101', mesa: 'Mesa 12', producto: 'Ensalada César', station: 'barra', estado: 'pendiente', estado_timestamp: Date.now() - 1000 * 60 * 3, estimated_time: 5, notes: ['Salsa aparte'] },
  { id: 'i4', orderId: 'O-102', mesa: 'Mesa 04', producto: 'Pizza Margarita', station: 'horno', estado: 'horno', estado_timestamp: Date.now() - 1000 * 60 * 9, estimated_time: 8, notes: [] },
  { id: 'i5', orderId: 'O-103', mesa: 'Mesa 08', producto: 'Entrecot', station: 'cocina', estado: 'cocina', estado_timestamp: Date.now() - 1000 * 60 * 12, estimated_time: 15, notes: ['Punto menos'] },
  { id: 'i6', orderId: 'O-104', mesa: 'Mesa 02', producto: 'Gazpacho', station: 'barra', estado: 'emplatado', estado_timestamp: Date.now() - 1000 * 60 * 1, estimated_time: 2, notes: [] },
  { id: 'i7', orderId: 'O-104', mesa: 'Mesa 02', producto: 'Cerveza Artesanal', station: 'barra', estado: 'listo', estado_timestamp: Date.now() - 1000 * 60 * 15, estimated_time: 2, notes: [] }
];

// Generador de Caos (Stress Test Data)
export const generateMassiveData = () => {
  const items = [];
  const products = [
    { n: 'Burger Smash', s: 'cocina', e: 10 },
    { n: 'Patatas Bravas', s: 'cocina', e: 8 },
    { n: 'Ensalada César', s: 'barra', e: 5 },
    { n: 'Pizza Margarita', s: 'horno', e: 12 },
    { n: 'Pizza 4 Quesos', s: 'horno', e: 14 },
    { n: 'Entrecot', s: 'cocina', e: 15 },
    { n: 'Costillas BBQ', s: 'horno', e: 18 },
    { n: 'Gazpacho', s: 'barra', e: 3 },
    { n: 'Cerveza de Barril', s: 'barra', e: 2 },
    { n: 'Vino Tinto', s: 'barra', e: 2 },
    { n: 'Tarta Queso', s: 'barra', e: 4 }
  ];

  for (let t = 1; t <= 20; t++) { // 20 mesas
    const numItems = Math.floor(Math.random() * 15) + 5; // 5 a 20 ítems por mesa
    const orderId = `O-MASS-${t}`;
    const mesa = `Mesa ${t.toString().padStart(2, '0')}`;
    
    for (let i = 0; i < numItems; i++) {
        const prod = products[Math.floor(Math.random() * products.length)];
        
        let state = 'pendiente';
        const rand = Math.random();
        if (rand > 0.8) state = 'emplatado';
        else if (rand > 0.5) state = prod.s; // cocinando en su estación

        // FIX: Aseguramos que 'notes' sea siempre un Array, no un string suelto.
        const noteOptions = ['Urgente', 'Sin sal'];
        const randomNote = noteOptions[Math.floor(Math.random() * noteOptions.length)];
        const itemNotes = Math.random() > 0.85 ? [randomNote] : [];

        items.push({
            id: `m_i_${t}_${i}`,
            orderId,
            mesa,
            producto: prod.n,
            station: prod.s,
            estado: state,
            estado_timestamp: Date.now() - Math.floor(Math.random() * 25 * 60000), // Hace 0 a 25 mins
            estimated_time: prod.e,
            notes: itemNotes
        });
    }
  }
  return items;
};

export const WORKFLOW = {
  pendiente: (station) => station,
  barra: () => 'emplatado',
  cocina: () => 'emplatado',
  horno: () => 'emplatado',
  emplatado: () => 'listo'
};

export const STATION_COLORS = {
  barra: '#06b6d4',   
  cocina: '#ef4444',  
  horno: '#f97316'    
};

// ============================================================================
// --- ARCHIVO: /hooks/useCurrentTime.js ---
// ============================================================================
const useCurrentTime = (updateIntervalMs = 1000) => {
  const [currentTime, setCurrentTime] = useState(Date.now());
  useEffect(() => {
    const timer = setInterval(() => setCurrentTime(Date.now()), updateIntervalMs);
    return () => clearInterval(timer);
  }, [updateIntervalMs]);
  return currentTime;
};

// ============================================================================
// --- ARCHIVO: /components/ToastContainer.jsx ---
// ============================================================================
const ToastContainer = ({ toasts, onUndo, onClose }) => {
  return (
    <div className="fixed bottom-6 right-6 flex flex-col-reverse gap-3 z-50 pointer-events-none">
      {toasts.map(toast => (
        <div key={toast.id} className="pointer-events-auto bg-slate-800 border-2 border-slate-600 text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-5 toast-enter">
          <div className="flex flex-col">
            <span className="text-sm font-black text-green-400 flex items-center gap-1">
              <CheckCircle size={14}/> Plato Finalizado
            </span>
            <span className="text-sm font-medium text-slate-200 mt-0.5">
              <span className="text-slate-400">{toast.mesa}</span> • {toast.producto}
            </span>
          </div>
          <div className="w-px h-10 bg-slate-600"></div>
          <button 
            onClick={() => onUndo(toast.itemId, toast.prevState, toast.prevTimestamp, toast.id)} 
            className="flex items-center gap-2 bg-slate-700 hover:bg-slate-600 active:scale-95 px-4 py-2 rounded-xl text-sm font-bold text-white transition-all shadow"
          >
            <RotateCcw size={16} className="text-amber-400" /> Deshacer
          </button>
          <button onClick={() => onClose(toast.id)} className="text-slate-500 hover:text-white transition-colors p-1">
            <X size={20} />
          </button>
        </div>
      ))}
    </div>
  );
};

// ============================================================================
// --- ARCHIVO: /components/KanbanCard.jsx ---
// ============================================================================
const KanbanCard = ({ itemGroup, currentTime, onAdvance, onGoBack }) => {
  const { ids, qty, mesa, producto, estimated_time, estado_timestamp, notes, station, estado } = itemGroup;

  const [isHolding, setIsHolding] = useState(false);
  const holdTimeout = useRef(null);
  const hasLongPressed = useRef(false);
  const HOLD_DURATION = 600; 

  const elapsedMs = currentTime - estado_timestamp;
  const elapsedMinutes = Math.floor(elapsedMs / 60000);
  const elapsedSeconds = Math.floor((elapsedMs % 60000) / 1000);
  const timeString = `${elapsedMinutes.toString().padStart(2, '0')}:${elapsedSeconds.toString().padStart(2, '0')}`;

  let statusColor = "bg-slate-700/80 border-slate-600 text-slate-200"; 
  let timeTextColor = "text-slate-300";
  
  if (elapsedMinutes >= estimated_time) {
    statusColor = "bg-red-900/40 border-red-500/50 shadow-[0_0_15px_rgba(220,38,38,0.2)]";
    timeTextColor = "text-red-400 font-bold animate-pulse";
  } else if (elapsedMinutes >= estimated_time * 0.75) {
    statusColor = "bg-amber-900/30 border-amber-500/50";
    timeTextColor = "text-amber-400 font-bold";
  }

  const borderColor = STATION_COLORS[station] || '#475569';
  const nextState = WORKFLOW[estado] ? WORKFLOW[estado](station) : null;
  const prevState = estado === 'emplatado' ? station : (estado === station ? 'pendiente' : null);

  const handlePointerDown = (e) => {
    if (e.pointerType === 'mouse' && e.button !== 0) return; 
    hasLongPressed.current = false;
    setIsHolding(true);
    
    holdTimeout.current = setTimeout(() => {
      hasLongPressed.current = true;
      if (prevState) {
        onGoBack(ids[0]);
        if (navigator.vibrate) navigator.vibrate([50, 50, 50]); 
      }
      setIsHolding(false);
    }, HOLD_DURATION);
  };

  const handlePointerUpOrLeave = (e) => {
    setIsHolding(false);
    if (holdTimeout.current) clearTimeout(holdTimeout.current);
    
    if (e.type === 'pointerup' && !hasLongPressed.current && nextState) {
      onAdvance(ids[0], nextState);
    }
  };

  return (
    <div 
      className={`relative p-3 mb-3 rounded-xl border flex flex-col gap-2 transition-transform duration-200 cursor-pointer overflow-hidden touch-none select-none hover:-translate-y-0.5 shadow-md ${statusColor}`}
      style={{ borderLeftWidth: '6px', borderLeftColor: borderColor, transform: isHolding ? 'scale(0.96)' : 'scale(1)' }}
      onPointerDown={handlePointerDown}
      onPointerUp={handlePointerUpOrLeave}
      onPointerLeave={handlePointerUpOrLeave}
      onContextMenu={(e) => e.preventDefault()} 
    >
      <div 
        className="absolute bottom-0 left-0 h-1 bg-red-500 ease-linear"
        style={{ 
          width: isHolding ? '100%' : '0%', 
          transition: isHolding ? `width ${HOLD_DURATION}ms linear` : 'width 100ms ease-out',
          opacity: isHolding ? 1 : 0
        }}
      />

      <div className="flex justify-between items-start">
        <div className="flex items-center gap-2">
          <span className="font-bold text-sm bg-slate-900/80 px-2 py-1 rounded text-white shadow-sm">
            {mesa}
          </span>
          {qty > 1 && (
            <span className="font-black text-sm bg-orange-500 px-2 py-1 rounded text-white shadow-md animate-pulse">
              x{qty}
            </span>
          )}
        </div>
        <div className={`text-lg font-mono flex items-center gap-1 bg-slate-900/40 px-2 py-0.5 rounded ${timeTextColor}`}>
          <Clock size={14} />
          {timeString}
        </div>
      </div>
      
      <div>
        <h3 className="text-xl font-black text-white leading-tight tracking-tight">{producto}</h3>
        {Array.isArray(notes) && notes.length > 0 && (
          <div className="mt-1.5 flex flex-col gap-1">
            {notes.map((note, idx) => (
              <span key={idx} className="text-xs font-bold text-amber-300 bg-amber-950/60 px-2 py-1 rounded w-fit flex items-center gap-1.5 border border-amber-500/30">
                <AlertCircle size={12} /> {note}
              </span>
            ))}
          </div>
        )}
      </div>

      <div className="mt-2 pt-2 border-t border-slate-600/50 flex justify-between items-center text-xs text-slate-400 font-bold uppercase tracking-wider">
        <span className="opacity-70">Est. {estimated_time}m</span>
        <div className="flex items-center gap-3">
          {prevState && <span className="text-amber-500/70">Mantener ➔ Atrás</span>}
          <span className="text-orange-400">Tap ➔ {nextState || 'Terminar'}</span>
        </div>
      </div>
    </div>
  );
};

// ============================================================================
// --- ARCHIVO: /components/KanbanColumn.jsx ---
// ============================================================================
const KanbanColumn = ({ title, icon: Icon, groupedItems, currentTime, onAdvance, onGoBack }) => {
  const totalItems = groupedItems.reduce((acc, item) => acc + item.qty, 0);

  return (
    <div className="flex flex-col flex-1 min-w-[300px] bg-slate-800/40 rounded-2xl border border-slate-700/60 overflow-hidden shadow-lg">
      <div className="bg-slate-800/90 px-4 py-3 border-b border-slate-700 flex justify-between items-center backdrop-blur-sm z-10">
        <h2 className="font-bold text-lg text-white flex items-center gap-2">
          <Icon size={20} className="text-orange-500" />
          {title}
        </h2>
        <span className="bg-slate-700/50 border border-slate-600 text-slate-200 text-xs font-black px-2.5 py-1 rounded-full">
          {totalItems}
        </span>
      </div>
      <div className="p-3 overflow-y-auto flex-1 custom-scrollbar">
        {groupedItems.length === 0 ? (
          <div className="h-full flex flex-col items-center justify-center text-slate-500 opacity-60">
            <CheckCircle size={40} className="mb-3 opacity-50" />
            <p className="font-semibold text-sm">Estación Libre</p>
          </div>
        ) : (
          groupedItems.map(group => (
            <KanbanCard 
              key={group.ids[0]} 
              itemGroup={group} 
              currentTime={currentTime} 
              onAdvance={onAdvance}
              onGoBack={onGoBack}
            />
          ))
        )}
      </div>
    </div>
  );
};

// ============================================================================
// --- ARCHIVO: /components/MesasView.jsx ---
// ============================================================================
const MesasView = ({ items, currentTime }) => {
  const tablesData = useMemo(() => {
    const tablesMap = new Map();

    items.forEach(item => {
      if (!tablesMap.has(item.mesa)) {
        tablesMap.set(item.mesa, { nombre: item.mesa, items: [], oldestPendingTime: null, totalItems: 0, completedItems: 0 });
      }
      const table = tablesMap.get(item.mesa);
      table.items.push(item);
      table.totalItems++;
      if (item.estado === 'listo') table.completedItems++;

      if (item.estado !== 'listo') {
        if (!table.oldestPendingTime || item.estado_timestamp < table.oldestPendingTime) {
          table.oldestPendingTime = item.estado_timestamp;
        }
      }
    });

    const extractNumber = (str) => {
      const match = str.match(/\d+/);
      return match ? parseInt(match[0], 10) : 0;
    };

    const sortedTables = Array.from(tablesMap.values()).sort((a, b) => extractNumber(a.nombre) - extractNumber(b.nombre));

    return sortedTables.map(table => {
      const groupedItemsMap = new Map();
      table.items.forEach(i => {
        const notesArray = Array.isArray(i.notes) ? i.notes : [];
        const key = `${i.producto}-${i.estado}-${notesArray.join(',')}`;
        if (groupedItemsMap.has(key)) groupedItemsMap.get(key).qty++;
        else groupedItemsMap.set(key, { ...i, qty: 1 });
      });
      
      const statusOrder = { 'pendiente': 1, 'cocina': 2, 'horno': 2, 'barra': 2, 'emplatado': 3, 'listo': 4 };
      table.groupedItems = Array.from(groupedItemsMap.values()).sort((a, b) => statusOrder[a.estado] - statusOrder[b.estado]);
      
      return table;
    });
  }, [items]);

  const getStatusDisplay = (estado) => {
    switch(estado) {
      case 'pendiente': return { label: 'Pendiente', color: 'bg-slate-600 text-slate-200', dot: 'bg-slate-400' };
      case 'cocina':
      case 'horno':
      case 'barra': return { label: 'En Preparación', color: 'bg-orange-900/40 text-orange-400 border border-orange-500/30', dot: 'bg-orange-500 animate-pulse' };
      case 'emplatado': return { label: 'Pase', color: 'bg-cyan-900/40 text-cyan-400 border border-cyan-500/30', dot: 'bg-cyan-400' };
      case 'listo': return { label: 'Entregado', color: 'bg-green-900/20 text-green-500/50', dot: 'bg-green-500/50' };
      default: return { label: estado, color: 'bg-slate-700 text-white', dot: 'bg-white' };
    }
  };

  return (
    <div className="h-full overflow-y-auto custom-scrollbar p-2">
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-20">
        {tablesData.map(table => {
          let tableTimeString = "00:00";
          let tableTimeColor = "text-slate-400";
          
          if (table.oldestPendingTime) {
            const elapsedMs = currentTime - table.oldestPendingTime;
            const elapsedMinutes = Math.floor(elapsedMs / 60000);
            const elapsedSeconds = Math.floor((elapsedMs % 60000) / 1000);
            tableTimeString = `${elapsedMinutes.toString().padStart(2, '0')}:${elapsedSeconds.toString().padStart(2, '0')}`;
            
            if (elapsedMinutes >= 15) tableTimeColor = "text-red-400 animate-pulse";
            else if (elapsedMinutes >= 10) tableTimeColor = "text-amber-400";
            else tableTimeColor = "text-emerald-400";
          }

          const isFullyDelivered = table.totalItems > 0 && table.completedItems === table.totalItems;

          return (
            <div key={table.nombre} className={`flex flex-col bg-slate-800/60 rounded-2xl border ${isFullyDelivered ? 'border-green-900/50 opacity-60' : 'border-slate-700/80'} overflow-hidden shadow-xl`}>
              <div className="bg-slate-800 px-5 py-4 border-b border-slate-700 flex justify-between items-center">
                <h2 className="font-black text-xl text-white tracking-tight">{table.nombre}</h2>
                <div className="flex items-center gap-4">
                  <span className="text-xs font-bold text-slate-400">{table.completedItems}/{table.totalItems}</span>
                  {!isFullyDelivered && (
                    <div className={`font-mono font-bold flex items-center gap-1 ${tableTimeColor}`}>
                      <Clock size={16} />{tableTimeString}
                    </div>
                  )}
                  {isFullyDelivered && (
                    <div className="text-green-500 flex items-center gap-1 font-bold text-sm">
                      <CheckCircle size={18} /> Listo
                    </div>
                  )}
                </div>
              </div>

              <div className="p-4 flex flex-col gap-3 max-h-[350px] overflow-y-auto custom-scrollbar">
                {table.groupedItems.map((item, idx) => {
                  const status = getStatusDisplay(item.estado);
                  const isDone = item.estado === 'listo';
                  
                  return (
                    <div key={idx} className={`flex flex-col gap-1 p-3 rounded-lg ${status.color} ${isDone ? 'opacity-70 line-through decoration-green-500/50' : ''}`}>
                      <div className="flex justify-between items-start">
                        <div className="flex items-center gap-2">
                          {item.qty > 1 && (
                            <span className="font-black text-xs bg-slate-900/50 px-1.5 py-0.5 rounded shadow-sm text-white">x{item.qty}</span>
                          )}
                          <span className={`font-bold ${isDone ? 'text-slate-400' : 'text-slate-100'}`}>{item.producto}</span>
                        </div>
                        <div className="flex items-center gap-2 mt-0.5 shrink-0">
                          <span className={`h-2 w-2 rounded-full ${status.dot}`}></span>
                          <span className="text-[10px] uppercase font-black tracking-wider opacity-80">{status.label}</span>
                        </div>
                      </div>
                      
                      {Array.isArray(item.notes) && item.notes.length > 0 && !isDone && (
                        <div className="flex gap-1 mt-1">
                          {item.notes.map((note, nIdx) => (
                            <span key={nIdx} className="text-[10px] font-bold text-amber-200/80 bg-black/20 px-1.5 py-0.5 rounded flex items-center gap-1">
                              <AlertCircle size={10} /> {note}
                            </span>
                          ))}
                        </div>
                      )}
                    </div>
                  );
                })}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
};

// ============================================================================
// --- ARCHIVO: /components/Header.jsx ---
// ============================================================================
const Header = ({ currentTime, activeView, setActiveView, activeFilter, setActiveFilter, onSimulate, isFullscreen, toggleFullscreen }) => {
  const timeString = new Date(currentTime).toLocaleTimeString('es-ES', {
    hour: '2-digit', minute: '2-digit', second: '2-digit'
  });

  const filters = [
    { id: 'todo', label: 'Todo' },
    { id: 'barra', label: 'Barra' },
    { id: 'cocina', label: 'Cocina' },
    { id: 'horno', label: 'Horno' },
  ];

  return (
    <header className="bg-slate-900 border-b border-slate-800 px-6 py-4 shrink-0 flex items-center justify-between shadow-xl z-10">
      <div className="flex items-center gap-6">
        <div className="flex items-center gap-3">
          <div className="bg-orange-500 text-white p-2.5 rounded-xl shadow-[0_0_20px_rgba(249,115,22,0.3)]">
            <ChefHat size={28} />
          </div>
          <div>
            <h1 className="text-2xl font-black text-white tracking-tight leading-none mb-1">KITCHEN<span className="text-orange-500">SYNC</span></h1>
            <p className="text-[10px] uppercase text-green-400 font-bold flex items-center gap-1.5 tracking-wider">
              <span className="relative flex h-2 w-2">
                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span className="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
              </span>
              En Línea
            </p>
          </div>
        </div>

        {/* Toggles de Vista */}
        <div className="hidden md:flex bg-slate-800 p-1 rounded-xl border border-slate-700 ml-4">
          <button
            onClick={() => setActiveView('kanban')}
            className={`px-4 py-2 rounded-lg font-bold text-sm transition-all duration-200 flex items-center gap-2 ${
              activeView === 'kanban' ? 'bg-slate-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-700/50'
            }`}
          >
            <KanbanSquare size={16} /> Kanban
          </button>
          <button
            onClick={() => setActiveView('mesas')}
            className={`px-4 py-2 rounded-lg font-bold text-sm transition-all duration-200 flex items-center gap-2 ${
              activeView === 'mesas' ? 'bg-slate-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-700/50'
            }`}
          >
            <LayoutGrid size={16} /> Mesas
          </button>
        </div>
      </div>

      <div className="flex items-center gap-4">
        {/* Filtros Kanban */}
        {activeView === 'kanban' && (
          <div className="hidden lg:flex bg-slate-800/80 p-1 rounded-xl border border-slate-700/50">
            {filters.map(f => (
              <button key={f.id} onClick={() => setActiveFilter(f.id)}
                className={`px-6 py-2 rounded-lg font-bold text-sm transition-all duration-200 ${
                  activeFilter === f.id ? 'bg-orange-500 text-white shadow-md' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-700/50'
                }`}>
                {f.label}
              </button>
            ))}
          </div>
        )}

        {/* Panel de Herramientas (Testing & Pantalla Completa) */}
        <div className="flex items-center gap-2 border-l border-slate-700 pl-4 ml-2">
          <button onClick={() => onSimulate('few')} title="Pocas Comandas" className="p-2.5 rounded-lg bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 transition-colors">
            <Activity size={20} />
          </button>
          <button onClick={() => onSimulate('many')} title="CAOS: Muchas Comandas" className="p-2.5 rounded-lg bg-red-900/30 text-red-500 hover:bg-red-900/60 hover:text-red-400 border border-red-900/50 transition-colors">
            <Zap size={20} />
          </button>
          <button onClick={toggleFullscreen} title="Pantalla Completa" className="p-2.5 rounded-lg bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 transition-colors ml-2 relative">
            {isFullscreen ? <Minimize size={20} /> : <Maximize size={20} />}
          </button>
        </div>
      </div>

    </header>
  );
};

// ============================================================================
// --- ARCHIVO: /App.jsx (Main Container) ---
// ============================================================================
export default function App() {
  const [items, setItems] = useState(INITIAL_ITEMS);
  const [activeView, setActiveView] = useState('kanban'); 
  const [activeFilter, setActiveFilter] = useState('todo');
  const [toasts, setToasts] = useState([]); 
  const [isFullscreen, setIsFullscreen] = useState(false);
  const [fullscreenError, setFullscreenError] = useState(false);
  const currentTime = useCurrentTime(1000);

  // Lógica de Pantalla Completa con captura de error para iframes
  const handleToggleFullscreen = () => {
    if (!document.fullscreenElement) {
      const promise = document.documentElement.requestFullscreen();
      if (promise) {
        promise.catch(err => {
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

  // Lógica de Simulación
  const handleSimulate = (mode) => {
    if (mode === 'few') setItems(INITIAL_ITEMS);
    if (mode === 'many') setItems(generateMassiveData());
    setToasts([]); 
  };

  const handleAdvanceState = (itemId, newState) => {
    setItems(prevItems => {
      const currentItem = prevItems.find(i => i.id === itemId);

      if (newState === 'listo' && currentItem) {
        const toastId = Date.now() + Math.random();
        setToasts(prev => [...prev, {
          id: toastId, itemId: itemId, mesa: currentItem.mesa, producto: currentItem.producto,
          prevState: currentItem.estado, prevTimestamp: currentItem.estado_timestamp
        }]);
        setTimeout(() => setToasts(current => current.filter(t => t.id !== toastId)), 5000);
      }

      return prevItems.map(item => item.id === itemId ? { ...item, estado: newState, estado_timestamp: Date.now() } : item);
    });
  };

  const handleGoBackState = (itemId) => {
    setItems(prevItems => prevItems.map(item => {
      if (item.id === itemId) {
        let prevState = item.estado === 'emplatado' ? item.station : (item.estado === item.station ? 'pendiente' : item.estado);
        if (prevState !== item.estado) return { ...item, estado: prevState, estado_timestamp: Date.now() };
      }
      return item;
    }));
  };

  const handleUndoToast = (itemId, revertState, revertTimestamp, toastId) => {
    setItems(prevItems => prevItems.map(item => item.id === itemId ? { ...item, estado: revertState, estado_timestamp: revertTimestamp } : item));
    setToasts(prev => prev.filter(t => t.id !== toastId));
  };

  const groupedColumns = useMemo(() => {
    let filtered = items.filter(i => i.estado !== 'listo');
    if (activeFilter !== 'todo') filtered = filtered.filter(i => i.station === activeFilter);

    const map = new Map();
    const grouped = [];
    
    filtered.forEach(item => {
      // FIX: Nos aseguramos defensivamente de que notes sea un array al construir la clave (key) para evitar fallos si llega malformado
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

      {/* Notificación de Error de Pantalla Completa */}
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