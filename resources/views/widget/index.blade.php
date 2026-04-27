@extends('layouts.widget')

@section('content')
    <x-bladewind.centered-content size="medium">
        <x-bladewind.modal
            name="ticker-sending-process-modal"
            ok-button-label="Завершить"
            cancel-button-label="Отмена"
        >
            <x-bladewind.processing
                name="ticket-sending-process"
                message="Отправка данных"
            />

            <x-bladewind.process-complete
                name="ticket-sending-complete"
                process_completed_as="passed"
                button-hide
                message="Данные успешно отправлены. Ожидайте ответ от менеджера"
            />

            <x-bladewind.process-complete
                name="ticket-sending-failed"
                process_completed_as="failed"
                button-hide
                message="Ошибка отправки данных"
            />

            <div id="ticket-sending-failed-message" class="text-center text-red-600 mt-4" hidden>
                Ошибка отправки данных
            </div>
        </x-bladewind.modal>

        <x-bladewind.card>
            <div style="text-align: center">
                <x-bladewind.icon name="bookmark" />
                <h1 class="text-2xl font-bold">Обратная связь</h1>
                <p>Заполните форму, и менеджер свяжется с вами после обработки заявки.</p>
            </div>

            <div id="success-message" hidden>Заявка успешно отправлена.</div>
            <div id="error-message" hidden>Произошла ошибка при отправке заявки.</div>

            <section>
                <form class="ticket-form-class" id="ticket-form" action="/api/tickets" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="name">Имя</label>
                        <x-bladewind.input
                            type="text"
                            id="name"
                            name="name"
                            required="true"
                            autocomplete="name"
                            placeholder="Имя"
                            error_message="Поле обьязательно для заполнения"
                        >
                        </x-bladewind.input>
                        <div id="name-error" class="error-text"></div>
                    </div>

                    <div>
                        <label for="phone">Номер телефона</label>
                        <x-bladewind.input
                            type="tel"
                            id="phone"
                            name="phone"
                            placeholder="+996700111222"
                            required="true"
                            autocomplete="tel"
                            placeholder="Номер телефона"
                            error_message="Поле обьязательно для заполнения"
                        />
                        <div id="phone-error" class="error-text"></div>
                    </div>

                    <div>
                        <label for="email">Электронная почта</label>
                        <x-bladewind.input
                            type="email"
                            id="email"
                            name="email"
                            required="true"
                            autocomplete="email"
                            placeholder="Email"
                            error_message="Поле обьязательно для заполнения"
                        />
                        <div id="email-error" class="error-text"></div>
                    </div>

                    <div>
                        <label for="title">Тема</label>
                        <x-bladewind.input
                            type="text"
                            id="title"
                            name="title"
                            required="true"
                            placeholder="Тема"
                            error_message="Поле обьязательно для заполнения"
                        />
                        <div id="title-error" class="error-text"></div>
                    </div>

                    <div>
                        <label for="content">Текст заявки</label>
                        <x-bladewind.textarea
                            id="content"
                            name="content"
                            rows="6"
                            required="true"
                            placeholder="Текст заявки"
                            error_message="Поле обьязательно для заполнения"
                        >
                        </x-bladewind.textarea>
                        <div id="content-error" class="error-text"></div>
                    </div>

                    <div>
                        <label for="attachments">Файлы</label>
                        <x-bladewind.filepicker
                            type="file"
                            id="attachments"
                            name="attachments[]"
                            max_files="10"
                            placeholder="Доп файлы к заявке"
                        />
                        <div id="attachments-error" class="error-text"></div>
                    </div>
                </form>
            </section>
            <x-slot:footer>
                <div>
                    <x-bladewind.button id="submit-button">
                        Отправить заявку
                    </x-bladewind.button>
                </div>
            </x-slot:footer>
        </x-bladewind.card>
    </x-bladewind.centered-content>
@endsection

@section('scripts')
    <script defer>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('ticket-form');
            const submitBtn = document.getElementById('submit-button');
            const failedMessage = document.getElementById('ticket-sending-failed-message');

            const state = {
                loading: true,
                status: null
            }

            submitBtn.addEventListener('click', async (e) => {
                e.preventDefault();

                if (form && !form.reportValidity()) {
                    return;
                }

                unhide('.ticket-sending-process');
                hide('.ticket-sending-failed');
                hide('.ticket-sending-complete');
                showModal('ticker-sending-process-modal');

                setTimeout(async () => {
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const result = await response.json();

                        if (!response.ok) {
                            let errorMessage = ''
                            if (response.status === 422 && result.errors) {
                                const errorKeys = Object.keys(result.errors);
                                errorMessage = result.errors[errorKeys[0]][0]
                                console.log(result.errors[errorKeys[0]][0])
                            } else {
                                errorMessage = result.message || 'Ошибка сервера';
                            }

                            failedMessage.textContent = errorMessage;
                            failedMessage.hidden = false;

                            hide('.ticket-sending-process');
                            unhide('.ticket-sending-failed');
                            return;
                        }

                        hide('.ticket-sending-process');
                        hide('.ticket-sending-failed');
                        unhide('.ticket-sending-complete');

                        form.reset();
                        pond_attachments?.removeFiles();

                        setTimeout(() => {
                           hideModal('ticker-sending-process-modal')
                        }, 3000);

                    } catch (error) {

                        state.status = 'error';
                        console.error('Fetch error:', error);

                        hide('.ticket-sending-process');
                        unhide('.ticket-sending-failed');
                        hide('.ticket-sending-complete');

                    } finally {
                        state.loading = false;
                    }
                }, 1000)
            });
        });
    </script>
@endsection
