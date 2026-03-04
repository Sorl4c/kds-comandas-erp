import React, { useState, useEffect } from 'react';

export default function KanbanCard({ comanda, onAvanzar }) {
  const [elapsedSeconds, setElapsedSeconds] = useState(
    Math.floor((Date.now() - comanda.estado_timestamp) / 1000)
  );

  // Update timer every second
  useEffect(() => {
    const interval = setInterval(() => {
      setElapsedSeconds(Math.floor((Date.now() - comanda.estado_timestamp) / 1000));
    }, 1000);

    return () => clearInterval(interval);
  }, [comanda.estado_timestamp]);

  // Calculations for colors
  const estimatedSeconds = comanda.estimated_time * 60;
  const progress = elapsedSeconds / estimatedSeconds;

  const getTimerColor = () => {
    if (progress >= 1.0) return 'text-red-500';
    if (progress >= 0.75) return 'text-amber-400';
    return 'text-green-400';
  };

  const getCardBorderColor = () => {
    if (progress >= 1.0) return 'border-red-500';
    if (progress >= 0.75) return 'border-amber-500';
    return 'border-green-500';
  };

  const formatTimer = (seconds) => {
    if (isNaN(seconds) || seconds < 0) return "00:00";
    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
    const s = (seconds % 60).toString().padStart(2, '0');
    return `${m}:${s}`;
  };

  return (
    <div className={`bg-gray-800 border-l-4 rounded-lg p-4 shadow-lg flex flex-col gap-3 transition-colors duration-300 ${getCardBorderColor()}`}>
      
      {/* Header: Mesa y Timer */}
      <div className="flex justify-between items-start">
        <span className="bg-gray-900 border border-gray-700 text-gray-200 text-xs font-bold px-2.5 py-1 rounded shadow-inner">
          {comanda.mesa}
        </span>
        <div className={`font-mono text-xl font-bold tracking-tight transition-colors duration-300 ${getTimerColor()}`}>
          {formatTimer(elapsedSeconds)}
        </div>
      </div>

      {/* Body: Producto y Notas */}
      <div className="flex-grow">
        <h2 className="text-xl font-bold mb-1 leading-tight">{comanda.producto}</h2>
        <div className="text-xs text-gray-400 mb-3">Orden: <span>{comanda.orderId}</span></div>
        
        {comanda.notes && comanda.notes.length > 0 && (
          <div className="flex flex-wrap gap-1.5 mt-2">
            {comanda.notes.map((note, index) => (
              <span key={index} className="bg-amber-900/50 border border-amber-700/50 text-amber-200 text-xs px-2 py-0.5 rounded font-medium">
                {note}
              </span>
            ))}
          </div>
        )}
      </div>

      {/* Footer: Estado y Acciones */}
      <div className="mt-4 pt-3 flex justify-between items-center border-t border-gray-700/50">
        <span className={`text-xs uppercase tracking-widest font-bold ${comanda.estado === 'pendiente' ? 'text-gray-400' : 'text-blue-400'}`}>
          {comanda.estado}
        </span>
        <button 
          onClick={() => onAvanzar(comanda.id)}
          className="bg-white/10 hover:bg-white/20 text-white text-sm font-medium py-1.5 px-3 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500"
        >
          Avanzar
        </button>
      </div>

    </div>
  );
}