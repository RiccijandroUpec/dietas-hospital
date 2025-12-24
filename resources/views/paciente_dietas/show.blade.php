@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Detalle Paciente - Dieta</h2>

                <div class="mb-4">
                    <strong>Paciente:</strong>
                    <div>{{ optional($item->paciente)->nombre }} {{ optional($item->paciente)->apellido }} ({{ optional($item->paciente)->cedula }})</div>
                </div>

                <div class="mb-4">
                    <strong>Dieta:</strong>
                    <div>{{ optional($item->dieta)->nombre }}</div>
                </div>

                <div class="mb-4">
                    <strong>Registró:</strong>
                    <div>{{ optional($item->createdBy)->name }}</div>
                </div>

                <div class="mb-4">
                    <strong>Actualizó:</strong>
                    <div>{{ optional($item->updatedBy)->name }}</div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('paciente-dietas.index') }}" class="text-gray-600">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
