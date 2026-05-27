<div>
@if($event && $startDate)<section id="countdown" data-start="{{ $startDate }}" class="bg-black py-12 text-white"><div class="mx-auto max-w-5xl px-6 text-center"><p class="text-sm uppercase tracking-[0.35em] text-amber-300">До старта осталось</p><div class="mt-8 grid grid-cols-2 gap-4 md:grid-cols-4">@foreach(['days'=>'дней','hours'=>'часов','minutes'=>'минут','seconds'=>'секунд'] as $key=>$label)<div class="rounded-3xl border border-white/10 bg-white/5 p-6"><div id="countdown-{{ $key }}" class="countdown-value text-5xl font-black text-amber-300">00</div><div class="mt-2 text-sm uppercase tracking-widest text-zinc-400">{{ $label }}</div></div>@endforeach</div></div></section>@endif

</div>
