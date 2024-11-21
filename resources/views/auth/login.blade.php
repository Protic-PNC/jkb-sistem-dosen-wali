<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- <div class="w-full max-w-md"> --}}
    <div class="p-6 pb-0 mb-0 bg-transparent border-b-0 rounded-t-2xl">
        <h3
            class="relative z-10 text-4xl font-bold text-transparent bg-gradient-to-tl from-red-400 to-yellow-400 bg-clip-text">
            Welcome back</h3>
        <p class="mb-0">Enter your email and password to sign in</p>
    </div>
    <div class="flex-auto p-6">

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email Address -->
            <div>
                {{-- <x-input-label for="email" :value="__('Email')" /> --}}
                {{-- <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" /> --}}
                <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Email</label>
                <div class="mb-4">
                    <input type="email" name="email" :value="old('email')" required autofocus
                        autocomplete="username"
                        class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        placeholder="Email" aria-label="Email" aria-describedby="email-addon" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                {{-- <x-input-label for="password" :value="__('Sandi')" /> --}}
                {{-- <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" /> --}}
                <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Password</label>
                <div class="mb-4">
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                        class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        placeholder="Password" aria-label="Password" aria-describedby="password-addon" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            {{-- <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div> --}}
            <div class="min-h-6 mb-0.5 block pl-12">
                <input id="rememberMe"
                    class="mt-0.54 rounded-full duration-250 ease-soft-in-out after:rounded-circle after:shadow-soft-2xl after:duration-250 checked:after:translate-x-5.25 h-5 relative float-left -ml-12 w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-slate-800/95 checked:bg-slate-800/95 checked:bg-none checked:bg-right"
                    type="checkbox" checked="" />
                <label class="mb-2 ml-1 font-normal cursor-pointer select-none text-sm text-slate-700"
                    for="rememberMe">Remember me</label>
            </div>

            <!-- Login Button -->
            {{-- <div class="flex items-center justify-between mt-6"> --}}
            {{-- @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}">
                        {{ __('Lupa sandi?') }}
                    </a>
                @endif --}}

            <div class="text-center">
                <button type="submit"
                    class="inline-block w-full px-6 py-3 mt-6 mb-0 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg cursor-pointer shadow-soft-md bg-x-25 bg-150 leading-pro text-xs ease-soft-in tracking-tight-soft bg-gradient-to-tl from-red-400 to-yellow-400 hover:scale-102 hover:shadow-soft-xs active:opacity-85">Sign
                    in</button>
            </div>
            {{-- </div> --}}
        </form>
    </div>
    {{-- </div> --}}
</x-guest-layout>
