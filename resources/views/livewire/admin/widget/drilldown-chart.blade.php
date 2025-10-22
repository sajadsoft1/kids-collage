<div class="p-6">
    {{-- مسیر ناوبری --}}
    @if ($breadcrumbs)
        <div class="flex gap-2 items-center mb-4 text-sm">
            <button wire:click="goBack" class="text-primary hover:underline">خانه</button>
            @foreach ($breadcrumbs as $index => $crumb)
                <span>/</span>
                <button wire:click="goBack({{ $index + 1 }})" class="text-primary hover:underline">
                    {{ $crumb }}
                </button>
            @endforeach
        </div>
    @endif

    {{-- چارت --}}
    <div wire:ignore class="p-4 rounded-2xl shadow bg-base-100">
        <canvas id="chart" height="300"></canvas>
    </div>

    <script>
        document.addEventListener('livewire:load', () => {
            const ctx = document.getElementById('chart').getContext('2d');

            let chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'تعداد بازدید',
                        data: [],
                        backgroundColor: 'rgba(59,130,246,0.6)',
                        borderColor: 'rgb(37,99,235)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    onClick: (evt, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const id = chart.data.meta[index];
                            Livewire.dispatch('chartClicked', {
                                id
                            });
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Livewire event → update chart
            Livewire.on('updateChart', (data) => {
                chart.data.labels = data.map(d => d.label);
                chart.data.datasets[0].data = data.map(d => d.value);
                chart.data.meta = data.map(d => d.id);
                chart.update();
            });

            // event از chart → Livewire
            Livewire.on('chartClicked', ({
                id
            }) => {
                @this.loadLevel(id);
            });
        });
    </script>
</div>
