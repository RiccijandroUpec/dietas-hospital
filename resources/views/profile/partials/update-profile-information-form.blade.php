<section class="bg-white rounded-lg shadow-md p-6">
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            👤 Información de perfil
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Actualiza tu nombre y correo electrónico. Esta información será visible en el sistema.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name"
            />
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="email"
            />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                    <p class="text-sm text-yellow-800">
                        ⚠️ Tu correo electrónico no está verificado.
                    </p>
                    <button 
                        form="send-verification" 
                        class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium underline"
                    >
                        Reenviar correo de verificación
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 font-medium">
                            ✓ Enlace de verificación enviado a tu correo.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
            <button type="submit" class="px-5 py-2.5 bg-blue-300 text-blue-900 rounded-lg hover:bg-blue-400 transition font-semibold shadow-md">
                💾 Guardar cambios
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 font-medium"
                >✓ Perfil actualizado correctamente</p>
            @endif
        </div>
    </form>
</section>
