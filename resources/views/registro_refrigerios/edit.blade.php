@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Registro</h1>
                <p class="text-gray-600 mt-1">Actualiza la información del registro de refrigerio</p>
            </div>
            <a href="{{ route('registro-refrigerios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <form action="{{ route('registro-refrigerios.update', $registroRefrigerio) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Paciente</label>
                        <select name="paciente_id" class="w-full border rounded-lg px-3 py-2" required>
                            @foreach($pacientes as $p)
                                <option value="{{ $p->id }}" @selected(old('paciente_id', $registroRefrigerio->paciente_id)==$p->id)>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">🥤 Refrigerios</label>
                        <div class="space-y-2 bg-gray-50 p-4 rounded-lg border">
                            @foreach($refrigerios as $r)
                                <label class="flex items-center cursor-pointer hover:bg-white p-2 rounded transition">
                                    <input type="checkbox" name="refrigerio_ids[]" value="{{ $r->id }}" 
                                        class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                                        @if(in_array($r->id, (array)old('refrigerio_ids', $refrigeriosSeleccionados ?? [$registroRefrigerio->refrigerio_id]))) checked @endif>
                                    <span class="ml-3 text-gray-900">{{ $r->nombre }}</span>
                                    @if($r->descripcion)
                                        <span class="ml-2 text-xs text-gray-500">({{ Str::limit($r->descripcion, 50) }})</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                        @error('refrigerio_ids')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        @error('refrigerio_ids.*')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha</label>
                            <input type="date" name="fecha" value="{{ old('fecha', $registroRefrigerio->fecha) }}" class="w-full border rounded-lg px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Momento</label>
                            <select name="momento" class="w-full border rounded-lg px-3 py-2" required>
                                @foreach($momentos as $m)
                                    <option value="{{ $m }}" @selected(old('momento', $registroRefrigerio->momento)==$m)>{{ ucfirst($m) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Observación (opcional)</label>
                        <textarea name="observacion" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('observacion', $registroRefrigerio->observacion) }}</textarea>
                    </div>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('registro-refrigerios.index') }}" class="px-4 py-2 bg-gray-100 rounded">Cancelar</a>
                        <button class="px-6 py-2 bg-orange-600 text-white rounded-lg">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection