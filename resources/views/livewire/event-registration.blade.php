<main class="min-h-screen bg-zinc-950 px-6 py-12 text-white">
    <div class="mx-auto max-w-5xl">
        @if($event)
            <h1 class="text-4xl font-black">Регистрация: {{ $event->title }}</h1>
            @if($event->yandex_form_url)
                <iframe src="{{ $event->yandex_form_url }}" title="Форма регистрации Яндекс" class="mt-8 h-[720px] w-full rounded-3xl bg-white" frameborder="0" loading="lazy"></iframe>
            @elseif($event->registration_url)
                <iframe src="{{ $event->registration_url }}" title="Форма регистрации" class="mt-8 h-[720px] w-full rounded-3xl bg-white" frameborder="0" loading="lazy"></iframe>
            @else
                <p class="mt-8 text-zinc-300">Регистрация для этого мероприятия пока не открыта.</p>
            @endif
        @else
            <h1 class="text-4xl font-black">Регистрация</h1>
            <p class="mt-8 text-zinc-300">Активное мероприятие не найдено.</p>
        @endif
    </div>
</main>

