@if($event && $documents->isNotEmpty())
    <section id="documents" class="bg-white py-16 text-zinc-950">
        <div class="mx-auto max-w-4xl px-6">
            <p class="font-bold uppercase tracking-widest text-cyan-600">Документы</p>
            <h2 class="mt-3 text-3xl font-black">Материалы мероприятия</h2>
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                @foreach($documents as $doc)
                    <a href="{{ asset('storage/' . $doc->file_path) }}"
                       target="_blank"
                       class="flex items-center gap-4 rounded-2xl bg-zinc-100 p-5 transition hover:bg-zinc-200">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-cyan-500 text-white font-bold">
                            @if(str_ends_with($doc->file_path, '.pdf'))
                                PDF
                            @elseif(str_contains($doc->file_path, '.doc'))
                                DOC
                            @else
                                📄
                            @endif
                        </div>
                        <div>
                            <div class="font-bold">{{ $doc->title }}</div>
                            <div class="text-sm text-zinc-500">Открыть</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif