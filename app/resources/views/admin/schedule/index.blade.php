@extends('layouts.admin')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            📅 Jadwal Resmi Perpustakaan Keliling
        </h1>
        <p class="text-gray-600 mt-1">Kelola dan monitor jadwal kunjungan perpustakaan keliling</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
            ✅ {{ session('status') }}
        </div>
    @endif

    <!-- Filter & Action Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <!-- Filter Card -->
        <div class="bg-white rounded-lg shadow-md p-5">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                🔍 Filter Jadwal
            </h3>
            <form method="get" action="{{ route('admin.schedule.index') }}" class="space-y-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal:</label>
                    <input id="date" type="date" name="date" value="{{ $date }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-sm">
                        🔍 Tampilkan
                    </button>
                    <a href="{{ route('admin.schedule.index') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium text-center transition">
                        🔄 Reset
                    </a>
                </div>

                <div class="pt-3 border-t border-gray-200">
                    @if($showAll ?? false)
                        <a href="{{ route('admin.schedule.index', array_filter(['date' => $date])) }}" 
                           class="block text-center bg-orange-50 border border-orange-300 text-orange-700 px-4 py-2 rounded-lg hover:bg-orange-100 transition">
                            📋 Menampilkan Semua (Termasuk Lewat)
                        </a>
                    @else
                        <a href="{{ route('admin.schedule.index', array_filter(['date' => $date, 'show_all' => '1'])) }}" 
                           class="block text-center bg-green-50 border border-green-300 text-green-700 px-4 py-2 rounded-lg hover:bg-green-100 transition">
                            ✅ Hanya Upcoming
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Generate Schedule Card -->
        <div class="bg-white rounded-lg shadow-md p-5">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                ⚙️ Generate Jadwal
            </h3>
            
            <div class="space-y-3">
                <form method="get" action="{{ route('admin.schedule.preview') }}" target="_blank">
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Preview:</label>
                        <input type="date" name="date" value="{{ $date ?? now()->toDateString() }}" required 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent" />
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-sm">
                        👁️ Preview Jadwal
                    </button>
                </form>

                <form method="post" action="{{ route('admin.schedule.commit') }}" onsubmit="return confirm('⚠️ Yakin simpan jadwal ini?\n\nJadwal yang ada di tanggal ini akan dihapus dan diganti dengan jadwal baru.')">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Commit:</label>
                        <input type="date" name="date" value="{{ $date ?? now()->toDateString() }}" required 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent" />
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-sm">
                        ✅ Commit Jadwal
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Schedules Table Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 border-b border-blue-800">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-white">
                    📋 Daftar Jadwal
                </h3>
                <span class="text-sm font-normal bg-blue-500 px-3 py-1 rounded-full text-white">
                    {{ $schedules->total() }} jadwal
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white-500 uppercase tracking-wider">Instansi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white-500 uppercase tracking-wider">Mulai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white-500 uppercase tracking-wider">Selesai</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($schedules as $i => $s)
                        @php
                            $isPast = $s->end_time && \Carbon\Carbon::parse($s->end_time)->isPast();
                            $rowClass = $isPast ? 'bg-gray-50 opacity-60' : 'hover:bg-blue-50';
                        @endphp
                        <tr class="{{ $rowClass }} transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $schedules->firstItem() + $i }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-bold text-lg">
                                            {{ substr(optional($s->reservation)->full_name ?? 'N', 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ optional($s->reservation)->full_name ?? '-' }}
                                        </div>
                                        @if($isPast)
                                            <span class="text-xs text-gray-500">⏰ Selesai</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(optional($s->reservation)->category)
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ $s->reservation->category }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ optional($s->reservation)->occupation ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                📅 {{ optional($s->scheduled_date)->format('d M Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                🕐 {{ optional($s->start_time)->format('H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                🕒 {{ optional($s->end_time)->format('H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <div class="text-6xl mb-4">📄</div>
                                    <p class="text-lg font-medium text-gray-600">Tidak ada jadwal ditemukan</p>
                                    <p class="text-sm mt-1">Coba ubah filter atau generate jadwal baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($schedules->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
