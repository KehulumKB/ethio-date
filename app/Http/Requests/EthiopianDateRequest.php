<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\EthiopianDateConverter;

class EthiopianDateRequest extends FormRequest
{
    protected $converter;

    public function __construct(EthiopianDateConverter $converter)
    {
        $this->converter = $converter;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ethiopian_date' => [
                'required',
                'string',
                'regex:/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/',
                function ($attribute, $value, $fail) {
                    // Validate Ethiopian date components
                    if (!preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches)) {
                        $fail('Invalid Ethiopian date format. Use DD/MM/YYYY');
                        return;
                    }

                    $day = (int)$matches[1];
                    $month = (int)$matches[2];
                    $year = (int)$matches[3];

                    // Validate month range
                    if ($month < 1 || $month > 13) {
                        $fail('Ethiopian month must be between 1-13');
                        return;
                    }

                    // Validate day range
                    $maxDays = ($month === 13) ?
                        ($this->isEthiopianLeapYear($year) ? 6 : 5) :
                        30;

                    if ($day < 1 || $day > $maxDays) {
                        $fail("Day must be between 1-$maxDays for month $month");
                    }
                }
            ],
        ];
    }

    private function isEthiopianLeapYear(int $year): bool
    {
        return $year % 4 === 3;
    }

    public function getGregorianDate()
    {
        $ethiopianDate = $this->input('ethiopian_date');
        preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $ethiopianDate, $matches);

        $day = (int)$matches[1];
        $month = (int)$matches[2];
        $year = (int)$matches[3];

        return $this->converter->ethiopianToGregorian($year, $month, $day);
    }

    public function messages()
    {
        return [
            'ethiopian_date.required' => 'Ethiopian date is required',
            'ethiopian_date.regex' => 'Invalid format. Use DD/MM/YYYY',
        ];
    }
}
