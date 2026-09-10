{{-- Randevu CTA bandı: marka renkli zemin --}}
@if(setting('cta_title'))
<section class="relative overflow-hidden bg-primary text-white">
    <div class="pointer-events-none absolute -top-20 right-10 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
    <div class="container-x relative flex flex-col items-center justify-between gap-6 py-16 text-center md:flex-row md:text-left">
        <div class="max-w-2xl">
            <h2 class="font-display text-3xl md:text-4xl [&_em]:text-white [&_em]:italic">{!! emphasize(setting('cta_title')) !!}</h2>
            @if(setting('cta_text'))<p class="mt-3 text-lg text-white/85">{{ setting('cta_text') }}</p>@endif
        </div>
        <div class="flex shrink-0 flex-col items-center gap-3 sm:flex-row">
            @if(setting('cta_button_text'))<a href="{{ setting('cta_button_url', route('appointments.create')) }}" class="btn-light !px-7 !py-3.5">{{ setting('cta_button_text') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>@endif
            @if(setting('emergency_phone'))<a href="{{ phone_href(setting('emergency_phone')) }}" class="btn-outline-light !py-3.5"><x-icon name="phone" class="h-4 w-4" /> {{ setting('emergency_phone') }}</a>@endif
        </div>
    </div>
</section>
@endif
