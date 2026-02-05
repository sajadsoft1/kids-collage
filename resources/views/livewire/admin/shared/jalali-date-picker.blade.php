<div x-data="jalaliDatePicker({
    initial: @js($jalali),
    model: @entangle('value')
})">
    <input type="date" x-model="jalali" @change="convert()" class="w-full input input-bordered"
        placeholder="1404/01/01" />
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('jalaliDatePicker', ({
                initial,
                model
            }) => ({
                jalali: initial,
                model: model,

                async convert() {
                    if (!this.jalali) {
                        this.model = null
                        return
                    }

                    let res = await fetch('/jalali-to-gregorian', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')
                                .content
                        },
                        body: JSON.stringify({
                            date: this.jalali
                        })
                    })

                    let data = await res.json()
                    this.model = data.date
                }
            }))
        })
    </script>
@endonce
