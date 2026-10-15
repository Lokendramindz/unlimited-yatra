<div x-data="{ isOpen: false, selectedYatraId: '', selectedYatraTitle: '' }"
     @open-yatra-enquiry.window="selectedYatraId = String($event.detail.id); selectedYatraTitle = $event.detail.title; isOpen = true"
     x-show="isOpen" x-cloak style="display:none"
     @click.self="isOpen = false" @keydown.escape.window="isOpen = false"
     class="fixed inset-0 z-[70] flex items-center justify-center overflow-y-auto bg-slate-950/60 p-4">
    <div role="dialog" aria-modal="true" aria-labelledby="yatra-enquiry-title" class="my-auto w-full max-w-2xl rounded-lg bg-white p-5 shadow-2xl sm:p-7">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <h2 id="yatra-enquiry-title" class="font-display text-2xl text-maroon-800">Enquire about this yatra</h2>
                <p class="mt-1 text-sm text-gray-600" x-text="selectedYatraTitle"></p>
            </div>
            <button type="button" @click="isOpen = false" aria-label="Close enquiry form" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded border border-gray-200 text-xl text-gray-600 hover:bg-gray-50">&times;</button>
        </div>
        @include('partials.enquiry-form', ['fixedYatra' => true])
    </div>
</div>