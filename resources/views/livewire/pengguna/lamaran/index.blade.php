<section class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex justify-between items-center flex-wrap gap-4 mb-8">
        <h2 class="text-2xl font-bold text-gray-800">📋 Daftar Lowongan Tersedia</h2>
        <input type="search" wire:model="search" placeholder="🔍 Cari lokasi atau posisi..."
            class="border border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg px-4 py-2 w-full md:w-1/3 shadow-sm" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($lowongans as $lowongan)
            <div class="bg-white border border-gray-200 rounded-xl shadow hover:shadow-lg transition duration-300">
                <div class="p-5 space-y-2">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $lowongan->judul_pekerjaan }}</h3>
                    <p class="text-sm text-gray-500">{{ $lowongan->nama_perusahaan }}</p>
                    <div class="flex items-center text-sm text-gray-400 gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 12l4.243-4.243m-9.9 0L10.586 12l-4.243 4.243" />
                        </svg>
                        {{ $lowongan->lokasi }}
                    </div>

                    <span class="inline-block mt-2 text-xs px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-medium">
                        {{ $lowongan->kategori }}
                    </span>

                    <div class="flex justify-between items-center mt-4">
                        <a href="{{ route('pengguna.lamaran.create', $lowongan->id) }}"
                            class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
                            Lamar
                        </a>
                        <span class="text-xs text-gray-400 whitespace-nowrap">
                            📅 {{ $lowongan->tanggal_berakhir->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 text-sm">
                Tidak ada lowongan ditemukan.
            </div>
        @endforelse
        @foreach ($lowongans as $lowongan)
            <div class="bg-white rounded-xl shadow p-5 space-y-2">
                <h3 class="text-lg font-semibold">{{ $lowongan->judul_pekerjaan }}</h3>
                <p class="text-sm text-gray-600">{{ $lowongan->nama_perusahaan }}</p>

                <a href="{{ route('pengguna.lamaran.create', $lowongan->id) }}"
                    class="inline-block mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Lamar Sekarang
                </a>
            </div>
        @endforeach

    </div>
</section>