@extends('template::layouts.app')
@section('seo')<x-seo title="İletişim" :description="'Bize ulaşın: '.setting('phone').' - '.setting('email')" />@endsection
@section('content')
    <x-page-header title="İletişim" subtitle="Sorularınız, tetkik sonuçlarınız ve sigorta işlemleri için bize ulaşın. Randevu için online formu kullanabilirsiniz." :breadcrumbs="[['label' => 'İletişim']]" />
    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-5">
            <div class="space-y-4 lg:col-span-2">
                @if(setting('emergency_phone'))
                    <a href="{{ phone_href(setting('emergency_phone')) }}" class="flex items-center gap-4 rounded-lg border border-rose-200 bg-rose-50 p-5 transition hover:border-rose-400"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-600 text-white"><x-icon name="phone" class="h-5 w-5" /></span><div><p class="text-sm font-semibold text-rose-600">7/24 Acil Hat</p><p class="mt-0.5 text-lg font-bold text-ink">{{ setting('emergency_phone') }}</p></div></a>
                @endif
                @foreach([['map-pin', 'Adres', setting('address'), null], ['phone', 'Telefon', setting('phone'), phone_href(setting('phone'))], ['mail', 'E-posta', setting('email'), 'mailto:'.setting('email')], ['clock', 'Çalışma Saatleri', setting('working_hours') ?: setting('working_hours_note'), null]] as [$icon, $label, $value, $href])
                    @if($value)
                        <div class="flex gap-4 rounded-2xl border border-slate-200 bg-white p-5"><span class="icon-box !h-11 !w-11"><x-icon :name="$icon" class="h-5 w-5" /></span><div><p class="text-sm font-semibold text-ink">{{ $label }}</p>@if($href)<a href="{{ $href }}" class="mt-1 block text-sm text-slate-600 hover:text-primary">{{ $value }}</a>@else<p class="mt-1 text-sm text-slate-600">{{ $value }}</p>@endif</div></div>
                    @endif
                @endforeach
                <div class="rounded-2xl bg-mist p-5">
                    <p class="font-semibold text-ink">Randevu mu almak istiyorsunuz?</p>
                    <p class="mt-1 text-sm text-slate-600">Online randevu formuyla bölüm ve doktor seçerek talebinizi iletebilirsiniz.</p>
                    <a href="{{ route('appointments.create') }}" class="btn-primary mt-4 !py-2.5"><x-icon name="calendar" class="h-4 w-4" /> {{ setting('appointments_label', 'Randevu Al') }}</a>
                </div>
            </div>
            <div class="lg:col-span-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <h2 class="font-display text-2xl text-ink">Bize Yazın</h2>
                    <p class="mt-1 mb-6 text-sm text-slate-500">Formu doldurun, hasta danışmanımız en kısa sürede size dönsün.</p>
                    @if(session('success'))<x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>@endif
                    <x-contact-form />
                </div>
            </div>
        </div>
    </section>
    @if(setting('map_embed'))<section class="h-96 w-full"><iframe src="{{ setting('map_embed') }}" class="h-full w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="Harita"></iframe></section>@endif
@endsection
