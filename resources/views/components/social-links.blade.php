@props(['socialLinks' => []])

@if($socialLinks && is_array($socialLinks))
    <div class="mt-6">
        <p class="font-semibold uppercase tracking-wide text-gray-500 text-xs mb-3">Социальные сети</p>
        <div class="flex flex-wrap gap-3">
            @foreach($socialLinks as $social)
                @if(is_array($social) && !empty($social['url']))
                    @php
                        $platform = strtolower($social['platform'] ?? '');
                        $icon = $social['icon'] ?? null;
                        $url = $social['url'];
                    @endphp
                    <a href="{{ $url }}" target="_blank" class="text-gray-400 hover:text-white transition-colors" title="{{ ucfirst($social['platform'] ?? 'Social') }}">
                        @if($icon && file_exists(public_path('storage/icons/' . $icon . '.png')))
                            <img src="{{ asset('storage/icons/' . $icon . '.png') }}" alt="{{ $social['platform'] }}" class="w-6 h-6 social-icon object-contain">
                        @elseif($icon && file_exists(public_path('storage/icons/' . $icon . '.svg')))
                            <img src="{{ asset('storage/icons/' . $icon . '.svg') }}" alt="{{ $social['platform'] }}" class="w-6 h-6 social-icon object-contain">
                        @elseif($platform === 'telegram' || $platform === 'tg')
                            <x-social-icons.telegram class="w-6 h-6 social-icon" />
                        @elseif($platform === 'vk' || $platform === 'vkontakte')
                            <x-social-icons.vk class="w-6 h-6 social-icon" />
                        @elseif($platform === 'youtube' || $platform === 'yt')
                            <x-social-icons.youtube class="w-6 h-6 social-icon" />
                        @elseif($platform === 'rutube')
                            <x-social-icons.rutube class="w-6 h-6 social-icon" />
                        @elseif($platform === 'ok' || $platform === 'odnoklassniki')
                            <x-social-icons.ok class="w-6 h-6 social-icon" />
                        @elseif($platform === 'max')
                            <x-social-icons.max class="w-6 h-6 social-icon" />
                        @else
                            <x-heroicon-o-link class="w-6 h-6 social-icon social-icon-default" />
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endif
