<div class="p-6 space-y-4 bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-lg mx-auto">
    <flux:heading size="xl">Ethiopian Date Picker</flux:heading>

    <div class="space-y-2">
        <flux:label for="gregorianDate">Gregorian Date</flux:label>
        <flux:input id="gregorianDate" type="date" wire:model.live="gregorianDate" wire:change="convertToEthiopian" />
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <flux:label for="ethiopianDay">Day</flux:label>
            <flux:input id="ethiopianDay" type="number" min="1" max="30" wire:model.live="ethiopianDay" />
        </div>
        <div>
            <flux:label for="ethiopianMonth">Month</flux:label>
            <flux:select id="ethiopianMonth" wire:model.live="ethiopianMonth">
                @foreach (range(1, 13) as $i)
                <flux:select.option value="{{ $i }}">{{ $i }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
        <div>
            <flux:label for="ethiopianYear">Year</flux:label>
            <flux:input id="ethiopianYear" type="number" wire:model.live="ethiopianYear" />
        </div>
    </div>

    <flux:button variant="primary" wire:click="convertToGregorian">Convert to Gregorian</flux:button>

    @if ($convertedDate)
    <div class="p-4 mt-4 border rounded-lg bg-gray-50 dark:bg-gray-900">
        <p><strong>Ethiopian Date:</strong> {{ $convertedDate['dayName'] }}, {{ $convertedDate['monthName'] }} {{
            $convertedDate['day'] }}, {{ $convertedDate['year'] }}</p>
        <p><strong>Gregorian:</strong> {{ $gregorianDate }}</p>
    </div>
    @endif
</div>