<?php

namespace App\Livewire;

use App\Services\EthiopianDateConverter;
use Livewire\Component;

class TestDate extends Component
{
    public $currentDate;
    public $ethiopicDate = [];
    public $conversionError = null;

    public function mount()
    {
        $converter = new \App\Services\EthiopianDateConverter();

        try {
            // First verify the conversion works
            // $testResult = $converter->verifyConversion();

            // Now convert current date
            $this->currentDate = '2025-07-1';
            $this->ethiopicDate = $converter->gregorianToEthiopian($this->currentDate);

            // Should output: Sene 14, 2017 (Kidame)
            // dump($this->ethiopicDate);
        } catch (\RuntimeException $e) {
            // Handle conversion error
            dd($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.test-date', [
            'formattedDate' => $this->formatEthiopicDate(),
            'isValid' => empty($this->conversionError)
        ]);
    }

    protected function formatEthiopicDate()
    {
        if (empty($this->ethiopicDate)) return '';

        return sprintf(
            '%s %d, %d (%s)',
            $this->ethiopicDate['monthName'],
            $this->ethiopicDate['day'],
            $this->ethiopicDate['year'],
            $this->ethiopicDate['dayName']
        );
    }
}
