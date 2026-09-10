@props(['member'])
<a href="{{ $member->url() }}" class="card group overflow-hidden">
    <div class="aspect-[4/4.4] overflow-hidden bg-gradient-to-b from-mist to-mist-dark">
        @if($member->image)<img src="{{ upload_url($member->image) }}" alt="{{ $member->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">@else<div class="flex h-full items-center justify-center text-primary/30"><x-icon name="user" class="h-20 w-20" /></div>@endif
    </div>
    <div class="p-5">
        @if($member->attribute('Uzmanlık'))<span class="pill mb-2">{{ $member->attribute('Uzmanlık') }}</span>@endif
        <h3 class="font-semibold text-ink group-hover:text-primary">{{ $member->name }}</h3>
        @if($member->title)<p class="mt-1 text-sm text-slate-500">{{ $member->title }}</p>@endif
        <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-primary"><x-icon name="calendar" class="h-4 w-4" /> Randevu Al <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" /></span>
    </div>
</a>
