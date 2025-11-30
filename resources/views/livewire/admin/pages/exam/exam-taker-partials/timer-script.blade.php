{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- TIMER SCRIPT --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<script>
    // استفاده از livewire:navigated برای سازگاری با wire:navigate
    // این کد در هر بار لود شدن صفحه (حتی با wire:navigate) اجرا می‌شود

    function initExamTakerData() {
        // تعریف examTakerData که شامل timer و modal states است
        if (!Alpine.data('examTakerData')) {
            Alpine.data('examTakerData', () => ({
                // Timer properties
                elapsedSeconds: {{ $elapsedSeconds }},
                remainingSeconds: {{ $timeRemaining ?? 0 }},
                hasTimeLimit: {{ $timeRemaining !== null ? 'true' : 'false' }},
                reviewMode: {{ $reviewMode ? 'true' : 'false' }},
                interval: null,

                // Modal states
                showEndModal: false,
                showSuspendModal: false,
                showFinishModal: false,

                init() {
                    // پاک کردن interval قبلی اگر وجود داشته باشد
                    if (this.interval) {
                        clearInterval(this.interval);
                        this.interval = null;
                    }

                    // در حالت بازبینی تایمر اجرا نمی‌شود
                    if (!this.reviewMode) {
                        this.startTimer();
                    }
                },

                startTimer() {
                    // پاک کردن interval قبلی اگر وجود داشته باشد
                    if (this.interval) {
                        clearInterval(this.interval);
                    }

                    this.interval = setInterval(() => {
                        this.elapsedSeconds++;

                        if (this.hasTimeLimit) {
                            this.remainingSeconds--;
                            if (this.remainingSeconds <= 0) {
                                clearInterval(this.interval);
                                this.interval = null;
                                // استفاده از $wire که در Alpine در دسترس است
                                if (typeof $wire !== 'undefined' && $wire) {
                                    $wire.call('handleTimeExpired');
                                }
                            }
                        }
                    }, 1000);
                },

                // فرمت کامل HH:MM:SS برای هدر
                formatTime() {
                    const seconds = this.hasTimeLimit ? this.remainingSeconds : this.elapsedSeconds;
                    const hours = Math.floor(Math.abs(seconds) / 3600);
                    const minutes = Math.floor((Math.abs(seconds) % 3600) / 60);
                    const secs = Math.abs(seconds) % 60;

                    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                },

                // فرمت کوتاه MM:SS برای نوار پایین
                formatTimeShort() {
                    const seconds = this.hasTimeLimit ? this.remainingSeconds : this.elapsedSeconds;
                    const totalMinutes = Math.floor(Math.abs(seconds) / 60);
                    const secs = Math.abs(seconds) % 60;

                    return `${String(totalMinutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                }
            }));
        }
    }

    // اجرای فوری برای بار اول
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initExamTakerData);
    } else {
        initExamTakerData();
    }

    // اجرا بعد از navigate شدن
    document.addEventListener('livewire:navigated', initExamTakerData);
</script>
