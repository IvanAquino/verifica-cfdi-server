<?php

namespace App\Livewire\Dashboard\Components;

use App\Models\SatValidation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class DashboardStats extends Component
{
    #[Computed]
    public function generalCount()
    {
        return SatValidation::count();
    }

    #[Computed(cache: true, seconds: 60)]
    public function thisMonthCount()
    {
        return SatValidation::where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<=', now()->endOfMonth())
            ->count();
    }

    #[Computed(cache: true, seconds: 60)]
    public function thisYearCount()
    {
        return SatValidation::where('created_at', '>=', now()->startOfYear())
            ->where('created_at', '<=', now()->endOfYear())
            ->count();
    }

    #[Computed(cache: true, seconds: 60)]
    public function thisWeekCount()
    {
        return SatValidation::where('created_at', '>=', now()->startOfWeek())
            ->where('created_at', '<=', now()->endOfWeek())
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboard.components.dashboard-stats');
    }
}
