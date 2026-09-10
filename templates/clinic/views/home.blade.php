@extends('template::layouts.app')

@section('content')
    {{-- Hero: tam genişlik fotoğraf, açık (beyazımsı-mavi) overlay, lacivert başlık; ikinci satır marka renginde --}}
    <section class="hero-bg relative flex min-h-[560px] items-center overflow-hidden md:min-h-[600px]">
        @if(setting('hero_image'))
            <img src="{{ upload_url(setting('hero_image')) }}" alt="" class="absolute inset-0 h-full w-full object-cover object-right" fetchpriority="high">
        @else
            <div class="pointer-events-none absolute -top-32 right-0 h-[28rem] w-[40rem] rounded-full bg-primary/10 blur-3xl"></div>
        @endif
        <div class="hero-veil absolute inset-0"></div>

        <div class="container-x relative py-20 md:py-24">
            <div class="max-w-xl">
                @if(setting('hero_eyebrow'))<p class="eyebrow">{{ setting('hero_eyebrow') }}</p>@endif
                <h1 class="hero-title text-5xl leading-[1.05] text-ink md:text-6xl lg:text-7xl">{!! emphasize(setting('hero_title', 'Sağlığınız *Bizim Önceliğimiz*')) !!}</h1>
                @if(setting('hero_subtitle'))<p class="mt-6 max-w-md text-lg leading-8 text-slate-700">{{ setting('hero_subtitle') }}</p>@endif
                <div class="mt-8 flex flex-wrap gap-3">
                    @if(setting('hero_button_text'))<a href="{{ setting('hero_button_url', route('appointments.create')) }}" class="btn-primary !px-6 !py-3.5"><x-icon name="calendar" class="h-4 w-4" /> {{ setting('hero_button_text') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>@endif
                    @if(setting('hero_button2_text'))<a href="{{ setting('hero_button2_url', '/hizmetler') }}" class="btn-outline !px-6 !py-3.5">{{ setting('hero_button2_text') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>@endif
                </div>
            </div>
        </div>

        {{-- Sağ altta yüzen bilgi kartı --}}
        @if(setting('hero_card_title'))
            <a href="{{ route('team.index') }}" class="absolute right-[5%] bottom-[10%] hidden w-[22rem] items-center gap-4 rounded-lg bg-white p-4 shadow-xl transition hover:-translate-y-0.5 lg:flex">
                <span class="icon-box"><x-icon name="users" class="h-6 w-6" /></span>
                <span class="flex-1 text-sm leading-6 font-semibold text-ink">{{ setting('hero_card_title') }}@if(setting('hero_card_text'))<span class="block">{{ setting('hero_card_text') }}</span>@endif</span>
                <x-icon name="chevron-right" class="h-5 w-5 text-primary" />
            </a>
        @endif
    </section>

    {{-- Hero'ya binen beyaz özellik kartı --}}
    <div class="container-x relative z-10 -mt-10">
        <div class="grid divide-y divide-slate-200 rounded-lg bg-white shadow-lg sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-5 lg:divide-x">
            @foreach([
                ['cpu', 'Modern', 'Tıbbi Teknoloji'],
                ['heart', 'Hasta Odaklı', 'Hizmet'],
                ['calendar', 'Kolay', 'Randevu Sistemi'],
                ['map-pin', 'Merkezi', 'Konum'],
                ['phone', '7/24 Hasta', 'Destek Hattı'],
            ] as [$icon, $line1, $line2])
                <div class="flex items-center gap-4 px-6 py-5">
                    <span class="icon-box"><x-icon :name="$icon" class="h-6 w-6" /></span>
                    <span class="text-sm leading-6 font-semibold text-ink">{{ $line1 }}<span class="block">{{ $line2 }}</span></span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Bölümler / tıbbi birimler + Online Randevu kartı --}}
    <section class="section" id="bolumler">
        <div class="container-x">
            <x-section-heading eyebrow="Hizmetlerimiz" :title="setting('services_home_title', 'Sağlığınız İçin Buradayız')" :actionUrl="route('services.index')" :actionLabel="'Tüm '.setting('services_label', 'Tıbbi Birimler')" />
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
                @foreach($services as $service)<x-service-card :service="$service" />@endforeach
                <div class="relative flex flex-col overflow-hidden rounded-lg bg-primary p-6 text-white sm:col-span-2 lg:col-span-{{ 5 - $services->count() % 5 }}">
                    <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-white/15"><x-icon name="calendar" class="h-6 w-6" /></span>
                    <h3 class="mt-4 text-xl font-semibold">Online Randevu</h3>
                    <p class="mt-2 text-sm leading-6 text-white/85">{{ setting('online_appointment_text', 'Uzman doktorlarımızdan kolayca randevu alın, sağlığınızı ertelemeyin.') }}</p>
                    <a href="{{ route('appointments.create') }}" class="btn-light mt-5 w-full">Randevu Oluştur <x-icon name="arrow-right" class="h-4 w-4" /></a>
                    <div class="mt-auto flex items-center gap-3 pt-10 opacity-40">
                        @if(setting('logo'))<img src="{{ upload_url(setting('logo')) }}" alt="" class="h-8 w-auto brightness-0 invert" loading="lazy">@else<x-icon name="heart" class="h-10 w-10" />@endif
                        @if(setting('site_tagline'))<span class="text-[11px] leading-5 font-medium tracking-[0.2em] uppercase">{{ setting('site_tagline') }}</span>@endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- İstatistikler + alıntı --}}
    @if($stats->isNotEmpty())
        <section class="border-y border-slate-200 bg-mist">
            <div class="container-x grid items-center gap-10 py-12 lg:grid-cols-5">
                <div class="grid grid-cols-2 divide-slate-200 sm:grid-cols-4 sm:divide-x lg:col-span-3">
                    @foreach($stats as $stat)
                        <div class="px-4 py-3 text-center sm:text-left"><p class="font-display text-4xl text-ink md:text-5xl">{{ $stat['value'] }}</p><p class="mt-1 text-sm font-medium text-slate-500">{{ $stat['label'] }}</p></div>
                    @endforeach
                </div>
                <figure class="relative pl-10 lg:col-span-2">
                    <span class="absolute top-0 left-0 font-display text-6xl leading-none text-accent">“</span>
                    <blockquote class="font-display text-2xl leading-snug text-ink italic">{{ setting('site_tagline', 'Sağlıklı, güzel, mutlu yarınlara…') }}</blockquote>
                    <figcaption class="mt-3 text-xs font-medium tracking-[0.2em] text-slate-500 uppercase">{{ setting('site_name', config('app.name')) }}</figcaption>
                </figure>
            </div>
        </section>
    @endif

    {{-- Neden biz --}}
    @if(setting('about_text'))
        <section class="section">
            <div class="container-x grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <x-section-heading eyebrow="Neden Biz" :title="setting('about_title', 'Hakkımızda')" class="!mb-6" />
                    <div class="space-y-4 text-lg leading-8 text-slate-600">
                        @foreach(preg_split('/\r\n|\r|\n/', setting('about_text')) as $paragraph)@if(trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif @endforeach
                    </div>
                    <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                        @foreach(['Aynı gün tetkik ve sonuç', 'Uzman ve deneyimli hekimler', 'Modern görüntüleme altyapısı', 'Anlaşmalı sigorta kurumları', 'Ücretsiz kapalı otopark', 'Çocuk dostu bekleme alanı'] as $item)
                            <li class="flex items-center gap-2 text-sm font-medium text-ink"><x-icon name="check-circle" class="h-5 w-5 shrink-0 text-primary" /> {{ $item }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('appointments.create') }}" class="btn-primary mt-9"><x-icon name="calendar" class="h-4 w-4" /> {{ setting('appointments_label', 'Randevu Al') }}</a>
                </div>
                <div>
                    @if(setting('about_image'))
                        <img src="{{ upload_url(setting('about_image')) }}" alt="{{ setting('about_title', 'Hakkımızda') }}" class="aspect-[4/3] w-full rounded-lg object-cover shadow-lg" loading="lazy">
                    @elseif(setting('hero_image'))
                        <img src="{{ upload_url(setting('hero_image')) }}" alt="{{ setting('about_title', 'Hakkımızda') }}" class="aspect-[4/3] w-full rounded-lg object-cover object-right shadow-lg" loading="lazy">
                    @else
                        <div class="flex aspect-[4/3] items-center justify-center rounded-lg border border-slate-200 bg-gradient-to-br from-white to-primary-light text-primary/40 shadow-sm"><x-icon name="heart" class="h-24 w-24" /></div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- Doktorlar --}}
    @if($team->isNotEmpty())
        <section class="section bg-mist">
            <div class="container-x">
                <x-section-heading eyebrow="Hekim Kadromuz" :title="setting('team_home_title', 'Doktorlarımız')" :subtitle="setting('team_home_intro')" :actionUrl="route('team.index')" actionLabel="Tüm Doktorlar" />
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($team as $member)<x-team-card :member="$member" />@endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Anlaşmalı kurum logoları --}}
    @if($partners->isNotEmpty())
        <section class="section !py-14" id="kurumlar">
            <div class="container-x">
                <p class="eyebrow text-center">{{ setting('insurance_partners_title', 'Anlaşmalı Kurumlar') }}</p>
                <p class="mx-auto mb-8 max-w-xl text-center font-display text-2xl text-ink">Önde gelen özel ve tamamlayıcı sağlık sigortalarıyla anlaşmalıyız.</p>
                <x-partner-strip :partners="$partners" />
            </div>
        </section>
    @endif

    {{-- Hasta yorumları --}}
    @if($testimonials->isNotEmpty())
        <section class="section bg-mist">
            <div class="container-x">
                <x-section-heading eyebrow="Hasta Deneyimleri" title="Hastalarımız ne *diyor*?" subtitle="Bize güvenen hastalarımızın deneyimleri, en değerli referansımızdır." />
                <div class="grid gap-6 md:grid-cols-3">@foreach($testimonials as $testimonial)<x-testimonial-card :testimonial="$testimonial" />@endforeach</div>
            </div>
        </section>
    @endif

    {{-- SSS --}}
    @if($faqs->isNotEmpty())
        <section class="section">
            <div class="container-x grid gap-12 lg:grid-cols-5">
                <div class="lg:col-span-2">
                    <x-section-heading eyebrow="Merak Edilenler" title="Sık Sorulan Sorular" subtitle="Randevu, sigorta ve tetkik süreçleri hakkında en çok sorulan sorular. Sorunuz yoksa bize yazın." class="!mb-6" />
                    <a href="{{ route('contact.show') }}" class="btn-outline">Soru Sorun <x-icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
                <div class="lg:col-span-3"><x-faq-list :faqs="$faqs" /></div>
            </div>
        </section>
    @endif

    {{-- Blog --}}
    @if($posts->isNotEmpty())
        <section class="section bg-mist">
            <div class="container-x">
                <x-section-heading eyebrow="Sağlık Rehberi" :title="setting('blog_label', 'Blog')" subtitle="Uzman hekimlerimizden sağlıklı yaşam ve tedavi süreçleri hakkında güvenilir içerikler." :actionUrl="route('blog.index')" actionLabel="Tüm Yazılar" />
                <div class="grid gap-6 md:grid-cols-3">@foreach($posts as $post)<x-post-card :post="$post" />@endforeach</div>
            </div>
        </section>
    @endif

    <x-cta-band />
@endsection
