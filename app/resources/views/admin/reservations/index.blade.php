@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl">🗂️ Kelola Reservasi</h1>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full text-sm">
            <thead>
                <tr>
                    <th class="px-3 py-2 text-left">NO</th>
                    <th class="px-3 py-2 text-left">NAMA PEMOHON</th>
                    <th class="px-3 py-2 text-left">KATEGORI</th>
                    <th class="px-3 py-2 text-left">INSTANSI/EVENT</th>
                    <th class="px-3 py-2 text-left">TANGGAL</th>
                    <th class="px-3 py-2 text-left">WAKTU</th>
                    <th class="px-3 py-2 text-left">FCFS METRICS</th>
                    <th class="px-3 py-2 text-left">SURAT</th>
                    <th class="px-3 py-2 text-left">STATUS</th>
                    <th class="px-3 py-2 text-left">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservations as $i => $r)
                <tr class="border-t">
                    <td class="px-3 py-2">{{ $reservations->firstItem() + $i }}</td>
                    <td class="px-3 py-2">{{ $r->full_name }}</td>
                    <td class="px-3 py-2">{{ $r->category }}</td>
                    <td class="px-3 py-2">{{ $r->occupation }}</td>
                    <td class="px-3 py-2">{{ optional($r->reservation_date)->format('Y-m-d') }}</td>
                    <td class="px-3 py-2">
                        @if($r->visit_time)
                            {{ \Carbon\Carbon::parse($r->visit_time)->format('H:i') }}
                            @if($r->end_time)
                                - {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}
                                <br><small class="text-gray-500">(2 jam)</small>
                            @endif
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        @if($r->start_time)
                            @php
                                // Format waktu ke jam/menit yang mudah dibaca
                                $formatTime = function($minutes) {
                                    if ($minutes == 0) return '0m';
                                    if ($minutes < 60) return $minutes . 'm';
                                    
                                    $hours = floor($minutes / 60);
                                    $mins = $minutes % 60;
                                    
                                    if ($hours >= 24) {
                                        $days = floor($hours / 24);
                                        $hours = $hours % 24;
                                        if ($hours > 0) {
                                            return $days . 'h ' . $hours . 'j ' . ($mins > 0 ? $mins . 'm' : '');
                                        }
                                        return $days . 'h ' . ($mins > 0 ? $mins . 'm' : '');
                                    }
                                    
                                    return $hours . 'j ' . ($mins > 0 ? $mins . 'm' : '');
                                };
                                
                                $formattedWT = $formatTime($r->waiting_time ?? 0);
                                $formattedTAT = $formatTime($r->turnaround_time ?? 0);
                            @endphp
                            <div style="font-size: 11px; line-height: 1.6;">
                                <div style="margin-bottom: 4px;">
                                    <span style="color: #7c3aed; font-weight: 600;">Posisi:</span>
                                    <span style="background: #f3e8ff; color: #6b21a8; padding: 2px 6px; border-radius: 4px; font-weight: 700;">#{{ $r->queue_position ?? '-' }}</span>
                                </div>
                                <div style="margin-bottom: 4px;">
                                    <span style="color: #2563eb; font-weight: 600;">WT:</span>
                                    <span style="background: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 4px;">{{ $formattedWT }}</span>
                                </div>
                                <div style="margin-bottom: 4px;">
                                    <span style="color: #16a34a; font-weight: 600;">TAT:</span>
                                    <span style="background: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 4px;">{{ $formattedTAT }}</span>
                                </div>
                                <div style="color: #4b5563; margin-top: 6px; padding-top: 4px; border-top: 1px solid #e5e7eb;">
                                    <strong>Start:</strong> {{ \Carbon\Carbon::parse($r->start_time)->format('H:i') }}
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 text-xs italic">Belum diproses</span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        @if ($r->request_letter)
                            <a class="text-blue-600 underline" href="{{ Storage::url($r->request_letter) }}" target="_blank">Lihat PDF</a>
                        @else
                            <span class="text-red-600">Belum Upload</span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        @php
                            // Cek apakah reservasi sudah selesai (confirmed + waktu sudah lewat)
                            $isCompleted = false;
                            if ($r->status === 'confirmed' && $r->reservation_date && $r->end_time) {
                                $endDateTime = \Carbon\Carbon::parse($r->reservation_date->format('Y-m-d') . ' ' . $r->end_time);
                                $isCompleted = $endDateTime->isPast();
                            } elseif ($r->status === 'confirmed' && $r->reservation_date) {
                                // Fallback: jika tidak ada end_time, cek apakah tanggal sudah lewat
                                $isCompleted = $r->reservation_date->endOfDay()->isPast();
                            }
                        @endphp
                        
                        @if ($r->status === 'pending')
                            <span class="text-orange-600 font-semibold">⏳ Menunggu</span>
                        @elseif ($r->status === 'confirmed' && $isCompleted)
                            <span class="text-blue-600 font-semibold">✅ Selesai</span>
                        @elseif ($r->status === 'confirmed')
                            <span class="text-green-600 font-semibold">✓ Diterima</span>
                        @else
                            <span class="text-red-600 font-semibold">✗ Ditolak</span>
                            @if($r->rejection_reason)
                                <br><small class="text-gray-600">Alasan: {{ Str::limit($r->rejection_reason, 30) }}</small>
                            @endif
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        @if ($r->status === 'pending' || ($r->status === 'confirmed' && !$isCompleted))
                        <form method="POST" action="{{ route('admin.reservations.updateStatus', $r) }}" class="reservation-form" id="form-{{ $r->id }}">
                            @csrf
                            <div class="flex flex-col gap-2">
                                <input type="time" name="visit_time" 
                                       value="{{ $r->visit_time ? \Carbon\Carbon::parse($r->visit_time)->format('H:i') : '' }}" 
                                       class="border rounded p-1 text-sm" 
                                       step="300" 
                                       placeholder="Set waktu" />
                                <select name="status" class="border rounded p-1 text-sm status-select" data-reservation-id="{{ $r->id }}" data-reservation-name="{{ $r->full_name }}">
                                    <option value="confirmed" @selected($r->status === 'confirmed')>✓ Terima</option>
                                    <option value="rejected">✗ Tolak</option>
                                </select>
                                <textarea name="rejection_reason" 
                                          class="border rounded p-1 text-sm rejection-reason-input hidden" 
                                          placeholder="Tulis alasan penolakan..."
                                          rows="3"
                                          maxlength="500"></textarea>
                                <button type="submit" class="btn bg-blue-600" style="padding: 6px 12px; font-size: 12px;">Update</button>
                            </div>
                        </form>
                        @elseif ($r->status === 'confirmed' && $isCompleted)
                            <span class="text-blue-600 text-sm">✅ Selesai</span>
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-3 py-4 text-center text-gray-500">Belum ada data reservasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $reservations->links() }}</div>
    <div class="mt-6"><a href="{{ route('admin.books.index') }}" class="text-blue-600 underline">Kembali ke Dashboard</a></div>
</div>

<!-- Modal Dialog untuk Konfirmasi Penolakan -->
<div id="rejection-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4 text-red-600">⚠️ Konfirmasi Penolakan Reservasi</h3>
        <p class="mb-4 text-gray-700">Anda akan menolak reservasi dari <strong id="modal-reservation-name"></strong>.</p>
        <p class="mb-4 text-sm text-gray-600">Silakan tulis alasan penolakan agar user dapat memahami keputusan ini:</p>
        <textarea id="modal-rejection-reason" 
                  class="w-full border rounded p-3 text-sm mb-4" 
                  placeholder="Contoh: Waktu bentrok dengan reservasi lain yang sudah dikonfirmasi"
                  rows="4"
                  maxlength="500"></textarea>
        <small class="text-gray-500 block mb-4"><span id="char-count">0</span>/500 karakter</small>
        <div class="flex gap-2 justify-end">
            <button id="modal-cancel" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button id="modal-confirm" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Tolak Reservasi</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('rejection-modal');
    const modalName = document.getElementById('modal-reservation-name');
    const modalReason = document.getElementById('modal-rejection-reason');
    const modalCancel = document.getElementById('modal-cancel');
    const modalConfirm = document.getElementById('modal-confirm');
    const charCount = document.getElementById('char-count');
    
    let currentForm = null;
    let currentReasonInput = null;
    
    // Toggle rejection reason textarea when status changes
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const form = this.closest('form');
            const reasonInput = form.querySelector('.rejection-reason-input');
            
            if (this.value === 'rejected') {
                reasonInput.classList.remove('hidden');
                reasonInput.required = true;
                
                // Show modal untuk konfirmasi
                currentForm = form;
                currentReasonInput = reasonInput;
                modalName.textContent = this.dataset.reservationName;
                modalReason.value = reasonInput.value || '';
                modal.classList.remove('hidden');
            } else {
                reasonInput.classList.add('hidden');
                reasonInput.required = false;
                reasonInput.value = '';
            }
        });
    });
    
    // Character counter
    modalReason.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    
    // Modal cancel
    modalCancel.addEventListener('click', function() {
        modal.classList.add('hidden');
        if (currentForm) {
            const select = currentForm.querySelector('.status-select');
            select.value = 'confirmed'; // Reset to confirmed
            currentReasonInput.classList.add('hidden');
            currentReasonInput.value = '';
        }
        currentForm = null;
        currentReasonInput = null;
    });
    
    // Modal confirm
    modalConfirm.addEventListener('click', function() {
        if (modalReason.value.trim() === '') {
            alert('Alasan penolakan harus diisi!');
            return;
        }
        
        if (currentReasonInput) {
            currentReasonInput.value = modalReason.value;
        }
        
        modal.classList.add('hidden');
        
        // Submit form after modal close
        if (currentForm) {
            currentForm.submit();
        }
    });
    
    // Close modal on overlay click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modalCancel.click();
        }
    });
    
    // Prevent form submission if rejected without reason
    document.querySelectorAll('.reservation-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const select = form.querySelector('.status-select');
            const reasonInput = form.querySelector('.rejection-reason-input');
            
            if (select.value === 'rejected' && !reasonInput.value.trim()) {
                e.preventDefault();
                alert('Alasan penolakan harus diisi!');
                return false;
            }
        });
    });
});
</script>

<style>
.rejection-reason-input {
    resize: vertical;
    min-height: 60px;
}

.reservation-form select:focus,
.reservation-form input:focus,
.reservation-form textarea:focus {
    outline: none;
    border-color: #0693E3;
    box-shadow: 0 0 0 2px rgba(6, 147, 227, 0.1);
}
</style>
@endsection
