{{-- DEMO режим переключатель (показывается только на Replit) --}}
@if(config('app.env') !== 'production')
<div class="fixed bottom-4 right-4 z-50" x-data="{ demoMode: {{ session('demo_mode', true) ? 'true' : 'false' }} }">
    <button 
        @click="toggleDemoMode()"
        :class="demoMode ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'"
        class="px-4 py-3 rounded-lg shadow-2xl text-white font-semibold flex items-center gap-2 transition-all hover:scale-105"
    >
        <span x-text="demoMode ? '🟢' : '🔴'" class="text-xl"></span>
        <span x-text="demoMode ? 'DEMO (быстро)' : 'БОЕВОЙ (медленно)'"></span>
    </button>

    <script>
        function toggleDemoMode() {
            fetch('{{ route('toggle.demo.mode') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Обновляем состояние
                    Alpine.store('demoMode', data.demo_mode);
                    // Перезагружаем страницу для применения изменений
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</div>
@endif
