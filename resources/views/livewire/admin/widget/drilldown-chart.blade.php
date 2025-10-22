<div class="p-6" x-data="chartComponent(@js($chartData))" x-init="init()">
    {{-- مسیر Breadcrumb --}}
    @if ($breadcrumbs)
        <div class="mb-4 flex items-center gap-2 text-sm">
            <button wire:click="goBack" class="text-primary hover:underline">خانه</button>
            @foreach ($breadcrumbs as $index => $crumb)
                <span>/</span>
                <button wire:click="goBack({{ $index + 1 }})" class="text-primary hover:underline">
                    {{ ucfirst($crumb) }}
                </button>
            @endforeach
        </div>
    @endif

    {{-- لودینگ --}}
    @if ($loading)
        <div class="flex items-center justify-center py-20">
            <span class="loading loading-spinner loading-lg text-primary"></span>
        </div>
    @else
        <div class="bg-base-100 rounded-2xl shadow p-4">
            <canvas id="chart"></canvas>
        </div>
    @endif

    <script>
        function chartComponent(initialData) {
            return {
                chart: null,
                data: initialData,

                init() {
                    this.$watch('$wire.chartData', (newData) => {
                        this.data = newData;
                        this.renderChart(this.data);
                    });
                    this.renderChart(this.data);
                },

                renderChart(data) {
                    if (!data || data.length === 0) return;

                    const ctx = document.getElementById('chart').getContext('2d');
                    if (this.chart) this.chart.destroy();

                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.map(d => d.label),
                            datasets: [{
                                label: 'تعداد بازدید',
                                data: data.map(d => d.value),
                                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                borderColor: 'rgb(37, 99, 235)',
                                borderWidth: 1,
                            }]
                        },
                        options: {
                            responsive: true,
                            onClick: (evt, elements) => {
                                if (elements.length) {
                                    const index = elements[0].index;
                                    const id = data[index].id;
                                    this.$wire.loadLevel(id);
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }
        }
    </script>
</div>
