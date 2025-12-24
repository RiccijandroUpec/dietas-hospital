@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">{{ $dieta->nombre }}</h2>

                <div class="prose">
                    {!! nl2br(e($dieta->descripcion)) !!}
                </div>

                <div class="pt-4">
                    <a href="{{ route('dietas.index') }}" class="text-gray-600">Volver al listado</a>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('dietas.edit', $dieta) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Editar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
