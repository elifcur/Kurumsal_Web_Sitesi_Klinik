@extends('template::layouts.app')
@section('seo')<x-seo :title="$post->meta_title ?: $post->title" :description="$post->meta_description ?: $post->excerpt" :image="$post->image" type="article" />@endsection
@section('content')
    <x-page-header :title="$post->title" :subtitle="$post->excerpt" :image="$post->image" :breadcrumbs="[['label' => setting('blog_label', 'Blog'), 'url' => route('blog.index')], ['label' => \Illuminate\Support\Str::limit($post->title, 50)]]" />
    <section class="section">
        <div class="container-x max-w-4xl">
            <div class="mb-8 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                @if($post->published_at)<time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d F Y') }}</time>@endif
                @if($post->author)<span>· {{ $post->author->name }}</span>@endif
                @if($post->category)<a href="{{ route('blog.index', ['kategori' => $post->category->slug]) }}" class="pill">{{ $post->category->name }}</a>@endif
            </div>
            @if($post->image)<img src="{{ upload_url($post->image) }}" alt="{{ $post->title }}" class="mb-10 w-full rounded-2xl object-cover">@endif
            <div class="prose-cms">{!! $post->content !!}</div>
            @if($post->tags->isNotEmpty())
                <div class="mt-10 flex flex-wrap gap-2">@foreach($post->tags as $tag)<a href="{{ route('blog.index', ['etiket' => $tag->slug]) }}" class="chip hover:bg-mist hover:text-primary">#{{ $tag->name }}</a>@endforeach</div>
            @endif
            <div class="mt-12 rounded-2xl border border-slate-200 bg-mist p-6 text-sm text-slate-600">
                <strong class="text-ink">Not:</strong> Bu içerik bilgilendirme amaçlıdır; tanı ve tedavi için lütfen hekiminize başvurun. <a href="{{ route('appointments.create') }}" class="font-semibold text-primary hover:underline">Randevu alın →</a>
            </div>
        </div>
    </section>
    @if($related->isNotEmpty())
        <section class="section bg-mist !pt-16"><div class="container-x"><h2 class="section-title mb-8 !text-3xl">İlgili Yazılar</h2><div class="grid gap-6 md:grid-cols-3">@foreach($related as $item)<x-post-card :post="$item" />@endforeach</div></div></section>
    @endif
@endsection
