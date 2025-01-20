<?php

namespace App\Livewire;

use App\Models\CurrentFinancialYear;
use Livewire\Component;

class ChangeFinancialYear extends Component
{
    public $financial_year;

    public function render()
    {
        return view('livewire.change-financial-year');
    }

    public function mount()
    {
        $this->financial_year = CurrentFinancialYear::first()->name ?? '';
    }

    public function save()
    {
        $this->validate(
            [
                'financial_year' => 'required|regex:/^\d{2}\/\d{2}$/', // The financial year must be in the format YY/YY
            ],
            [
                'financial_year.regex' => 'The financial year must be in the format YY/YY', //Validation message
            ]
        );

        $currentFinancialYear = CurrentFinancialYear::first();
        $currentFinancialYear->update(['name' => $this->financial_year]);

        $this->resetValidation();
        $this->dispatch('close-fy-modal');
        $this->dispatch('show-message', message: 'Financial year changed successfully');
    }

    public function increment()
    {
        [$startyear, $endyear] = explode('/', $this->financial_year);

        $startyear = (int)$startyear + 1;
        $endyear = (int)$endyear + 1;

        $this->financial_year = $startyear . '/' . $endyear;
    }

    public function decrement()
    {
        [$startyear, $endyear] = explode('/', $this->financial_year);

        $startyear = (int)$startyear - 1;
        $endyear = (int)$endyear - 1;

        $this->financial_year = $startyear . '/' . $endyear;
    }
}
