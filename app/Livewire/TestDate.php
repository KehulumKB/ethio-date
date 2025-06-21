<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\EthiopianDateConverter;

class TestDate extends Component
{
    public $currentDate;
    public $gcDate = [];
    public $ethiopicDate = [];
    public $conversionError = null;

    protected EthiopianDateConverter $converter;

    public function mount()
    {
        $this->converter = new EthiopianDateConverter();

        try {
            $this->currentDate = '2020-07-31';
            $this->ethiopicDate = $this->converter->gregorianToEthiopian($this->currentDate);
            $this->gcDate = $this->converter->ethiopianToGregorian(2016, 10, 14);
        } catch (\RuntimeException $e) {
            dd($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.test-date', [
            'formattedDate' => $this->converter->formatEthiopianDate($this->ethiopicDate),
            'formattedGregorianDate' => $this->converter->formatGregorianDate($this->gcDate),
            'isValid' => empty($this->conversionError)
        ]);
    }
}
