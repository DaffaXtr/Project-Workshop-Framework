@extends('layouts.app')

@section('title', 'Manajemen Antrian')

@section('content')
<div class="container">
    <h2>🎛️ Panel Admin Antrian</h2>
    <p class="text-muted small">Kelola dan panggil antrian pasien secara real-time</p>
    
    {{-- CONTROLS --}}
    <div class="mb-4 d-flex gap-2" style="gap: 10px;">
        <button class="btn btn-sm btn-primary btn-loader font-weight-bold" onclick="panggil()">
            <i class="mdi mdi-arrow-right-bold-circle me-1"></i> Panggil Berikutnya
        </button>
        <button class="btn btn-sm btn-danger btn-loader font-weight-bold" onclick="resetAll()">
            <i class="mdi mdi-refresh me-1"></i> Reset Semua
        </button>
    </div>
</div>

<div class="container">
    <div class="row">
        
        {{-- SIDEBAR / STATUS INFORMASI --}}
        <div class="col-xl-4 col-lg-5 grid-margin stretch-card">
            <div class="d-flex flex-column w-100" style="gap: 20px;">
                
                {{-- CARD ANTRIAN YANG SEDANG DIPANGGIL --}}
                <div class="card text-white text-center" style="background: #b66dff; border-radius: 10px;">
                    <div class="card-body py-4">
                        <h6 class="text-uppercase font-weight-bold text-white-50 mb-3" style="letter-spacing: 0.5px;">Nomor Sedang Dipanggil</h6>
                        <div id="current-content">
                            <div class="current-empty text-white-50 small py-3">Belum ada antrian</div>
                        </div>
                    </div>
                </div>

                {{-- CARD STATISTIK RINGKAS --}}
                <div class="row m-0" style="gap: 12px; display: grid; grid-template-columns: 1fr 1fr;">
                    <div class="card p-3 border-0 shadow-sm text-center" style="border-radius: 8px;">
                        <span class="text-muted font-weight-bold small d-block mb-1" style="font-size: 11px;">Total Antrian</span>
                        <h3 class="font-weight-black text-primary mb-0" id="stat-total" style="font-size: 26px; font-weight: 800;">0</h3>
                    </div>
                    <div class="card p-3 border-0 shadow-sm text-center" style="border-radius: 8px;">
                        <span class="text-muted font-weight-bold small d-block mb-1" style="font-size: 11px;">Menunggu</span>
                        <h3 class="font-weight-black text-warning mb-0" id="stat-menunggu" style="font-size: 26px; font-weight: 800;">0</h3>
                    </div>
                    <div class="card p-3 border-0 shadow-sm text-center" style="border-radius: 8px;">
                        <span class="text-muted font-weight-bold small d-block mb-1" style="font-size: 11px;">Selesai</span>
                        <h3 class="font-weight-black text-success mb-0" id="stat-selesai" style="font-size: 26px; font-weight: 800;">0</h3>
                    </div>
                    <div class="card p-3 border-0 shadow-sm text-center" style="border-radius: 8px;">
                        <span class="text-muted font-weight-bold small d-block mb-1" style="font-size: 11px;">Terlambat</span>
                        <h3 class="font-weight-black text-danger mb-0" id="stat-terlambat" style="font-size: 26px; font-weight: 800;">0</h3>
                    </div>
                </div>

            </div>
        </div>

        {{-- MAIN DATA / DAFTAR ANTRIAN --}}
        <div class="col-xl-8 col-lg-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-dark font-weight-bold mb-3">📋 Daftar Urutan Antrian</h4>
                    
                    <div class="queue-items overflow-auto pe-1" id="list-antrian" style="max-height: 500px;">
                        <div class="empty-state text-center py-5 text-muted">
                            <p class="mb-0">Belum ada data antrian pasien saat ini</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- CSS Custom untuk mendukung render element dinamis dari JS tanpa tabrakan layout --}}
<style>
    .queue-item {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s;
        border-radius: 6px;
        margin-bottom: 5px;
    }
    .queue-item:hover {
        background: #f8f9fa;
    }
    .queue-item.dipanggil {
        background: #fff8ec;
        border-left: 4px solid #ffb64d;
    }
    .queue-nomor {
        font-size: 20px;
        font-weight: 800;
        color: #4b49ac;
        min-width: 50px;
        text-align: center;
    }
    .queue-info {
        flex-grow: 1;
        margin-left: 15px;
    }
    .queue-nama {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
        margin-bottom: 2px;
    }
    .queue-poli {
        font-size: 12px;
        color: #6b7280;
    }
    .status-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 16px;
        text-align: center;
        min-width: 90px;
        margin-right: 15px;
    }
    .status-menunggu { background: #e8daff; color: #6c25be; }
    .status-dipanggil { background: #ffeacf; color: #a16411; }
    .status-selesai { background: #c9f7f1; color: #0f7a6a; }
    .status-terlambat { background: #ffcfd7; color: #b3213c; }

    .action-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 11px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
    }
    .action-btn-mark { background: #ffeef1; color: #fe7c96; }
    .action-btn-mark:hover { background: #fe7c96; color: white; }
    .action-btn-call { background: #fff8ee; color: #ffb64d; }
    .action-btn-call:hover { background: #ffb64d; color: white; }
    
    .current-nomor { font-size: 56px; font-weight: 800; margin: 10px 0; letter-spacing: -1px; line-height: 1; }
    .current-nama { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
    .current-poli { font-size: 13px; opacity: 0.85; }
</style>

<script>
const source = new EventSource('{{ route("sse.antrian") }}');

let allQueues = [];
let lastCalled = null;

source.addEventListener('queue-update', function(event) {
    const result = JSON.parse(event.data);
    allQueues = result.queues || [];

    // Update current queue
    updateCurrentQueue(result.dipanggil);

    // Update list
    updateQueueList();

    // Update stats
    updateStats();
});

function updateCurrentQueue(current) {
    const container = document.getElementById('current-content');

    if(!current) {
        container.innerHTML = '<div class="current-empty text-white-50 small py-3">Belum ada antrian</div>';
        return;
    }

    container.innerHTML = `
        <div class="current-nomor">${current.nomor}</div>
        <div class="current-nama">${current.nama}</div>
        <div class="current-poli">${current.poli}</div>
    `;
}

function updateQueueList() {
    const container = document.getElementById('list-antrian');
    
    if(allQueues.length === 0) {
        container.innerHTML = `
            <div class="empty-state text-center py-5 text-muted">
                <p class="mb-0">Belum ada data antrian pasien saat ini</p>
            </div>`;
        return;
    }

    let html = '';

    allQueues.forEach(queue => {
        const statusClass = queue.status || 'menunggu';
        const isCurrentCalled = queue.status === 'dipanggil';

        html += `
            <div class="queue-item ${isCurrentCalled ? 'dipanggil' : ''}">
                <div class="queue-nomor">${queue.nomor}</div>
                <div class="queue-info">
                    <div class="queue-nama">${queue.nama}</div>
                    <div class="queue-poli">${queue.poli}</div>
                </div>
                <div class="status-badge status-${statusClass}">
                    ${getStatusLabel(statusClass)}
                </div>
                <div class="queue-actions">
                    ${statusClass === 'terlambat' ? `
                        <button class="action-btn action-btn-call" onclick="callTerlambat(${queue.nomor})">Panggil</button>
                    ` : `
                        <button class="action-btn action-btn-mark" onclick="markTerlambat(${queue.nomor})">Terlambat</button>
                    `}
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function updateStats() {
    const total = allQueues.length;
    const menunggu = allQueues.filter(q => q.status === 'menunggu').length;
    const selesai = allQueues.filter(q => q.status === 'selesai').length;
    const terlambat = allQueues.filter(q => q.status === 'terlambat').length;

    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-menunggu').textContent = menunggu;
    document.getElementById('stat-selesai').textContent = selesai;
    document.getElementById('stat-terlambat').textContent = terlambat;
}

function getStatusLabel(status) {
    const labels = {
        'menunggu': '⏳ Menunggu',
        'dipanggil': '🔊 Dipanggil',
        'selesai': '✅ Selesai',
        'terlambat': '⚠️ Terlambat'
    };
    return labels[status] || 'Unknown';
}

function panggil() {
    fetch('{{ route("admin.panggil") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(!data.success) {
            alert(data.message);
        }
    });
}

function markTerlambat(nomor) {
    if(confirm(`Tandai nomor ${nomor} sebagai terlambat?`)) {
        fetch('{{ route("antrian.terlambat") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nomor: nomor })
        });
    }
}

function callTerlambat(nomor) {
    fetch('{{ route("antrian.panggil-terlambat") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ nomor: nomor })
    });
}

function resetAll() {
    if(confirm('Reset semua antrian? Tindakan ini tidak bisa dibatalkan!')) {
        fetch('{{ route("antrian.reset") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Antrian berhasil direset');
            }
        });
    }
}
</script>
@endsection