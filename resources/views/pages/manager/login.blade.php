<x-layouts::manager-auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Авторизация для менеджера')" :description="__('Войти')" />
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="email"
                :label="__('Email')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Пароль')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Пароль')"
                    viewable
                />
            </div>

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::manager-auth>
