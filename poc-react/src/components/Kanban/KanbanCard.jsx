// src/components/Kanban/KanbanCard.jsx
import React, { useState, useRef } from 'react';
import { Clock, AlertCircle } from 'lucide-react';
import { STATION_COLORS, WORKFLOW } from '../../data/mockData';

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
        onGoBack(ids); // Enviamos todos los IDs
        if (navigator.vibrate) navigator.vibrate([50, 50, 50]);
      }
      setIsHolding(false);
    }, HOLD_DURATION);
  };

  const handlePointerUpOrLeave = (e) => {
    setIsHolding(false);
    if (holdTimeout.current) clearTimeout(holdTimeout.current);

    if (e.type === 'pointerup' && !hasLongPressed.current && nextState) {
      onAdvance(ids, nextState); // Enviamos todos los IDs
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
          {prevState && <span className="text-amber-500/70">Mantener ➜ Atrás</span>}
          <span className="text-orange-400">Tap ➜ {nextState || 'Terminar'}</span>
        </div>
      </div>
    </div>
  );
};

export default KanbanCard;
