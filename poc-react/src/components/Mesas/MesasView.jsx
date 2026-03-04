// src/components/Mesas/MesasView.jsx
import React, { useMemo } from 'react';
import { Clock, CheckCircle, AlertCircle } from 'lucide-react';

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

export default MesasView;
