<div class="max-w-7xl mx-auto p-4 space-y-6">
    {{-- Notifikasi --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm flex items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mr-2 text-green-600" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11V5a1 1 0 10-2 0v2a1 1 0 102 0zm0 6v-4a1 1 0 10-2 0v4a1 1 0 102 0z"
                    clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800">
                ✕
            </button>
        </div>
    @endif

    {{-- Chart Section --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Lamaran Masuk (7 Hari Terakhir)</h2>
        <div class="relative h-64">
            <canvas id="lamaranChart" class="w-full h-full"></canvas>
        </div>
    </div>

    {{-- Table & Controls --}}
    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="text-xl font-semibold">Daftar Lamaran Masuk</h2>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <input type="text" wire:model.debounce.300ms="search" placeholder="Cari pelamar atau lowongan..."
                        class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" />
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 16.65z" />
                        </svg>
                    </span>
                </div>
                <button wire:click="exportExcel"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-500 text-white text-sm font-semibold rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Unduh Excel
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lowongan Dilamar
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Melamar</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($lamarans as $lamaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $lamaran->user?->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $lamaran->lowongan?->judul_lowongan ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $status = strtolower($lamaran->status);
                                    $classes = match ($status) {
                                        'diterima' => 'bg-green-100 text-green-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                        default => 'bg-yellow-100 text-yellow-800',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $classes }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ optional($lamaran->created_at)->format('d M Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button x-data x-on:click.prevent="
                                            if (confirm('Anda yakin ingin menghapus lamaran ini?')) {
                                                $wire.delete({{ $lamaran->id }})
                                            }
                                        " wire:loading.attr="disabled"
                                    class="text-red-600 hover:text-red-900 disabled:opacity-50">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada data lamaran ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $lamarans->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:load', () => {
            const ctx = document.getElementById('lamaranChart');
            if (!ctx) return;

            // Data dari Livewire: { labels: [...], data: [...] }
            const chartData = @json($chartData);

            // Jika instance lama ada, destroy dulu
            if (window.lamaranChartInstance) {
                window.lamaranChartInstance.destroy();
            }

            window.lamaranChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Jumlah Lamaran',
                        data: chartData.data,
                        backgroundColor: chartData.data.map(() => 'rgba(54, 162, 235, 0.5)'),
                        borderColor: chartData.data.map(() => 'rgba(54, 162, 235, 1)'),
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { mode: 'index', intersect: false }
                    }
                }
            });

            // Re-render chart jika Livewire update chartData
            Livewire.hook('message.processed', (message, component) => {
                if (message.updateQueue.some(u => u.data?.chartData)) {
                    const newData = @this.chartData;
                    window.lamaranChartInstance.data.labels = newData.labels;
                    window.lamaranChartInstance.data.datasets[0].data = newData.data;
                    window.lamaranChartInstance.update();
                }
            });
        });
    </script>
@endpush