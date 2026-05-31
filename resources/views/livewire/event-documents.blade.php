@if($event && $documents->isNotEmpty())
    <section id="documents" class="bg-[var(--color-background)] py-20 text-[var(--color-text)]">
        <div class="mx-auto max-w-6xl px-4">
            <p class="font-semibold uppercase tracking-wide text-[var(--color-muted)] text-xs mb-2">Документы</p>
            <!-- <h2 class="mt-3 text-4xl font-bold text-[var(--color-text)]">Документы мероприятия</h2> -->

            <div class="mt-12 grid gap-6 sm:grid-cols-2">
                @foreach($documents as $doc)
                    <div class="document-card rounded-[var(--radius-card)] border border-[var(--color-border)] bg-white p-4 shadow-sm hover:shadow-[0_8px_24px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 transition-all flex items-center gap-4">
                        <div class="flex h-20 w-20 items-center justify-center rounded-[var(--radius-round)] bg-[var(--color-text)] text-white shadow-md flex-shrink-0">
                            @if(str_ends_with($doc->file_path, '.pdf'))
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>
                            @elseif(str_contains($doc->file_path, '.doc'))
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                            @elseif(str_ends_with($doc->file_path, '.xls') || str_contains($doc->file_path, '.xlsx'))
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>
                            @else
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="text-lg font-semibold text-[var(--color-text)]">{{ $doc->title }}</div>
                            <div class="mt-1 text-sm text-[var(--color-muted)]">Документ в формате
                                @if(str_ends_with($doc->file_path, '.pdf'))
                                    PDF
                                @elseif(str_contains($doc->file_path, '.doc'))
                                    DOC
                                @elseif(str_ends_with($doc->file_path, '.xls') || str_contains($doc->file_path, '.xlsx'))
                                    Excel
                                @else
                                    {{ strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION)) }}
                                @endif
                            </div>
                            @if($doc->description)
                                <div class="mt-1 text-sm text-[var(--color-text-secondary)]">{!! $doc->description !!}</div>
                            @endif
                            <a href="{{ asset('storage/' . $doc->file_path) }}"
                               target="_blank"
                               class="mt-2 inline-block rounded-[var(--radius-btn)] bg-[var(--color-primary)] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[var(--color-primary-hover)] transition-colors">Открыть</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
