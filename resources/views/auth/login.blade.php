



<x-guest-layout>


    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
  <form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <!-- Email Address -->
    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" />
        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Remember Me -->
    <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center ">
            <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
        </label>
    </div>

    <x-primary-button class=" flex items-center justify-center mt-3" style="width: 400px; height: 40px;">
      {{ __('Log in') }}
  </x-primary-button>

    <div class="flex items-center justify-center mt-4">
        @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        @endif
      
    </div>

      <!-- Separator with OR surrounded by lines -->
      <div class="flex items-center justify-center mt-6 relative">
        <!-- Left Line -->
        <hr class="w-full border-gray-300 border-t" style="flex: 1; margin-right: 8px;" />
        <!-- OR Text -->
        <span class="text-gray-500 whitespace-nowrap bg-white px-2">Or</span>
        <!-- Right Line -->
        <hr class="w-full border-gray-300 border-t" style="flex: 1; margin-left: 8px;" />
      </div>




    <!-- Social Login Buttons -->
    <div class="flex flex-col space-y-4 mt-6">
      <a href="{{ url('auth/facebook/redirect') }}" class="flex items-center justify-center h-12 w-full bg-white border border-gray-300 rounded-lg shadow-md hover:bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="40" fill="#1877F2" class="bi bi-facebook" viewBox="0 0 16 16">
              <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
          </svg>
          <span style="margin-left: 9px;" class="text-sm font-medium text-gray-700">{{ __('Login with Facebook') }}</span>
      </a>
      <a href="{{ url('auth/google/redirect') }}" class="flex items-center justify-center h-12 w-full bg-white border border-gray-300 rounded-lg shadow-md hover:bg-gray-100 mt-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="40" fill="#DB4437" class="bi bi-google" viewBox="0 0 16 16" style="margin-right: 7px;">
            <path d="M15.545 6.558a9.4 9.4 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.8 4.8 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.7 3.7 0 0 0 1.599-2.431H8v-3.08z"/>
        </svg>
        <span style="margin-left: 10px;" class="text-sm font-medium text-gray-700">{{ __('Login with Google') }}</span>
    </a>
      </div>
      <div class="flex items-center justify-center mt-4">
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{route('register')}}">registration manually</a>
      
    </div>
</form>
  
</x-guest-layout>
