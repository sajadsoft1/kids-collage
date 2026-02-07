<x-card title="{{ trans('dashboard.charts.monthly_growth') }}" shadow>
    <div class="space-y-4">
        <div class="flex items-center justify-between text-sm text-gray-600">
            <span>{{ trans('dashboard.charts.metrics.users') }}</span>
            <span>{{ trans('dashboard.charts.metrics.blogs') }}</span>
        </div>

        <!-- Multi-line Chart -->
        <div class="h-48 flex items-end justify-between space-x-2">
            @php
                $maxValue = 0;
                foreach ($this->monthlyGrowthData as $monthData) {
                    $maxValue = max($maxValue, $monthData['users'], $monthData['blogs']);
                }
                $maxValue = $maxValue > 0 ? $maxValue : 1;
            @endphp

            @foreach ($this->monthlyGrowthData as $month => $data)
                <div class="flex-1 flex flex-col items-center space-y-1">
                    <div class="w-full flex flex-col space-y-1">
                        <div class="bg-blue-500 rounded-t" style="height: {{ ($data['users'] / $maxValue) * 100 }}%">
                        </div>
                        <div class="bg-green-500 rounded-t" style="height: {{ ($data['blogs'] / $maxValue) * 100 }}%">
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $month }}</span>
                </div>
            @endforeach
        </div>

        <!-- Legend -->
        <div class="flex items-center justify-center space-x-4 text-xs">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-blue-500 rounded"></div>
                <span>{{ trans('dashboard.charts.metrics.users') }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded"></div>
                <span>{{ trans('dashboard.charts.metrics.blogs') }}</span>
            </div>
        </div>
    </div>
</x-card>
