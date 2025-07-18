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
        $converter = new EthiopianDateConverter();

        try {
            // Now convert current date
            $this->currentDate = '2025-07-22';
            $this->ethiopicDate = $converter->gregorianToEthiopian($this->currentDate);

            $gregorianDate = $converter->ethiopianToGregorian(2016, 1, 1);
            dd( $gregorianDate->format('Y-m-d'));

        } catch (\RuntimeException $e) {
            dd($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.test-date', [
            'formattedDate' => $this->formatEthiopicDate(),
            'isValid' => empty($this->conversionError),
        ]);
    }

    protected function formatEthiopicDate()
    {
        if (empty($this->ethiopicDate)) {
            return '';
        }

        return sprintf('%s %d, %d (%s)', $this->ethiopicDate['monthName'], $this->ethiopicDate['day'], $this->ethiopicDate['year'], $this->ethiopicDate['dayName']);
    }
}
