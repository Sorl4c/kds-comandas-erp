// src/components/Kanban/KanbanColumn.jsx
import React from 'react';
import { CheckCircle } from 'lucide-react';
import KanbanCard from './KanbanCard';

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

export default KanbanColumn;
