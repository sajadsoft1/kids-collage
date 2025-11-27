{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- TIMER SCRIPT --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('examTimer', () => ({
            elapsedSeconds: {{ $elapsedSeconds }},
            remainingSeconds: {{ $timeRemaining ?? 0 }},
            hasTimeLimit: {{ $timeRemaining !== null ? 'true' : 'false' }},
            reviewMode: {{ $reviewMode ? 'true' : 'false' }},
            interval: null,

            init() {
                // در حالت بازبینی تایمر اجرا نمی‌شود
                if (!this.reviewMode) {
                    this.startTimer();
                }
            },

            startTimer() {
                this.interval = setInterval(() => {
                    this.elapsedSeconds++;

                    if (this.hasTimeLimit) {
                        this.remainingSeconds--;
                        if (this.remainingSeconds <= 0) {
                            clearInterval(this.interval);
                            @this.call('handleTimeExpired');
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
    });
</script>

