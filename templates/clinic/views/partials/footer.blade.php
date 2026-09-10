@php($siteName = setting('site_name', config('app.name')))
<footer class="mt-auto bg-ink text-slate-300">
    <div class="container-x grid gap-10 py-16 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 text-white">
                @if(setting('logo'))
                    <img src="{{ upload_url(setting('logo')) }}" alt="{{ $siteName }}" class="h-10 w-auto brightness-0 invert">
                @else
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-white"><x-icon name="heart" class="h-5 w-5" /></span>
                    <span class="text-xl font-bold tracking-tight">{{ $siteName }}</span>
                @endif
            </a>
            <p class="mt-4 text-sm leading-6 text-slate-400">{{ setting('site_tagline') }}</p>
            @if(setting('emergency_phone'))
                <a href="{{ phone_href(setting('emergency_phone')) }}" class="mt-5 inline-flex items-center gap-2 rounded-lg border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10"><x-icon name="phone" class="h-4 w-4 text-primary-light" /> 7/24 Hasta Destek: {{ setting('emergency_phone') }}</a>
            @endif
            @if(setting('working_hours_note'))<p class="mt-3 flex items-center gap-2 text-xs text-slate-400"><x-icon name="clock" class="h-3.5 w-3.5 text-primary-light" /> {{ setting('working_hours_note') }}</p>@endif
            <div class="mt-5 flex items-center gap-3">
                @foreach(['facebook', 'instagram', 'twitter', 'linkedin', 'youtube'] as $social)
                    @if(setting($social))<a href="{{ setting($social) }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-slate-300 transition hover:bg-primary hover:text-white" aria-label="{{ ucfirst($social) }}"><x-icon :name="$social" class="h-4 w-4" /></a>@endif
                @endforeach
            </div>
        </div>
        <div>
            <h3 class="text-sm font-semibold tracking-wider text-white uppercase">Hızlı Erişim</h3>
            <ul class="mt-4 space-y-2 text-sm">
                @foreach($siteNav as $item)<li><a href="{{ $item['url'] }}" class="transition hover:text-white">{{ $item['label'] }}</a></li>@endforeach
                <li><a href="{{ route('appointments.create') }}" class="transition hover:text-white">{{ setting('appointments_label', 'Randevu Al') }}</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-sm font-semibold tracking-wider text-white uppercase">{{ setting('services_label', 'Tedaviler') }}</h3>
            <ul class="mt-4 space-y-2 text-sm">
                @forelse($footerServices as $service)
                    <li><a href="{{ route('services.show', $service['slug']) }}" class="transition hover:text-white">{{ $service['title'] }}</a></li>
                @empty
                    <li><a href="{{ route('services.index') }}" class="transition hover:text-white">Tüm bölümler</a></li>
                @endforelse
            </ul>
        </div>
        <div>
            <h3 class="text-sm font-semibold tracking-wider text-white uppercase">İletişim</h3>
            <ul class="mt-4 space-y-3 text-sm">
                @if(setting('address'))<li class="flex gap-3"><x-icon name="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-primary-light" /><span>{{ setting('address') }}</span></li>@endif
                @if(setting('phone'))<li class="flex gap-3"><x-icon name="phone" class="mt-0.5 h-4 w-4 shrink-0 text-primary-light" /><a href="{{ phone_href(setting('phone')) }}" class="hover:text-white">{{ setting('phone') }}</a></li>@endif
                @if(setting('email'))<li class="flex gap-3"><x-icon name="mail" class="mt-0.5 h-4 w-4 shrink-0 text-primary-light" /><a href="mailto:{{ setting('email') }}" class="hover:text-white">{{ setting('email') }}</a></li>@endif
                @if(setting('working_hours'))<li class="flex gap-3"><x-icon name="clock" class="mt-0.5 h-4 w-4 shrink-0 text-primary-light" /><span>{{ setting('working_hours') }}</span></li>@endif
                <li class="pt-1"><a href="{{ route('appointments.create') }}" class="btn-primary !py-2.5"><x-icon name="calendar" class="h-4 w-4" /> {{ setting('appointments_label', 'Randevu Al') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="container-x flex flex-col items-center justify-between gap-3 py-5 text-xs text-slate-500 md:flex-row">
            <p>&copy; {{ date('Y') }} {{ $siteName }}. {{ setting('footer_text') }}</p>
            <ul class="flex flex-wrap items-center gap-4">
                @foreach($footerPages as $page)<li><a href="{{ route('page.show', $page['slug']) }}" class="hover:text-white">{{ $page['title'] }}</a></li>@endforeach
            </ul>
        </div>
    </div>
</footer>
