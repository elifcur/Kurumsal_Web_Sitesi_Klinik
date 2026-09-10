@extends('template::layouts.app')
@section('seo')<x-seo :title="$service->meta_title ?: $service->title" :description="$service->meta_description ?: $service->excerpt" :image="$service->image" />@endsection
@section('content')
    <x-page-header :title="$service->title" :subtitle="$service->excerpt" :image="$service->image" :breadcrumbs="[['label' => setting('services_label', 'Tedaviler'), 'url' => route('services.index')], ['label' => $service->title]]" />
    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if($service->image)<img src="{{ upload_url($service->image) }}" alt="{{ $service->title }}" class="mb-8 w-full rounded-2xl object-cover">@endif
                <div class="prose-cms">{!! $service->content !!}</div>
                <div class="mt-10 flex flex-col items-start gap-4 rounded-2xl border border-primary/20 bg-mist p-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4"><span class="icon-box bg-white"><x-icon name="calendar" class="h-6 w-6" /></span><div><p class="font-semibold text-ink">{{ $service->title }} için randevu</p><p class="text-sm text-slate-600">Bölümü seçili olarak randevu formuna gidin.</p></div></div>
                    <a href="{{ route('appointments.create', ['tedavi' => $service->slug]) }}" class="btn-primary shrink-0">Bu tedavi için randevu al <x-icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
            </div>
            <aside class="space-y-6">
                @if($others->isNotEmpty())
                    <div class="rounded-2xl border border-slate-200 p-6">
                        <h3 class="font-semibold text-ink">Diğer {{ setting('services_label', 'Tedaviler') }}</h3>
                        <ul class="mt-4 divide-y divide-slate-100">@foreach($others as $other)<li><a href="{{ $other->url() }}" class="flex items-center gap-3 py-3 text-sm text-slate-700 hover:text-primary"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-mist text-primary"><x-icon :name="$other->icon ?: 'plus'" class="h-4 w-4" /></span> {{ $other->title }}</a></li>@endforeach</ul>
                    </div>
                @endif
                <div class="rounded-2xl border border-slate-200 bg-mist p-6">
                    <h3 class="font-display text-xl text-ink">Sorularınız mı var?</h3>
                    <p class="mt-2 text-sm text-slate-600">Hasta danışmanlarımız tedavi süreci ve ücretler hakkında bilgi vermek için hazır.</p>
                    <a href="{{ route('contact.show', ['konu' => 'Bilgi almak istiyorum']) }}" class="btn-primary mt-5 w-full">İletişime Geçin</a>
                    @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}" class="mt-3 flex items-center justify-center gap-2 text-sm font-semibold text-ink"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>@endif
                </div>
            </aside>
        </div>
    </section>
@endsection
