@extends('template::layouts.app')
@section('seo')<x-seo :title="setting('appointments_label', 'Randevu Al')" :description="setting('appointment_intro')" :robots="request()->query() ? 'noindex, follow' : 'index, follow'" />@endsection
@section('content')
    @php
        $doctorOptions = $doctors->map(fn ($d) => ['id' => $d->id, 'name' => $d->name, 'title' => $d->title, 'specialty' => $d->attribute('Uzmanlık')])->values();
        $serviceOptions = $services->map(fn ($s) => ['id' => $s->id, 'title' => $s->title])->values();
    @endphp
    <x-page-header :title="setting('appointments_label', 'Randevu Al')" :subtitle="setting('appointment_intro')" :breadcrumbs="[['label' => setting('appointments_label', 'Randevu Al')]]" />
    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-5">
            <div class="lg:col-span-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <h2 class="font-display text-2xl text-ink">Randevu Talep Formu</h2>
                    <p class="mt-1 mb-6 text-sm text-slate-500">Yıldızlı alanlar zorunludur. Talebiniz onaylandığında SMS ve telefonla bilgilendirilirsiniz.</p>
                    @if(session('success'))<x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>@endif
                    <form method="post" action="{{ route('appointments.store') }}" class="space-y-5" x-data="appointmentForm(@js(['doctors' => $doctorOptions, 'services' => $serviceOptions, 'serviceId' => old('service_id', $selectedService?->id), 'doctorId' => old('team_member_id', $selectedDoctor?->id)]))">
                        @csrf
                        <div class="hidden" aria-hidden="true"><label>Web sitesi <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div><label for="name" class="form-label">Ad Soyad *</label><input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="120" class="form-input" autocomplete="name">@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
                            <div><label for="phone" class="form-label">Telefon *</label><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required maxlength="40" class="form-input" autocomplete="tel">@error('phone')<p class="form-error">{{ $message }}</p>@enderror</div>
                            <div class="sm:col-span-2"><label for="email" class="form-label">E-posta</label><input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="190" class="form-input" autocomplete="email">@error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
                            <div>
                                <label for="service_id" class="form-label">Bölüm / Tedavi</label>
                                <select id="service_id" name="service_id" class="form-input" x-model="serviceId" @change="onServiceChange()">
                                    <option value="">Bölüm seçin</option>
                                    @foreach($services as $service)<option value="{{ $service->id }}">{{ $service->title }}</option>@endforeach
                                </select>
                                @error('service_id')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="team_member_id" class="form-label">Doktor</label>
                                <select id="team_member_id" name="team_member_id" class="form-input" x-model="doctorId">
                                    <option value="">Fark etmez</option>
                                    @foreach($doctors as $doctor)<option value="{{ $doctor->id }}" :disabled="!matches({{ $doctor->id }})" :hidden="!matches({{ $doctor->id }})" @selected((string) old('team_member_id', $selectedDoctor?->id) === (string) $doctor->id)>{{ $doctor->name }}@if($doctor->attribute('Uzmanlık')) · {{ $doctor->attribute('Uzmanlık') }}@endif</option>@endforeach
                                </select>
                                @error('team_member_id')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div><label for="preferred_date" class="form-label">Tercih Edilen Tarih *</label><input id="preferred_date" name="preferred_date" type="date" value="{{ old('preferred_date') }}" min="{{ now()->toDateString() }}" required class="form-input">@error('preferred_date')<p class="form-error">{{ $message }}</p>@enderror</div>
                            <div>
                                <label for="preferred_time" class="form-label">Saat Aralığı</label>
                                <select id="preferred_time" name="preferred_time" class="form-input">
                                    <option value="">Fark etmez</option>
                                    @foreach(\Templates\Clinic\Models\Appointment::TIMES as $key => $label)<option value="{{ $key }}" @selected(old('preferred_time') === $key)>{{ $label }}</option>@endforeach
                                </select>
                                @error('preferred_time')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div><label for="message" class="form-label">Şikayetiniz / Notunuz</label><textarea id="message" name="message" rows="4" maxlength="2000" class="form-input" placeholder="Kısaca şikayetinizi yazabilirsiniz.">{{ old('message') }}</textarea>@error('message')<p class="form-error">{{ $message }}</p>@enderror</div>
                        <div>
                            <label class="flex items-start gap-3 text-sm text-slate-600">
                                <input type="checkbox" name="kvkk" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" @checked(old('kvkk'))>
                                <span><a href="{{ route('page.show', 'kvkk') }}" target="_blank" class="font-medium text-primary underline">KVKK Aydınlatma Metni</a>'ni okudum ve sağlık verilerim dahil kişisel verilerimin randevu amacıyla işlenmesini kabul ediyorum.</span>
                            </label>
                            @error('kvkk')<p class="form-error">{{ $message }}</p>@enderror
                            @error('website')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn-primary w-full !py-3.5 sm:w-auto sm:!px-8">Randevu Talebi Gönder <x-icon name="arrow-right" class="h-4 w-4" /></button>
                    </form>
                </div>
            </div>
            <aside class="space-y-5 lg:col-span-2">
                @if($selectedDoctor)
                    <div class="card flex items-center gap-4 p-5">
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-full bg-mist text-primary">@if($selectedDoctor->image)<img src="{{ upload_url($selectedDoctor->image) }}" alt="" class="h-full w-full object-cover">@else<x-icon name="user" class="h-7 w-7" />@endif</span>
                        <div><p class="text-xs font-semibold tracking-wide text-primary uppercase">Seçili doktor</p><p class="font-semibold text-ink">{{ $selectedDoctor->name }}</p><p class="text-sm text-slate-500">{{ $selectedDoctor->title }}</p></div>
                    </div>
                @endif
                @if($selectedService)
                    <div class="card flex items-center gap-4 p-5">
                        <span class="icon-box !h-14 !w-14"><x-icon name="plus" class="h-7 w-7" /></span>
                        <div><p class="text-xs font-semibold tracking-wide text-primary uppercase">Seçili bölüm</p><p class="font-semibold text-ink">{{ $selectedService->title }}</p></div>
                    </div>
                @endif
                <div class="rounded-2xl border border-slate-200 bg-mist p-6">
                    <h3 class="font-semibold text-ink">Nasıl işler?</h3>
                    <ol class="mt-4 space-y-4 text-sm text-slate-600">
                        @foreach(['Formu doldurup gönderin.', 'Hasta danışmanımız sizi arayarak uygun saati birlikte belirler.', 'Randevunuz SMS ile teyit edilir; kimlik ve varsa sigorta kartınızla gelmeniz yeterli.'] as $i => $step)
                            <li class="flex gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary text-xs font-bold text-white">{{ $i + 1 }}</span><span class="pt-1">{{ $step }}</span></li>
                        @endforeach
                    </ol>
                </div>
                @if(setting('emergency_phone'))
                    <a href="{{ phone_href(setting('emergency_phone')) }}" class="flex items-center gap-4 rounded-lg border border-rose-200 bg-rose-50 p-5 transition hover:border-rose-400"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-600 text-white"><x-icon name="phone" class="h-5 w-5" /></span><div><p class="text-sm font-semibold text-rose-600">Acil durum mu?</p><p class="mt-0.5 font-bold text-ink">7/24 Acil Hat: {{ setting('emergency_phone') }}</p></div></a>
                @endif
                @if(setting('working_hours_note'))<p class="flex items-center gap-2 px-1 text-sm text-slate-500"><x-icon name="clock" class="h-4 w-4 text-primary" /> {{ setting('working_hours_note') }}</p>@endif
            </aside>
        </div>
    </section>
@endsection
