@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Detalle de Registro de Dieta</h2>
                <div class="mb-4">
                    <strong>Paciente:</strong> {{ optional($registro->paciente)->nombre }} {{ optional($registro->paciente)->apellido }}<br>
                    <strong>Dietas:</strong>
                    @foreach($registro->dietas as $dieta)
                        <span class="inline-block bg-gray-100 text-gray-800 rounded px-2 py-0.5 mr-1 text-xs">{{ $dieta->nombre }}</span>
                    @endforeach
                    <br>
                    <strong>Tipo de comida:</strong> @php $tipos = ['desayuno'=>'Desayuno','almuerzo'=>'Almuerzo','merienda'=>'Merienda']; @endphp {{ $tipos[$registro->tipo_comida] ?? $registro->tipo_comida }}<br>
                    <strong>Fecha:</strong> {{ $registro->fecha ?? '—' }}<br>
                    <strong>Observaciones:</strong> {{ $registro->observaciones ?? '—' }}<br>
                    <strong>Creado por:</strong> {{ optional($registro->createdBy)->name }}<br>
                    <strong>Actualizado por:</strong> {{ optional($registro->updatedBy)->name }}<br>
                </div>
                <a href="{{ route('registro-dietas.index') }}" class="text-blue-600">Volver al listado</a>
            </div>
        </div>
    </div>
</div>
@endsection
