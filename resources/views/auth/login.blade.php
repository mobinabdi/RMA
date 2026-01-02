<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
        <!-- سر تیتر -->
        <h2 class="text-2xl font-bold text-center mb-6">ورود داوران فدراسیون تکواندو</h2>

        <!-- پیام وضعیت -->
        <div id="status-message" class="mb-4 text-center text-sm"></div>

        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            <!-- کد ملی / کد داوری -->
            <div id="step1">
                <x-input-label for="national_id" :value="__('کد ملی / کد داوری')" />
                <x-text-input id="national_id" class="block mt-1 w-full" type="text" name="national_id" required autofocus />
                <x-input-error :messages="$errors->get('national_id')" class="mt-2" />

                <div class="flex justify-end mt-4">
                    <button type="button" id="step1-next" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">ادامه</button>
                </div>
            </div>

            <!-- OTP -->
            <div id="step2" class="mt-4 hidden">
                <x-input-label for="otp" :value="__('کد پیامکی')" />
                <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" />
                <x-input-error :messages="$errors->get('otp')" class="mt-2" />

                <p class="text-sm text-gray-500 mt-2">زمان باقی‌مانده: <span id="otp-timer">05:00</span></p>

                <div class="flex justify-end mt-4">
                    <x-primary-button class="ms-3">
                        {{ __('ورود') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const step1Next = document.getElementById('step1-next');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const statusMessage = document.getElementById('status-message');
        const timerElement = document.getElementById('otp-timer');

        step1Next.addEventListener('click', () => {
            const nationalId = document.getElementById('national_id').value.trim();
            if (nationalId === '') {
                alert('لطفاً کد ملی یا کد داوری را وارد کنید.');
                return;
            }

            // AJAX بررسی کد ملی / کد داوری و ارسال OTP
            fetch("{{ route('verify.national_id') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ national_id: nationalId })
            })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'ok') {
                        statusMessage.textContent = 'کد درست است. کد پیامکی ارسال شد.';
                        step1.style.display = 'none';
                        step2.style.display = 'block';
                        startOtpTimer(5 * 60);
                    } else {
                        statusMessage.textContent = 'کد وارد شده اشتباه است.';
                    }
                })
                .catch(err => console.log(err));
        });

        function startOtpTimer(duration) {
            let timer = duration, minutes, seconds;
            const interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                timerElement.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    alert('زمان کد پیامکی تمام شد. دوباره تلاش کنید.');
                    location.reload();
                }
            }, 1000);
        }
    </script>
</x-guest-layout>
