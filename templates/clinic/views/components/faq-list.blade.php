@props(['faqs'])
<div x-data="accordion()" class="space-y-3">
    @foreach($faqs as $faq)
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm" :class="isOpen({{ $faq->id }}) && 'border-primary/40'">
            <button type="button" @click="toggle({{ $faq->id }})" class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left font-semibold text-ink" :aria-expanded="isOpen({{ $faq->id }})">
                <span>{{ $faq->question }}</span>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-mist text-primary transition" ::class="isOpen({{ $faq->id }}) && 'rotate-180'"><x-icon name="chevron-down" class="h-4 w-4" /></span>
            </button>
            <div x-show="isOpen({{ $faq->id }})" x-collapse x-cloak class="px-6 pb-5 text-sm leading-7 text-slate-600">{{ $faq->answer }}</div>
        </div>
    @endforeach
</div>
