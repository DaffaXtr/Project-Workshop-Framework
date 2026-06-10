@extends('layouts.login')

@section('title', 'Papan Antrian Real-Time')

@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row flex-grow-1 m-0 justify-content-center">
                <div class="col-xl-10 col-lg-11 mx-auto">
                    <div class="auth-form-light p-4 p-md-5 rounded shadow">
                        
                        {{-- HEADER PAPAN --}}
                        <div class="text-center mb-4">
                            <h2 class="text-primary font-weight-bold mb-1">
                                <i class="mdi mdi-television me-2"></i> Papan Antrian
                            </h2>
                            <h6 class="font-weight-light text-muted">Informasi panggillan antrian pasien secara langsung (Real-Time).</h6>
                        </div>

                        <div class="row">
                            {{-- NOMOR YANG SEDANG DIPANGGIL --}}
                            <div class="col-md-5 mb-4">
                                <div class="card bg-light border-0 text-center py-4 px-3" style="border-radius: 12px; min-height: 380px; display: flex; flex-direction: column; justify-content: center;">
                                    <h4 class="text-primary font-weight-bold mb-2">Nomor Antrian</h4>
                                    <div class="nomor-besar my-2" id="nomor" style="font-size: 84px; font-weight: 800; color: #b66dff; line-height: 1; letter-spacing: -2px;">-</div>
                                    <div class="nama-besar text-dark font-weight-bold h3 my-3" id="nama">-</div>
                                </div>
                            </div>

                            {{-- LIST ANTRIAN REALTIME --}}
                            <div class="col-md-7 mb-4">
                                <div class="card border-0" style="border-radius: 12px;">
                                    <h4 class="text-primary font-weight-bold mb-3">Daftar Antrian</h4>
                                    <div class="queue-items overflow-auto pe-1" id="queueItems" style="max-height: 350px;">
                                        <div class="empty-state text-center py-5 text-muted">
                                            <i class="mdi mdi-format-list-bulleted h1 d-block text-muted mb-2"></i>
                                            <p class="mb-0">Belum ada antrian</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Audio elemen tersembunyi --}}
<audio id="audio" src="/sounds/dingdong.mp3"></audio>

{{-- Tambahan CSS helper pendukung class dinamis dari JavaScript --}}
<style>
    .queue-item {
        display: flex;
        align-items: center;
        padding: 14px;
        margin-bottom: 10px;
        background: #f9fafb;
        border-radius: 10px;
        border-left: 4px solid #d1d5db;
        transition: all 0.3s;
    }
    .queue-item.menunggu {
        border-left-color: #b66dff;
        background: #f5ebff;
    }
    .queue-item.dipanggil {
        border-left-color: #ffb64d;
        background: #fff8ee;
        box-shadow: 0 0 12px rgba(255, 182, 77, 0.2);
        transform: scale(1.01);
    }
    .queue-item.selesai {
        border-left-color: #1bcfb4;
        background: #e8faf7;
        opacity: 0.6;
    }
    .queue-item.terlambat {
        border-left-color: #fe7c96;
        background: #ffeef1;
    }
    .queue-nomor {
        font-size: 26px;
        font-weight: 800;
        min-width: 55px;
        text-align: center;
    }
    .queue-item.menunggu .queue-nomor { color: #b66dff; }
    .queue-item.dipanggil .queue-nomor { color: #ffb64d; }
    .queue-item.selesai .queue-nomor { color: #1bcfb4; }
    .queue-item.terlambat .queue-nomor { color: #fe7c96; }
    
    .queue-info { flex-grow: 1; margin-left: 14px; }
    .queue-nama { font-size: 15px; font-weight: 600; color: #1f2937; }
    .queue-poli { font-size: 12px; color: #6b7280; margin-top: 2px; }
    
    .queue-status {
        font-size: 11px;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 16px;
        text-align: center;
        min-width: 75px;
    }
    .status-menunggu { background: #e8daff; color: #6c25be; }
    .status-dipanggil { background: #ffeacf; color: #a16411; }
    .status-selesai { background: #c9f7f1; color: #0f7a6a; }
    .status-terlambat { background: #ffcfd7; color: #b3213c; }
</style>

<script>
const source = new EventSource('{{ route("sse.antrian") }}');

let lastCalled = null;
let allQueues = [];

source.addEventListener('queue-update', function(event) {
    const result = JSON.parse(event.data);

    // Update list antrian
    if(result.queues) {
        allQueues = result.queues;
        updateQueueList();
    }

    if(result.dipanggil) {
        const nomor = result.dipanggil.nomor;
        const nama = result.dipanggil.nama;

        document.getElementById('nomor').innerHTML = nomor;
        document.getElementById('nama').innerHTML = nama;

        // Hindari suara berulang
        if(lastCalled != nomor) {
            lastCalled = nomor;
            playSound(nomor, nama);
        }
    }
});

function updateQueueList() {
    const container = document.getElementById('queueItems');
    
    if(allQueues.length === 0) {
        container.innerHTML = `
            <div class="empty-state text-center py-5 text-muted">
                <i class="mdi mdi-format-list-bulleted h1 d-block text-muted mb-2"></i>
                <p class="mb-0">Belum ada antrian</p>
            </div>`;
        return;
    }

    let html = '';

    allQueues.forEach(queue => {
        const statusClass = queue.status || 'menunggu';
        const statusLabel = getStatusLabel(statusClass);

        html += `
            <div class="queue-item ${statusClass}">
                <div class="queue-nomor">${queue.nomor}</div>
                <div class="queue-info">
                    <div class="queue-nama">${queue.nama}</div>
                    <div class="queue-poli">${queue.poli}</div>
                </div>
                <div class="queue-status status-${statusClass}">
                    ${statusLabel}
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
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

function playSound(nomor, nama) {
    const audio = document.getElementById('audio');

    // Ambil poli dari queue data
    let poli = '-';
    const currentQueue = allQueues.find(q => q.nomor == nomor);
    if(currentQueue) {
        poli = currentQueue.poli;
    }

    // Buat pesan dengan pengejaan poli
    const poliText = ejakanPoli(poli);
    
    const pesan = new SpeechSynthesisUtterance(
        `Nomor antrian ${nomor}. ${nama}. Silahkan masuk. ${poliText}`
    );

    // bahasa indonesia
    pesan.lang = 'id-ID';

    // pengaturan suara
    pesan.rate = 0.9;
    pesan.pitch = 1;
    pesan.volume = 1;

    // ambil voice indonesia
    const voices = speechSynthesis.getVoices();
    const indoVoice = voices.find(voice => voice.lang === 'id-ID');

    // set voice jika ada
    if (indoVoice) {
        pesan.voice = indoVoice;
    }

    // reset audio
    audio.pause();
    audio.currentTime = 0;

    // play ting tong
    audio.play();

    // setelah ting tong selesai
    audio.onended = function() {
        // hentikan speech sebelumnya
        speechSynthesis.cancel();
        // mulai bicara
        speechSynthesis.speak(pesan);
    };
}

function ejakanPoli(poli) {
    // Mapping untuk pengejaan poli
    const poliMap = {
        'Poli Umum': 'Poli Umum',
        'Poli Gigi': 'Poli Gigi',
        'Poli Anak': 'Poli Anak',
        'Poli Mata': 'Poli Mata',
        'Poli THT': 'Poli Te Ha Te'
    };
    
    return poliMap[poli] || poli;
}
</script>
@endsection