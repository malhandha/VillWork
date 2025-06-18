<section class="max-w-7xl mx-auto px-6 py-10">
    {{-- Header & Search --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.75 17L15 12m0 0l-5.25-5M15 12H3" />
            </svg>
            Daftar Lowongan Tersedia
        </h2>
        <div class="relative w-full md:w-1/3">
            <input type="search" wire:model.debounce.300ms="search" placeholder="Cari lokasi atau posisi..."
                class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 16.65z" />
                </svg>
            </span>
            @if($search)
                <button wire:click="$set('search','')"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600"
                    title="Bersihkan">✕</button>
            @endif
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading.flex class="justify-center mb-6">
        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
    </div>

    {{-- Grid Lowongan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($lowongans as $lowongan)
            <div
                class="flex flex-col bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg transition duration-300 h-full">
                {{-- Konten Utama --}}
                <div class="p-5 flex-1 flex flex-col">
                    {{-- Judul --}}
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">{{ $lowongan->judul_pekerjaan }}</h3>
                    {{-- Perusahaan --}}
                    <p class="text-sm text-gray-500 mb-3 line-clamp-1">{{ $lowongan->nama_perusahaan }}</p>
                    {{-- Lokasi --}}
                    <div class="flex items-center text-sm text-gray-400 gap-1 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 22s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z" />
                        </svg>
                        <span class="truncate">{{ $lowongan->lokasi }}</span>
                    </div>
                    {{-- Kategori --}}
                    <span
                        class="inline-block text-xs px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-medium self-start">
                        {{ $lowongan->kategori }}
                    </span>
                </div>

                {{-- Footer Card --}}
                <div class="p-5 border-t border-gray-100 flex justify-between items-center">
                    <a href="{{ route('pengguna.lamaran.create', $lowongan->id) }}"
                        class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Lamar
                    </a>
                    <span class="text-xs text-gray-400 whitespace-nowrap flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z" />
                        </svg>
                        {{ optional($lowongan->tanggal_berakhir)->format('d M Y') ?: '-' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6a2 2 0 114 0v6m-2 4a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                <p class="mt-4 text-sm">Tidak ada lowongan ditemukan.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8 flex justify-center">
        {{ $lowongans->links() }}
    </div>
</section>

@push('styles')
    <style>
        /* Jika Tailwind line-clamp plugin tersedia */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush