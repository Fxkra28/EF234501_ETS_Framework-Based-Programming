<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @livewire(App\Livewire\EDDStatsUsage::class)
        @livewire(App\Livewire\SalesStats::class)
        </div>
    </div>
</x-layouts.app>
