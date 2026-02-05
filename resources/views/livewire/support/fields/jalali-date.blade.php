<div wire:ignore x-data="jalaliPicker({
    model: @entangle('value')
})">
    <input x-ref="input" type="text" class="w-full input input-bordered" placeholder="1404/01/01" />
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('jalaliPicker', ({
                model
            }) => ({

                model: model,
                picker: null,

                init() {

                    this.picker = flatpickr(this.$refs.input, {
                        altInput: true,
                        altFormat: "YYYY/MM/DD",
                        dateFormat: "Y-m-d",
                        plugins: [new jalaliPlugin({})],

                        onChange: (selectedDates, dateStr) => {
                            this.model = dateStr || null
                        }
                    })

                    this.$watch('model', val => {
                        if (!val) {
                            this.picker.clear()
                            return
                        }
                        this.picker.setDate(val, false)
                    })
                }

            }))
        })
    </script>
@endonce
