<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- نام -->
        <div>
            <x-input-label for="name" value="نام و نام خانوادگی" />
            <x-text-input id="name" class="block mt-1 w-full"
                          type="text"
                          name="name"
                          value="{{ old('name') }}"
                          required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- ایمیل -->
        <div class="mt-4">
            <x-input-label for="email" value="ایمیل" />
            <x-text-input id="email" class="block mt-1 w-full"
                          type="email"
                          name="email"
                          value="{{ old('email') }}"
                          required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- کد ملی -->
        <div class="mt-4">
            <x-input-label for="national_code" value="کد ملی" />
            <x-text-input id="national_code" class="block mt-1 w-full"
                          type="text"
                          name="national_code"
                          value="{{ old('national_code') }}"
                          required maxlength="10" />
            <x-input-error :messages="$errors->get('national_code')" class="mt-2" />
        </div>

        <!-- شماره تماس -->
        <div class="mt-4">
            <x-input-label for="mobile" value="شماره تماس" />
            <x-text-input id="mobile" class="block mt-1 w-full"
                          type="text"
                          name="mobile"
                          value="{{ old('mobile') }}"
                          required placeholder="09xxxxxxxxx" />
            <x-input-error :messages="$errors->get('mobile')" class="mt-2" />
        </div>

        <!-- رمز عبور -->
        <div class="mt-4">
            <x-input-label for="password" value="رمز عبور" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- تکرار رمز -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="تکرار رمز عبور" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation"
                          required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                قبلاً ثبت‌نام کرده‌اید؟
            </a>

            <x-primary-button class="ms-4">
                ثبت‌نام
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
