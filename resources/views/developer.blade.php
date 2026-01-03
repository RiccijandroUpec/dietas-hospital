@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
            <div class="p-6 sm:p-8 bg-gradient-to-r from-gray-50 via-white to-gray-50">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">👨‍💻 Programador</h2>
                        <p class="text-gray-600 text-sm">Información del desarrollador del sistema</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">Volver al panel</a>
                </div>

                @if (session('error'))
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-700">Nombre</h3>
                        <p class="text-lg font-bold text-gray-900">{{ config('app.developer.name') }}</p>
                        <p class="text-gray-600">{{ config('app.developer.title') }}</p>
                        <p class="text-gray-600">{{ config('app.developer.company') }}</p>
                        <p class="text-gray-500 text-sm mt-1">📍 {{ config('app.developer.location') }}</p>
                    </div>

                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-700">Contacto</h3>
                        <ul class="mt-2 space-y-1 text-gray-700 text-sm">
                            <li><span class="font-semibold">Correo:</span> <a class="text-blue-700 hover:underline" href="mailto:{{ config('app.developer.email') }}">{{ config('app.developer.email') }}</a></li>
                            <li><span class="font-semibold">Teléfono:</span> <a class="text-blue-700 hover:underline" href="tel:{{ config('app.developer.phone') }}">{{ config('app.developer.phone') }}</a></li>
                            <li><span class="font-semibold">Web:</span> <a class="text-blue-700 hover:underline" href="{{ config('app.developer.website') }}" target="_blank" rel="noopener">{{ config('app.developer.website') }}</a></li>
                        </ul>
                    </div>

                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-700">Redes</h3>
                        @php $social = config('app.developer.social'); @endphp
                        <div class="mt-2 flex flex-wrap gap-2">
                            @if(!empty($social['github']))
                                <a href="{{ $social['github'] }}" target="_blank" rel="noopener" class="px-3 py-2 bg-gray-900 text-white text-xs rounded-lg shadow hover:bg-black">GitHub</a>
                            @endif
                            @if(!empty($social['linkedin']))
                                <a href="{{ $social['linkedin'] }}" target="_blank" rel="noopener" class="px-3 py-2 bg-blue-700 text-white text-xs rounded-lg shadow hover:bg-blue-800">LinkedIn</a>
                            @endif
                            @if(!empty($social['twitter']))
                                <a href="{{ $social['twitter'] }}" target="_blank" rel="noopener" class="px-3 py-2 bg-sky-500 text-white text-xs rounded-lg shadow hover:bg-sky-600">Twitter</a>
                            @endif
                            @if(!empty($social['instagram']))
                                <a href="{{ $social['instagram'] }}" target="_blank" rel="noopener" class="px-3 py-2 bg-pink-600 text-white text-xs rounded-lg shadow hover:bg-pink-700">Instagram</a>
                            @endif
                            @if(!empty($social['facebook']))
                                <a href="{{ $social['facebook'] }}" target="_blank" rel="noopener" class="px-3 py-2 bg-blue-600 text-white text-xs rounded-lg shadow hover:bg-blue-700">Facebook</a>
                            @endif
                            @if(!empty($social['whatsapp']))
                                <a href="{{ $social['whatsapp'] }}" target="_blank" rel="noopener" class="px-3 py-2 bg-green-600 text-white text-xs rounded-lg shadow hover:bg-green-700">WhatsApp</a>
                            @endif
                        </div>
                    </div>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700">Exportar base de datos</h3>
                                <p class="text-gray-600 text-sm mt-1">Disponible solo para administradores. Genera un respaldo inmediato.</p>
                            </div>
                            <form action="{{ route('programador.export-db') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition">
                                    🗄️ Exportar base de datos
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm md:col-span-2 lg:col-span-3">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Stack Tecnológico</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(config('app.developer.stack') as $tech)
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full border text-xs">{{ $tech }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
