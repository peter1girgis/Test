<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded-xl space-y-6 my-4 dark:bg-slate-500">
    <h2 class="text-2xl font-semibold mb-4">Payment Requirements</h2>

    <form wire:submit.prevent="submit" class="space-y-4">
        @foreach($requirements as $req)
            <div class="w-full {{ $req['width'] === 'half' ? 'md:w-1/2' : '' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $req['label'] }}
                    @if($req['is_required'])
                        <span class="text-red-500">*</span>
                    @endif
                </label>

                @if($req['type'] === 'text')
                    <input type="text" wire:model="inputs.{{ $req['id'] }}"
                        class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200 dark:focus:ring-blue-950">
                @elseif($req['type'] === 'textarea')
                    <textarea wire:model="inputs.{{ $req['id'] }}"
                        class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200 dark:focus:ring-blue-950"></textarea>
                @elseif($req['type'] === 'phone')
                    <input type="tel" wire:model="inputs.{{ $req['id'] }}"
                        class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200 dark:focus:ring-blue-950">
                @elseif($req['type'] === 'file')
                    <input type="file" wire:model="inputs.{{ $req['id'] }}"
                        class="w-full border rounded-lg p-2 ">
                @elseif($req['type'] === 'date')
                    <input type="date" wire:model="inputs.{{ $req['id'] }}"
                        class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200 dark:focus:ring-blue-950">
                @elseif($req['type'] === 'number')
                    <input type="number" wire:model="inputs.{{ $req['id'] }}"
                        class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200 dark:focus:ring-blue-950">
                @endif

                @if($req['description'])
                    <p class="text-sm text-gray-500 mt-1">{{ $req['description'] }}</p>
                @endif

                @error('inputs.' . $req['id'])
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        @endforeach

        <button type="submit"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" >
            <span wire:loading.remove>Place data</span>
            <span wire:loading>Processing...</span>
        </button>
    </form>
</div>
