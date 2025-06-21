<div class="p-4 border rounded-lg">
    @if($isValid)
    <div class="space-y-2">
        <div>
            <h3 class="font-bold">Gregorian Date:</h3>
            <p>{{ $currentDate }}</p>
        </div>
        <div>
            <h3 class="font-bold">Ethiopian Date:</h3>
            <p class="text-lg">{{ $formattedDate }}</p>
        </div>
    </div>
    @else
    <div class="text-red-600 p-4 bg-red-50 rounded-lg">
        <p class="font-bold">Conversion Error:</p>
        <p>{{ $conversionError }}</p>
    </div>
    @endif
</div>
