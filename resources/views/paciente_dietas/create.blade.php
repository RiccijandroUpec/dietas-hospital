@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Nuevo Paciente - Dieta</h2>

                @if($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('paciente-dietas.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Paciente</label>
                        <select name="paciente_id" class="w-full mt-1 border-gray-300 rounded-md" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($pacientes as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }} {{ $p->apellido }} ({{ $p->cedula }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Dieta</label>
                        <select name="dieta_id" class="w-full mt-1 border-gray-300 rounded-md" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($dietas as $d)
                                <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('paciente-dietas.index') }}" class="mr-3 text-gray-600">Cancelar</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
