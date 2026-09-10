@extends('layouts.admin')
@section('title', 'Randevu #'.$appointment->id)
@section('content')
    <x-admin.page-header :title="'Randevu #'.$appointment->id.' · '.$appointment->name" :subtitle="'Talep tarihi: '.$appointment->created_at->format('d.m.Y H:i')">
        <x-slot:actions>
            <a href="{{ route('admin.appointments.index') }}" class="btn-ghost"><x-icon name="arrow-left" class="h-4 w-4" /> Listeye Dön</a>
            <x-admin.delete-button :action="route('admin.appointments.destroy', $appointment)" label="Sil" class="btn-danger" message="Bu randevu talebi silinecek. Devam edilsin mi?" />
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="card">
                <div class="card-header"><h2 class="font-semibold text-slate-900">Randevu Bilgileri</h2><span class="{{ $appointment->statusBadge() }}">{{ $appointment->statusLabel() }}</span></div>
                <dl class="grid gap-x-6 gap-y-4 p-5 text-sm sm:grid-cols-2">
                    <div><dt class="text-xs text-slate-500">Bölüm / Tedavi</dt><dd class="font-medium text-slate-900">{{ $appointment->service?->title ?? '—' }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Doktor</dt><dd class="font-medium text-slate-900">{{ $appointment->doctor?->name ?? 'Fark etmez' }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Tercih Edilen Tarih</dt><dd class="font-medium text-slate-900">{{ $appointment->preferred_date->translatedFormat('d F Y, l') }}</dd></div>
                    <div><dt class="text-xs text-slate-500">Saat Aralığı</dt><dd class="font-medium text-slate-900">{{ $appointment->timeLabel() ?? 'Belirtilmedi' }}</dd></div>
                </dl>
                <div class="border-t border-slate-100 p-5">
                    <p class="text-xs text-slate-500">Hasta notu</p>
                    <p class="mt-1 whitespace-pre-line text-slate-800">{{ $appointment->message ?: '—' }}</p>
                </div>
            </div>

            <form method="post" action="{{ route('admin.appointments.update', $appointment) }}" class="card">
                @csrf
                @method('PUT')
                <div class="card-header"><h2 class="font-semibold text-slate-900">Durum ve İç Not</h2></div>
                <div class="card-body space-y-4">
                    <x-admin.select name="status" label="Durum" :options="\Templates\Clinic\Models\Appointment::STATUSES" :value="$appointment->status" required />
                    <x-admin.textarea name="notes" label="İç not" :value="$appointment->notes" rows="4" help="Sadece yönetim panelinde görünür (arama sonucu, kesinleşen saat vb.)." />
                </div>
                <div class="flex justify-end border-t border-slate-100 px-5 py-4"><button type="submit" class="btn-primary"><x-icon name="check" class="h-4 w-4" /> Kaydet</button></div>
            </form>
        </div>

        <div class="card self-start">
            <div class="card-header"><h2 class="font-semibold text-slate-900">Hasta</h2></div>
            <dl class="divide-y divide-slate-100 text-sm">
                <div class="px-5 py-3"><dt class="text-xs text-slate-500">Ad Soyad</dt><dd class="font-medium text-slate-900">{{ $appointment->name }}</dd></div>
                <div class="px-5 py-3"><dt class="text-xs text-slate-500">Telefon</dt><dd><a href="{{ phone_href($appointment->phone) }}" class="font-medium text-primary hover:underline">{{ $appointment->phone }}</a></dd></div>
                <div class="px-5 py-3"><dt class="text-xs text-slate-500">E-posta</dt><dd>@if($appointment->email)<a href="mailto:{{ $appointment->email }}" class="font-medium text-primary hover:underline">{{ $appointment->email }}</a>@else<span class="text-slate-500">—</span>@endif</dd></div>
                <div class="px-5 py-3"><dt class="text-xs text-slate-500">IP</dt><dd class="text-slate-700">{{ $appointment->ip ?? '—' }}</dd></div>
                <div class="px-5 py-3"><dt class="text-xs text-slate-500">Son güncelleme</dt><dd class="text-slate-700">{{ $appointment->updated_at->format('d.m.Y H:i') }}</dd></div>
            </dl>
            <div class="space-y-2 border-t border-slate-100 p-5">
                <a href="{{ phone_href($appointment->phone) }}" class="btn-primary w-full"><x-icon name="phone" class="h-4 w-4" /> Hastayı Ara</a>
                @if($appointment->email)<a href="mailto:{{ $appointment->email }}?subject={{ rawurlencode('Randevu talebiniz hakkında') }}" class="btn-secondary w-full"><x-icon name="mail" class="h-4 w-4" /> E-posta Gönder</a>@endif
            </div>
        </div>
    </div>
@endsection
