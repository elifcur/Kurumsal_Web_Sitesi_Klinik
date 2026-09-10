@props(['title', 'subtitle' => null, 'image' => null, 'breadcrumbs' => []])
@push('head')<script type="application/ld+json">{!! breadcrumb_schema($breadcrumbs) !!}</script>@endpush
@php($background = $image ?: setting('hero_image'))
<section class="relative overflow-hidden bg-ink text-white">
    @if($background)<img src="{{ upload_url($background) }}" alt="" class="absolute inset-0 h-full w-full object-cover object-right">@endif
    <div class="absolute inset-0 bg-gradient-to-r from-ink/95 via-ink/80 to-ink/50"></div>
    <div class="container-x relative py-16">
        <nav class="mb-5 flex flex-wrap items-center gap-2 text-xs text-white/70" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white">Ana Sayfa</a>
            @foreach($breadcrumbs as $crumb)
                <x-icon name="chevron-right" class="h-3 w-3 text-white/50" />
                @if(isset($crumb['url']))<a href="{{ $crumb['url'] }}" class="hover:text-white">{{ $crumb['label'] }}</a>@else<span class="text-white">{{ $crumb['label'] }}</span>@endif
            @endforeach
        </nav>
        <h1 class="max-w-3xl font-display text-4xl tracking-tight text-white md:text-5xl">{!! emphasize($title) !!}</h1>
        @if($subtitle)<p class="mt-4 max-w-2xl text-lg text-white/80">{{ $subtitle }}</p>@endif
    </div>
</section>
