@props(['service'])
{{-- Fotoğraflı bölüm kartı: fotoğrafın sol altına binen beyaz yuvarlak ikon rozeti --}}
<a href="{{ $service->url() }}" class="card group flex flex-col overflow-hidden">
    <div class="relative aspect-[2/1] overflow-hidden bg-gradient-to-br from-mist to-mist-dark">
        @if($service->image)
            <img src="{{ upload_url($service->image) }}" alt="{{ $service->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
        @else
            <div class="flex h-full w-full items-center justify-center text-primary/25"><x-icon :name="$service->icon ?: 'plus'" class="h-14 w-14" /></div>
        @endif
        <span class="icon-badge absolute -bottom-6 left-5"><x-icon :name="$service->icon ?: 'plus'" class="h-5 w-5" /></span>
    </div>
    <div class="flex flex-1 flex-col px-5 pt-9 pb-5">
        <h3 class="font-semibold text-ink group-hover:text-primary">{{ $service->title }}</h3>
        @if($service->excerpt)<p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">{{ $service->excerpt }}</p>@endif
        <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-primary">Detaylı Bilgi <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" /></span>
    </div>
</a>
