<?php

namespace App\Livewire;

use Livewire\Component;
use App\Http\Requests\EthiopianDateRequest;

class Form extends Component
{
    public function save(EthiopianDateRequest $request)
    {
        // Convert to Gregorian for database storage
        $gregorianDate = $request->getGregorianDate();

        // Save your model
        dd($gregorianDate); // For debugging, remove in production
        // Event::create([
        //     'date' => $gregorianDate,
        //     // other fields
        // ]);

        return redirect()->back()->with('success', 'Event created!');
    }

    public function render()
    {
        return view('livewire.form');
    }
}
