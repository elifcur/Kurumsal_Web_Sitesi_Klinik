@extends('layouts.admin')
@section('title', 'Randevular')
@section('content')
    <x-admin.page-header title="Randevu Talepleri" subtitle="Online randevu formundan gelen talepler. Durumu güncelleyip iç not ekleyebilirsiniz." />

    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        @foreach(\Templates\Clinic\Models\Appointment::STATUSES as $key => $label)
            <a href="{{ route('admin.appointments.index', ['status' => $key]) }}" @class(['card flex items-center justify-between p-4 transition hover:border-primary/50', 'border-primary' => request('status') === $key])>
                <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                <span class="{{ \Templates\Clinic\Models\Appointment::badgeFor($key) }}">{{ $counts[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">
            <x-admin.search placeholder="Hasta adı, telefon, e-posta...">
                <select name="status" class="form-input !w-40"><option value="">Tüm durumlar</option>@foreach(\Templates\Clinic\Models\Appointment::STATUSES as $key => $label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>@endforeach</select>
                <select name="doctor" class="form-input !w-48"><option value="">Tüm doktorlar</option>@foreach($doctors as $id => $name)<option value="{{ $id }}" @selected((string) request('doctor') === (string) $id)>{{ $name }}</option>@endforeach</select>
            </x-admin.search>
        </div>
        @if($appointments->isEmpty())
            <x-admin.empty title="Randevu talebi bulunamadı" icon="calendar" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th>Hasta</th><th>Bölüm</th><th>Doktor</th><th>Tarih / Saat</th><th>Durum</th><th>Talep</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td><a href="{{ route('admin.appointments.show', $appointment) }}" class="font-medium text-slate-900 hover:text-primary">{{ $appointment->name }}</a><span class="block text-xs text-slate-500">{{ $appointment->phone }}@if($appointment->email) · {{ $appointment->email }}@endif</span></td>
                        <td class="text-slate-600">{{ $appointment->service?->title ?? '—' }}</td>
                        <td class="text-slate-600">{{ $appointment->doctor?->name ?? 'Fark etmez' }}</td>
                        <td class="whitespace-nowrap text-slate-900">{{ $appointment->preferred_date->format('d.m.Y') }}<span class="block text-xs text-slate-500">{{ $appointment->timeLabel() ?? 'Saat belirtilmedi' }}</span></td>
                        <td><span class="{{ $appointment->statusBadge() }}">{{ $appointment->statusLabel() }}</span></td>
                        <td class="whitespace-nowrap text-xs text-slate-500">{{ $appointment->created_at->format('d.m.Y H:i') }}</td>
                        <td class="text-right whitespace-nowrap">
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn-ghost btn-sm"><x-icon name="eye" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.appointments.destroy', $appointment)" message="Bu randevu talebi silinecek. Devam edilsin mi?" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $appointments->links() }}</div>
        @endif
    </div>
@endsection
