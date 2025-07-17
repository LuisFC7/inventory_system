@if($items->isEmpty())
    <div class="text-center py-8 text-gray-500">
        <i class="fas fa-box-open fa-2x mb-2"></i>
        <p>No se encontraron resultados con los filtros seleccionados</p>
    </div>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activo Fijo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->item_activo_fijo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->item_nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusClasses = [
                                'DISPONIBLE' => 'bg-green-100 text-green-800 border-green-200',
                                'DETENIDA' => 'bg-red-100 text-red-800 border-red-200',
                                'TRABAJANDO' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'POR SALIR' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'COMPRAS' => 'bg-purple-100 text-purple-800 border-purple-200',
                            ];

                            $statusClass = $statusClasses[$item->item_status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        @endphp

                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $statusClass }}">
                            {{ $item->item_status }}
                        </span>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $item->item_origen }} → {{ $item->item_destino }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="px-4 py-4 border-t border-gray-200 pagination">
            {!! $items->appends(request()->except('page'))->links() !!}
        </div>
    @endif
@endif