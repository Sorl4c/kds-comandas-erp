// src/components/Toast/ToastContainer.jsx
import React from 'react';
import { CheckCircle, RotateCcw, X } from 'lucide-react';

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
              <span className="text-slate-400">{toast.mesa}</span> • {toast.qty > 1 && <span className="text-orange-400 font-black mr-1">x{toast.qty}</span>}{toast.producto}
            </span>
          </div>
          <div className="w-px h-10 bg-slate-600"></div>
          <button
            onClick={() => onUndo(toast.itemIds, toast.prevState, toast.prevTimestamp, toast.id)}
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

export default ToastContainer;
