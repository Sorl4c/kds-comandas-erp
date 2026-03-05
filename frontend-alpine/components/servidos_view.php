<div class="h-full overflow-y-auto custom-scrollbar p-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                <i data-lucide="history" class="w-8 h-8 text-orange-500"></i>
                Historial de Platos Servidos
            </h2>
            <span class="bg-slate-800 border border-slate-700 text-slate-400 px-4 py-1.5 rounded-full text-sm font-bold"
                  x-text="$store.kds.servedItems.length + ' últimos platos'"></span>
        </div>

        <template x-if="$store.kds.servedItems.length === 0">
            <div class="flex flex-col items-center justify-center py-20 text-slate-500 bg-slate-800/20 rounded-3xl border-2 border-dashed border-slate-700/50">
                <i data-lucide="package-open" class="w-16 h-16 mb-4 opacity-20"></i>
                <p class="text-xl font-bold">No hay platos servidos recientemente</p>
                <p class="text-sm">Cuando marques platos como "Listos", aparecerán aquí.</p>
            </div>
        </template>

        <div class="grid grid-cols-1 gap-3">
            <template x-for="item in $store.kds.servedItems" :key="item.id">
                <div class="bg-slate-800/40 border border-slate-700/50 hover:border-slate-600 rounded-2xl p-4 flex items-center justify-between transition-all group shadow-lg backdrop-blur-sm">
                    <div class="flex items-center gap-6">
                        <!-- Info Mesa -->
                        <div class="flex flex-col items-center justify-center bg-slate-900 rounded-xl px-4 py-2 border border-slate-700 min-w-[80px]">
                            <span class="text-[10px] uppercase font-black text-slate-500 leading-none mb-1">Mesa</span>
                            <span class="text-xl font-black text-white leading-none" x-text="item.mesa"></span>
                        </div>
                        
                        <!-- Producto -->
                        <div>
                            <h3 class="text-lg font-bold text-white group-hover:text-orange-400 transition-colors" x-text="item.producto"></h3>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs font-bold px-2 py-0.5 rounded bg-slate-700 text-slate-300 uppercase tracking-wider" x-text="item.station"></span>
                                <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    Servido hace <span x-text="Math.floor(($store.kds.lastUpdate - item.estado_timestamp) / 60000) + ' min'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="flex items-center gap-3">
                        <button @click="$store.kds.goBackState([item.id])" 
                                class="bg-amber-500/10 hover:bg-amber-500 text-amber-500 hover:text-white border border-amber-500/20 px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 active:scale-95 shadow-lg">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            Recuperar Plato
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
