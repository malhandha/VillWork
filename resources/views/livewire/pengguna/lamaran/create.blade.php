<section class="max-w-3xl mx-auto mt-10 px-4 md:px-0">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
        <ol class="list-reset flex">
            <li><a href="{{ route('pengguna.lamaran.index') }}" class="hover:underline">Lowongan</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-700">Lamar</li>
        </ol>
    </nav>

    <div class="bg-white/90 backdrop-blur-md border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
        {{-- Header --}}
        <div class="bg-blue-900 text-white px-6 py-4 text-lg font-semibold flex justify-between">
            <div>
                Lamar: <span class="font-bold">{{ $lowongan->judul_pekerjaan }}</span>
                <span class="text-sm opacity-80">@ {{ $lowongan->nama_perusahaan }}</span>
            </div>
            <div class="text-sm opacity-75">
                Berakhir: {{ optional($lowongan->tanggal_berakhir)->format('d M Y') ?? '-' }}
            </div>
        </div>

        <div class="px-6 py-6 space-y-6">
            {{-- Flash Message --}}
            @if (session('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow">
                    {{ session('message') }}
                </div>
            @endif

            {{-- Error Summary --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded shadow">
                    <p class="font-medium mb-2">Masalah ditemukan:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form wire:submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="nama" disabled
                            class="w-full p-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Email</label>
                        <input type="email" wire:model="email" disabled
                            class="w-full p-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed" />
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">No. Telepon</label>
                        <input type="tel" wire:model.defer="telepon" placeholder="08xxxxxxxxxx"
                            class="w-full p-3 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
                        @error('telepon') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- LinkedIn --}}
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">LinkedIn (Opsional)</label>
                        <input type="url" wire:model.defer="linkedin" placeholder="https://linkedin.com/in/username"
                            class="w-full p-3 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
                        @error('linkedin') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Pesan --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Pesan Lamaran</label>
                    <textarea wire:model.defer="pesan" rows="5" placeholder="Tulis alasan Anda melamar..."
                        class="w-full p-3 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                    @error('pesan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Upload CV --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Upload CV (PDF maks. 2MB)</label>
                    <input type="file" wire:model="cv" accept="application/pdf"
                        class="w-full p-3 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
                    @error('cv') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <div wire:loading wire:target="cv" class="text-sm text-blue-600 mt-2">Mengunggah CV...</div>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full bg-blue-900 hover:bg-blue-800 transition text-white font-semibold py-3 px-4 rounded-lg shadow">
                        Kirim Lamaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>