@extends('template::layouts.app')
@section('seo')<x-seo :title="setting('team_home_title', 'Doktorlarımız')" :description="setting('team_home_intro', 'Uzman hekim kadromuzla tanışın.')" />@endsection
@section('content')
    <x-page-header :title="setting('team_home_title', 'Doktorlarımız')" :subtitle="setting('team_home_intro', 'Alanında uzman, deneyimli ve güler yüzlü hekim kadromuzla tanışın.')" :breadcrumbs="[['label' => setting('team_home_title', 'Doktorlarımız')]]" />
    <section class="section">
        <div class="container-x">
            @if($members->isEmpty())
                <x-empty-state icon="users" title="Henüz doktor eklenmedi" />
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">@foreach($members as $member)<x-team-card :member="$member" />@endforeach</div>
            @endif
        </div>
    </section>
    <x-cta-band />
@endsection
