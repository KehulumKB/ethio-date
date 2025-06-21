<?php

namespace App\Livewire;

use App\Services\EthiopianDateConverter;
use Livewire\Component;

class EthiopianDatePicker extends Component
{
    public $gregorianDate;
    public $ethiopianYear;
    public $ethiopianMonth;
    public $ethiopianDay;
    public $convertedDate = [];

    public function mount()
    {
        $today = now()->format('Y-m-d');
        $this->gregorianDate = $today;
        $this->convertToEthiopian();
    }

    public function convertToEthiopian()
    {
        $converter = new EthiopianDateConverter();
        $this->convertedDate = $converter->gregorianToEthiopian($this->gregorianDate);

        $this->ethiopianYear = $this->convertedDate['year'];
        $this->ethiopianMonth = $this->convertedDate['month'];
        $this->ethiopianDay = $this->convertedDate['day'];
    }

    public function convertToGregorian()
    {
        $converter = new EthiopianDateConverter();
        $greg = $converter->ethiopianToGregorian((int) $this->ethiopianYear, (int) $this->ethiopianMonth, (int) $this->ethiopianDay);

        $this->gregorianDate = $greg['year'] . '-' . str_pad($greg['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($greg['day'], 2, '0', STR_PAD_LEFT);
        $this->convertToEthiopian(); // Refresh displayed info
    }

    public function render()
    {
        return view('livewire.ethiopian-date-picker');
    }
}
