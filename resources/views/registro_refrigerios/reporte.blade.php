@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📊 Reporte de Refrigerios</h1>
                <p class="text-gray-600 mt-1">Estadísticas y registros recientes</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <p class="text-sm text-gray-600">Total Registros</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $total }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Hoy</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $hoy }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-600">Por Momento</p>
                <ul class="mt-2 text-gray-800">
                    @foreach($porMomento as $pm)
                        <li>{{ ucfirst($pm->momento) }}: <strong>{{ $pm->total }}</strong></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-orange-50 to-orange-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Paciente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Refrigerio</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Momento</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($registros as $r)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $r->paciente->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $r->refrigerio->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($r->momento) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t">{{ $registros->links() }}</div>
        </div>
    </div>
</div>
@endsection