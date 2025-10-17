<div class="h-64">
    <canvas id="atividadeRecente"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('atividadeRecente').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(\App\Models\Pesquisa::selectRaw('DATE(created_at) as date')
                    ->where('created_at', '>=', now()->subDays(7))
                    ->groupBy('date')
                    ->pluck('date')) !!},
                datasets: [{
                    label: 'Novas Pesquisas',
                    data: {!! json_encode(\App\Models\Pesquisa::selectRaw('COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->groupBy('date')
                        ->pluck('count')) !!},
                    borderColor: '#3B82F6',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush 