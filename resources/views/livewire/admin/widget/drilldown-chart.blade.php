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
        <div style="height: 300px; width: 100%;">
            <canvas id="chart"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
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
                    maintainAspectRatio: false,
                    onClick: (evt, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const id = chart.data.meta[index];
                            // بررسی اینکه آیا سطح فعلی ساعت است یا نه
                            if (!id.includes('hour-')) {
                                @this.call('loadLevel', id);
                            }
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
            Livewire.on('updateChart', (event) => {
                const data = event.data;
                if (data && Array.isArray(data)) {
                    chart.data.labels = data.map(d => d.label);
                    chart.data.datasets[0].data = data.map(d => d.value);
                    chart.data.meta = data.map(d => d.id);
                    chart.update();
                }
            });

        });
    </script>
</div>
