<div>
    <button
        wire:click="switchLanguage"
        class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300"
    >
        {{ $locale === 'ar' ? 'English' : 'العربية' }}
    </button>
</div>
