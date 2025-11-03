<div>
    <div class="grid gap-4 grid-cols-1 md:grid-cols-4 border border-neutral-200 dark:border-neutral-700 rounded-lg p-4">
        <div class="flex flex-col items-center justify-center">
            <span class="text-sm text-neutral-500">{{ __('General') }}</span>
            <span class="text-2xl font-bold">{{ $this->generalCount }}</span>
        </div>
        <div class="flex flex-col items-center justify-center">
            <span class="text-sm text-neutral-500">{{ __('This Month') }}</span>
            <span class="text-2xl font-bold">{{ $this->thisMonthCount }}</span>
        </div>
        <div class="flex flex-col items-center justify-center">
            <span class="text-sm text-neutral-500">{{ __('This Year') }}</span>
            <span class="text-2xl font-bold">{{ $this->thisYearCount }}</span>
        </div>
        <div class="flex flex-col items-center justify-center">
            <span class="text-sm text-neutral-500">{{ __('This Week') }}</span>
            <span class="text-2xl font-bold">{{ $this->thisWeekCount }}</span>
        </div>
    </div>
</div>
