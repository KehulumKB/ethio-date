<!-- In your form view -->
<form wire:submit="save">
    <div class="space-y-4">
        <div>
            <livewire:ethiopian-date-picker wire:model="ethiopian_date" label="Date" />
            {{--
            <x-flux-input-error for="ethiopian_date" class="mt-2" /> --}}
        </div>

        <flux:button type="submit">Save</flux:button>
    </div>
</form>