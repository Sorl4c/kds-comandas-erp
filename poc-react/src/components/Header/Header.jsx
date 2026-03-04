// src/components/Header/Header.jsx
import React from 'react';
import { ChefHat, KanbanSquare, LayoutGrid, Activity, Zap, Maximize, Minimize } from 'lucide-react';

const Header = ({ currentTime, activeView, setActiveView, activeFilter, setActiveFilter, onSimulate, isFullscreen, toggleFullscreen, sseStatus }) => {
  const timeString = new Date(currentTime).toLocaleTimeString('es-ES', {
    hour: '2-digit', minute: '2-digit', second: '2-digit'
  });

  const getStatusConfig = () => {
    switch(sseStatus) {
      case 'connected': return { color: 'text-green-400', label: 'En Línea', dot: 'bg-green-500' };
      case 'connecting': return { color: 'text-amber-400', label: 'Conectando...', dot: 'bg-amber-500 animate-pulse' };
      case 'error': return { color: 'text-red-500', label: 'Error de Red', dot: 'bg-red-500' };
      default: return { color: 'text-slate-400', label: 'Desconectado', dot: 'bg-slate-500' };
    }
  };

  const status = getStatusConfig();

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
            <p className={`text-[10px] uppercase font-bold flex items-center gap-1.5 tracking-wider ${status.color}`}>
              <span className="relative flex h-2 w-2">
                <span className={`absolute inline-flex h-full w-full rounded-full opacity-75 ${status.dot === 'bg-green-500' ? 'animate-ping bg-green-400' : ''}`}></span>
                <span className={`relative inline-flex rounded-full h-2 w-2 ${status.dot}`}></span>
              </span>
              {status.label}
            </p>
          </div>
        </div>

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

        <div className="flex items-center gap-2 border-l border-slate-700 pl-4 ml-2">
          <div className="text-mono font-bold text-slate-400 mr-4 px-3 py-1 bg-slate-800 rounded-lg">{timeString}</div>
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

export default Header;
