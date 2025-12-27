<footer class="mt-10 border-t bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Perfil del Programador -->
            <div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">👨‍💻 Programador</h4>
                <p class="text-gray-700 font-semibold">{{ config('app.developer.name') }}</p>
                <p class="text-gray-600">{{ config('app.developer.title') }}</p>
                <p class="text-gray-600">{{ config('app.developer.company') }}</p>
                <p class="text-gray-500 mt-1">📍 {{ config('app.developer.location') }}</p>
            </div>

            <!-- Contacto -->
            <div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">✉️ Contacto</h4>
                <ul class="space-y-1 text-gray-700">
                    <li>
                        <a class="hover:text-blue-700 underline" href="mailto:{{ config('app.developer.email') }}">{{ config('app.developer.email') }}</a>
                    </li>
                    <li>
                        <a class="hover:text-blue-700 underline" href="tel:{{ config('app.developer.phone') }}">{{ config('app.developer.phone') }}</a>
                    </li>
                    <li>
                        <a class="hover:text-blue-700 underline" href="{{ config('app.developer.website') }}" target="_blank" rel="noopener">{{ config('app.developer.website') }}</a>
                    </li>
                </ul>
            </div>

            <!-- Redes Sociales -->
            <div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">🔗 Redes Sociales</h4>
                <div class="flex flex-wrap gap-2">
                    @php $social = config('app.developer.social'); @endphp
                    @if(!empty($social['github']))
                        <a href="{{ $social['github'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 bg-gray-900 text-white rounded-lg shadow hover:bg-black">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .5C5.73.5.75 5.48.75 11.76c0 4.92 3.19 9.09 7.62 10.57.56.1.77-.24.77-.54 0-.27-.01-1.17-.02-2.12-3.1.67-3.76-1.33-3.76-1.33-.51-1.31-1.25-1.66-1.25-1.66-1.02-.7.08-.69.08-.69 1.13.08 1.73 1.16 1.73 1.16 1 .1 1.55-.25 1.88-.48.1-.73.4-1.25.72-1.54-2.47-.28-5.07-1.23-5.07-5.47 0-1.21.43-2.2 1.14-2.98-.11-.28-.49-1.42.11-2.96 0 0 .93-.3 3.05 1.14.89-.25 1.85-.38 2.8-.39.95 0 1.91.13 2.8.39 2.12-1.44 3.04-1.14 3.04-1.14.6 1.54.22 2.68.11 2.96.71.78 1.13 1.77 1.13 2.98 0 4.25-2.61 5.18-5.1 5.46.41.35.77 1.03.77 2.07 0 1.49-.01 2.69-.01 3.06 0 .3.21.65.78.54 4.43-1.49 7.61-5.65 7.61-10.57C23.25 5.48 18.27.5 12 .5z"/></svg>
                            GitHub
                        </a>
                    @endif
                    @if(!empty($social['linkedin']))
                        <a href="{{ $social['linkedin'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-700 text-white rounded-lg shadow hover:bg-blue-800">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.49 6S0 4.88 0 3.5 1.12 1 2.49 1 4.98 2.12 4.98 3.5zM.5 8h4V23h-4zM8.5 8h3.8v2.05h.05c.53-1 1.82-2.05 3.75-2.05 4.01 0 4.75 2.64 4.75 6.07V23h-4v-5.3c0-1.26-.02-2.88-1.76-2.88-1.76 0-2.03 1.38-2.03 2.79V23h-4z"/></svg>
                            LinkedIn
                        </a>
                    @endif
                    @if(!empty($social['twitter']))
                        <a href="{{ $social['twitter'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 bg-sky-500 text-white rounded-lg shadow hover:bg-sky-600">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3c-.8.4-1.7.7-2.6.8.9-.5 1.6-1.4 1.9-2.4-.9.5-1.9.9-3 1.1C18.4 1.8 17.2 1 15.8 1c-2.6 0-4.6 2.2-4.2 4.7C7.7 5.5 4.5 3.9 2.2 1.3c-.9 1.6-.5 3.7 1.1 4.8-.7 0-1.3-.2-1.9-.5v.1c0 2.3 1.6 4.2 3.8 4.7-.4.1-.8.1-1.2.1-.3 0-.6 0-.8-.1.6 1.9 2.4 3.2 4.5 3.2-1.7 1.3-3.9 2.1-6.2 2.1-.4 0-.8 0-1.2-.1 2.2 1.4 4.9 2.2 7.7 2.2 9.3 0 14.4-7.8 14-14.8.9-.6 1.7-1.4 2.3-2.2z"/></svg>
                            Twitter
                        </a>
                    @endif
                    @if(!empty($social['instagram']))
                        <a href="{{ $social['instagram'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 bg-pink-600 text-white rounded-lg shadow hover:bg-pink-700">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.9.2 2.3.4.6.2 1 .5 1.5 1 .5.5.8.9 1 1.5.2.4.3 1.1.4 2.3.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.9-.4 2.3-.2.6-.5 1-1 1.5-.5.5-.9.8-1.5 1-.4.2-1.1.3-2.3.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.9-.2-2.3-.4-.6-.2-1-.5-1.5-1-.5-.5-.8-.9-1-1.5-.2-.4-.3-1.1-.4-2.3C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-1.9.4-2.3.2-.6.5-1 1-1.5.5-.5.9-.8 1.5-1 .4-.2 1.1-.3 2.3-.4C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.7.1-1 .1-1.6.2-2 .4-.5.2-.8.4-1.2.8-.4.4-.6.7-.8 1.2-.2.4-.3 1-.4 2-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1 1 .2 1.6.4 2 .2.5.4.8.8 1.2.4.4.7.6 1.2.8.4.2 1 .3 2 .4 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1-.1 1.6-.2 2-.4.5-.2.8-.4 1.2-.8.4-.4.6-.7.8-1.2.2-.4.3-1 .4-2 .1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-1-.2-1.6-.4-2-.2-.5-.4-.8-.8-1.2-.4-.4-.7-.6-1.2-.8-.4-.2-1-.3-2-.4-1.2-.1-1.6-.1-4.7-.1zM12 5.8a6.2 6.2 0 110 12.4 6.2 6.2 0 010-12.4zm0 2.2a4 4 0 100 8 4 4 0 000-8zm5.7-2.8a1.4 1.4 0 11-2.8 0 1.4 1.4 0 012.8 0z"/></svg>
                            Instagram
                        </a>
                    @endif
                    @if(!empty($social['facebook']))
                        <a href="{{ $social['facebook'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12C22 6.48 17.52 2 12 2S2 6.48 2 12c0 4.84 3.44 8.85 7.94 9.8v-6.93H7.9V12h2.04V9.8c0-2.02 1.2-3.13 3.04-3.13.88 0 1.8.16 1.8.16v1.98h-1.01c-.99 0-1.3.62-1.3 1.26V12h2.21l-.35 2.87h-1.86v6.93C18.56 20.85 22 16.84 22 12z"/></svg>
                            Facebook
                        </a>
                    @endif
                    @if(!empty($social['whatsapp']))
                        <a href="{{ $social['whatsapp'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A10.74 10.74 0 0 0 12 0C5.37 0 0 5.37 0 12c0 2.1.55 4.17 1.6 5.99L0 24l6.18-1.61a11.96 11.96 0 0 0 5.82 1.49c6.63 0 12-5.37 12-12 0-3.21-1.25-6.23-3.48-8.4zM12 21.6c-1.87 0-3.68-.5-5.28-1.44l-.38-.22-3.66.95.98-3.57-.25-.37A9.6 9.6 0 1 1 21.6 12c0 5.29-4.31 9.6-9.6 9.6zm5.48-7.23c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.66.16-.19.3-.76.97-.93 1.17-.17.2-.34.22-.64.07-.3-.15-1.28-.47-2.44-1.5-.9-.77-1.5-1.71-1.68-2-.17-.3-.02-.46.13-.61.13-.13.3-.34.45-.52.15-.18.2-.31.3-.52.1-.2.05-.39-.02-.54-.08-.15-.66-1.6-.9-2.2-.24-.58-.5-.5-.66-.5-.17 0-.36-.02-.55-.02-.2 0-.52.07-.79.39-.27.31-1.05 1.03-1.05 2.52 0 1.49 1.08 2.92 1.23 3.12.15.2 2.12 3.25 5.12 4.41.72.31 1.28.49 1.72.63.72.23 1.37.2 1.88.12.58-.09 1.77-.72 2.02-1.41.25-.69.25-1.28.17-1.41-.08-.13-.27-.2-.57-.35z"/></svg>
                            WhatsApp
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stack / Tecnologías -->
            <div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">🛠️ Stack Tecnológico</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach(config('app.developer.stack') as $tech)
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full border">{{ $tech }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t text-center text-sm text-gray-600">
            <p>&copy; {{ now()->year }} {{ config('app.name') }}. Desarrollado por {{ config('app.developer.name') }}.</p>
        </div>
    </div>
</footer>
