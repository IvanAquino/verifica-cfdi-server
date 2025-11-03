<x-layouts.app :title="__('Dashboard')">
    <div class="space-y-4">
        @livewire('dashboard.components.dashboard-stats', key('dashboard-stats'))

        @livewire('dashboard.components.sat-validations-table', key('sat-validations-table'))
    </div>
</x-layouts.app>
