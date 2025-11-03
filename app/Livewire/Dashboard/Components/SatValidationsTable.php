<?php

namespace App\Livewire\Dashboard\Components;

use App\Models\SatValidation;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
class SatValidationsTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.dashboard.components.sat-validations-table', [
            'satValidations' => SatValidation::query()
                ->orderBy('created_at', 'desc')
                ->paginate(32),
        ]);
    }
}
