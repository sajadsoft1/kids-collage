{{--
    Custom Chart Component with One-Way Data Binding

    This component accepts chart data directly as a prop instead of using
    wire:model/@entangle. This prevents Chart.js from causing serialization
    errors when Livewire tries to sync the modified settings object back.

    Usage:
        <x-chart :data="$chartConfig" id="myChart" />

    The data prop should be a Chart.js configuration array with:
        - type: 'bar', 'line', 'pie', etc.
        - data: { labels: [...], datasets: [...] }
        - options: (optional) Chart.js options
--}}

<div wire:key="{{ $uuid }}" x-data="{
    chart: null,
    settings: @js($chartData),
    init() {
        this.renderChart();
    },
    renderChart() {
        if (this.chart) {
            this.chart.destroy();
        }
        if (this.settings && typeof this.settings === 'object' && Object.keys(this.settings).length > 0) {
            {{-- Create a deep copy to avoid Chart.js modifying the original --}}
            const config = JSON.parse(JSON.stringify(this.settings));
            this.chart = new Chart(this.$refs.canvas, config);
        }
    }
}" {{ $attributes->class(['relative']) }}>
    <canvas x-ref="canvas"></canvas>
</div>
