@extends('template::layouts.app')
@section('seo')<x-seo :title="$member->name" :description="$member->excerpt ?: $member->title" :image="$member->image" type="profile" />@endsection
@section('content')
    <x-page-header :title="$member->name" :subtitle="$member->title" :breadcrumbs="[['label' => setting('team_home_title', 'Doktorlarımız'), 'url' => route('team.index')], ['label' => $member->name]]" />
    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-3">
            <aside>
                <div class="sticky top-24 overflow-hidden rounded-2xl border border-slate-200 shadow-sm">
                    <div class="aspect-[4/4.4] bg-gradient-to-b from-mist to-mist-dark">@if($member->image)<img src="{{ upload_url($member->image) }}" alt="{{ $member->name }}" class="h-full w-full object-cover">@else<div class="flex h-full items-center justify-center text-primary/30"><x-icon name="user" class="h-24 w-24" /></div>@endif</div>
                    <div class="space-y-3 p-5 text-sm">
                        <dl class="space-y-3">
                        @foreach($member->attributes_list() as $attribute)<div class="flex justify-between gap-4 border-b border-slate-100 pb-3"><dt class="text-slate-500">{{ $attribute['label'] }}</dt><dd class="text-right font-medium text-ink">{{ $attribute['value'] }}</dd></div>@endforeach
                        </dl>
                        @if($member->phone)<a href="{{ phone_href($member->phone) }}" class="flex items-center gap-2 text-slate-700 hover:text-primary"><x-icon name="phone" class="h-4 w-4 text-primary" /> {{ $member->phone }}</a>@endif
                        @if($member->email)<a href="mailto:{{ $member->email }}" class="flex items-center gap-2 text-slate-700 hover:text-primary"><x-icon name="mail" class="h-4 w-4 text-primary" /> {{ $member->email }}</a>@endif
                        <div class="flex gap-2 pt-1">@foreach(['linkedin', 'instagram', 'twitter'] as $network)@if($member->social($network))<a href="{{ $member->social($network) }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-primary hover:text-white" aria-label="{{ $network }}"><x-icon :name="$network" class="h-4 w-4" /></a>@endif @endforeach</div>
                        <a href="{{ route('appointments.create', ['doktor' => $member->slug]) }}" class="btn-primary mt-2 w-full"><x-icon name="calendar" class="h-4 w-4" /> Randevu Al</a>
                    </div>
                </div>
            </aside>
            <div class="lg:col-span-2">
                @if($member->attribute('Uzmanlık'))<span class="pill mb-4">{{ $member->attribute('Uzmanlık') }}</span>@endif
                @if($member->excerpt)<p class="text-lg leading-8 text-slate-700">{{ $member->excerpt }}</p>@endif
                <div class="prose-cms mt-6">{!! $member->bio !!}</div>
                <div class="mt-10 flex flex-col items-start gap-4 rounded-2xl border border-primary/20 bg-mist p-6 sm:flex-row sm:items-center sm:justify-between">
                    <div><p class="font-semibold text-ink">{{ $member->name }} ile görüşmek ister misiniz?</p><p class="text-sm text-slate-600">Doktor seçili olarak randevu formuna gidin.</p></div>
                    <a href="{{ route('appointments.create', ['doktor' => $member->slug]) }}" class="btn-primary shrink-0">Randevu Al <x-icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
            </div>
        </div>
    </section>
    @if($others->isNotEmpty())
        <section class="section bg-mist !pt-16"><div class="container-x"><h2 class="section-title mb-8 !text-3xl">Diğer Doktorlarımız</h2><div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">@foreach($others as $other)<x-team-card :member="$other" />@endforeach</div></div></section>
    @endif
@endsection
