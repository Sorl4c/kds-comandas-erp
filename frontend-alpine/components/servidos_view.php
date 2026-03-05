<div class="h-full flex flex-col fade-in">
    <!-- Encabezado de la Vista -->
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-white tracking-tight">Historial de <span class="text-green-500">Servidos</span></h2>
            <p class="text-slate-400 font-medium">Últimos 50 platos finalizados</p>
        </div>
        <div class="bg-slate-800/50 border border-slate-700 px-4 py-2 rounded-xl flex items-center gap-3">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Sesión</span>
            <span class="text-2xl font-mono font-black text-green-400" x-text="$store.kds.items.filter(i => i.estado === 'listo').length"></span>
        </div>
    </div>

    <!-- Tabla de Historial -->
    <div class="flex-1 bg-slate-800/40 rounded-2xl border border-slate-700/60 overflow-hidden shadow-xl flex flex-col">
        <div class="overflow-y-auto custom-scrollbar">
            <table class="w-full text-left border-collapse relative">
                <thead>
                    <tr class="bg-slate-800/80 sticky top-0 z-10 backdrop-blur-md">
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider border-b border-slate-700">Hora</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider border-b border-slate-700">Mesa</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider border-b border-slate-700">Producto</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider border-b border-slate-700 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    <template x-for="item in $store.kds.items.filter(i => i.estado === 'listo').sort((a,b) => b.estado_timestamp - a.estado_timestamp)" :key="item.id">
                        <!-- Cada fila es ahora un componente kanbanCard -->
                        <tr x-data="kanbanCard(item)" 
                            x-show="show"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            class="hover:bg-slate-700/30 transition-colors group relative cursor-pointer select-none touch-none"
                            @pointerdown="handlePointerDown"
                            @pointerup="handlePointerUpOrLeave"
                            @pointerleave="handlePointerUpOrLeave">
                            
                            <td class="px-6 py-4 relative">
                                <!-- Barra de progreso integrada en la primera celda pero expandida a toda la fila -->
                                <div class="absolute bottom-0 left-0 h-1 bg-red-500 ease-linear z-50 pointer-events-none" 
                                     :style="{ 
                                        width: isHolding ? '400%' : '0%', 
                                        transition: isHolding ? 'width 600ms linear' : 'width 100ms ease-out',
                                        opacity: isHolding ? 1 : 0
                                     }"></div>
                                <span class="font-mono text-sm text-slate-400" x-text="new Date(item.estado_timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-white bg-slate-700/50 px-2 py-1 rounded text-sm" x-text="item.mesa"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-200" x-text="item.producto"></span>
                                    <span class="text-[10px] text-slate-500 uppercase font-black" x-text="item.station" :style="{ color: stationColor }"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right relative">
                                <div class="flex items-center justify-end gap-3">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase group-hover:hidden transition-opacity">Mantener p. Recuperar</span>
                                    <button @click="$store.kds.updateStatus(item.id, 'emplatado')" class="opacity-0 group-hover:opacity-100 bg-slate-700 hover:bg-amber-600 hover:text-white text-slate-300 p-2 rounded-lg transition-all shadow-sm flex items-center gap-2 text-xs font-bold">
                                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Recuperar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <!-- Empty State -->
                    <template x-if="$store.kds.items.filter(i => i.estado === 'listo').length === 0">
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="history" class="w-16 h-16 mb-4"></i>
                                    <p class="text-xl font-bold">No hay platos servidos aún</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
