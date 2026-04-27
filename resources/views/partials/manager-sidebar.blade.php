<aside class="w-72 shrink-0 bg-slate-950 text-slate-200 min-h-screen border-r border-slate-800">
    <div class="h-20 px-6 flex items-center border-b border-slate-800">
        <div>
            <div class="text-lg font-bold text-blue-900/50">
                Менеджер
            </div>
            <div class="text-xs text-slate-400">
                Управление заявками
            </div>
        </div>
    </div>

    <nav class="px-4 py-6 space-y-2">
        <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 text-black"
        >
            <x-bladewind.icon name="home" class="!w-5 !h-5" />
            <span class="font-medium">Главная</span>
        </a>

        <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition text-slate-700 hover:text-black"
        >
            <x-bladewind.icon name="document-text" class="!w-5 !h-5" />
            <span>Тикеты</span>
        </a>

        <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition text-slate-700 hover:text-black"
        >
            <x-bladewind.icon name="users" class="!w-5 !h-5" />
            <span>Пользователи</span>
        </a>

        <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition text-slate-700 hover:text-black"
        >
            <x-bladewind.icon name="cog-6-tooth" class="!w-5 !h-5" />
            <span>Настройки</span>
        </a>
    </nav>

    <div class="absolute bottom-0 w-72 p-4 border-t border-slate-800">
        <div class="rounded-2xl bg-white/5 p-4">
            <div class="text-sm font-medium text-black">
                Администратор
            </div>
            <div class="text-xs text-slate-400 mt-1">
                admin@example.com
            </div>

            <form method="POST" action="#">
                @csrf

                <button
                    type="submit"
                    class="mt-4 w-full text-left text-sm text-red-300 hover:text-red-200"
                >
                    Выйти
                </button>
            </form>
        </div>
    </div>
</aside>
