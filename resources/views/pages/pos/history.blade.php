@extends('layouts.customer')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        Riwayat Pesanan Saya
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Alert -->
                    <div id="alertMessage" style="display: none;" class="alert alert-info alert-dismissible fade show" role="alert">
                        Tidak ada riwayat pesanan. Lakukan pemesanan terlebih dahulu.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <!-- List Orders -->
                    <div id="ordersList">
                        <div class="text-center text-muted py-5">
                            <p>Memuat data riwayat pesanan...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-3">
                <a href="{{ route('customer.index') }}" class="btn btn-secondary">
                    Kembali ke POS
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL LIHAT STRUK -->
<div class="modal fade" id="ReceiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Struk Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="font-family: monospace; font-size: 12px; text-align: center;">
                <div style="border: 2px dotted #000; padding: 15px; background: white;">
                    <h6 style="margin: 0 0 10px 0;"><strong>STRUK PEMBAYARAN</strong></h6>

                    <!-- QR CODE -->
                    <div style="margin: 10px 0;">
                        <img 
                            id="receiptQrCode"
                            alt="QR Code"
                            style="width: 100px; height: 100px;"
                            onerror="this.style.display='none'"
                        >
                    </div>

                    <p style="margin: 5px 0; font-size: 11px;">
                        <span id="receiptDate"></span><br>
                        <span id="receiptTime"></span>
                    </p>

                    <p style="margin: 0 0 10px 0; font-size: 11px;">
                        ID Pesanan: <strong id="receiptPesananId"></strong>
                    </p>

                    <table style="width: 100%; border-collapse: collapse;">
                        <tr style="border-bottom: 1px dotted #000; padding: 5px 0;">
                            <td colspan="2"></td>
                        </tr>
                        <tbody id="receiptItems">
                            <!-- Items akan ditampilkan di sini -->
                        </tbody>
                        <tr style="border-top: 1px dotted #000;">
                            <td><strong>TOTAL</strong></td>
                            <td style="text-align: right;">
                                <strong id="receiptTotal"></strong>
                            </td>
                        </tr>
                    </table>

                    <p style="margin: 10px 0 5px 0; font-size: 10px; color: #666;">
                        Transaksi: <span id="receiptTransactionId">-</span>
                    </p>

                    <p style="margin: 0; font-size: 11px; color: #27ae60;" id="receiptStatus">
                        <strong>✓ PEMBAYARAN BERHASIL</strong>
                    </p>

                    <p style="margin: 10px 0 0 0; font-size: 10px;">
                        === TERIMA KASIH ===
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printReceipt()">Cetak</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Print Area -->
<div id="printArea" style="display:none;"></div>

<script>
// Load order history dari localStorage
function loadOrderHistory() {
    const orderIds = JSON.parse(localStorage.getItem('orderHistory')) || [];
    
    if (orderIds.length === 0) {
        document.getElementById('ordersList').innerHTML = `
            <div class="text-center text-muted py-5">
                <p>Tidak ada riwayat pesanan.</p>
                <a href="{{ route('customer.index') }}" class="btn btn-primary btn-sm">
                    Buat Pesanan
                </a>
            </div>
        `;
        return;
    }

    // Fetch data pesanan dari server
    fetch('/orders-history', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
            pesanan_ids: orderIds
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.orders.length > 0) {
            displayOrdersList(data.orders);
        } else {
            document.getElementById('ordersList').innerHTML = `
                <div class="text-center text-muted py-5">
                    <p>Tidak ada riwayat pesanan.</p>
                </div>
            `;
        }
    })
    .catch(err => {
        console.error('Error loading history:', err);
        document.getElementById('ordersList').innerHTML = `
            <div class="alert alert-danger">
                Error memuat riwayat pesanan
            </div>
        `;
    });
}

function displayOrdersList(orders) {
    let html = '';

    orders.forEach(order => {
        const date = new Date(order.timestamp).toLocaleDateString('id-ID');
        const time = new Date(order.timestamp).toLocaleTimeString('id-ID');
        const statusBadge = order.status_bayar == 1 
            ? '<span class="badge bg-success">Lunas</span>'
            : '<span class="badge bg-warning">Pending</span>';

        html += `
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="mb-1">
                                <strong>Pesanan #${order.idpesanan}</strong>
                                ${statusBadge}
                            </h6>
                            <small class="text-muted">
                                ${date} ${time}<br>
                                ${order.nama}
                            </small>
                            <p class="mb-0 mt-2">
                                <strong>Total: Rp ${new Intl.NumberFormat('id-ID').format(order.total)}</strong>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-sm btn-primary" onclick="viewReceipt(${order.idpesanan})">
                                Lihat Struk
                            </button>
                            <button class="btn btn-sm btn-secondary" onclick="printReceiptById(${order.idpesanan})">
                                Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    document.getElementById('ordersList').innerHTML = html;
}

function viewReceipt(pesananId) {
    fetch('/receipt/' + pesananId)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                displayReceipt(data);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Error memuat struk');
        });
}

function displayReceipt(data) {
    const timestamp = new Date(data.timestamp);
    const date = timestamp.toLocaleDateString('id-ID');
    const time = timestamp.toLocaleTimeString('id-ID');
    
    // Set date and time
    document.getElementById('receiptDate').textContent = date;
    document.getElementById('receiptTime').textContent = time;
    
    // Set pesanan ID
    document.getElementById('receiptPesananId').textContent = data.pesanan_id;
    
    // Set transaction ID
    document.getElementById('receiptTransactionId').textContent = data.transaction_id || '-';
    
    // Set status
    const statusHtml = data.status_bayar == 1
        ? '<strong style="color: #27ae60;">✓ PEMBAYARAN BERHASIL</strong>'
        : '<strong style="color: #e74c3c;">⏳ MENUNGGU PEMBAYARAN</strong>';
    document.getElementById('receiptStatus').innerHTML = statusHtml;
    
    // Set QR Code
    document.getElementById('receiptQrCode').src = `/qrcode/${data.pesanan_id}?t=${Date.now()}`;
    
    // Build items HTML
    let itemsHTML = '';
    let total = 0;
    
    data.items.forEach(item => {
        const subtotal = item.harga * item.qty;
        total += subtotal;
        
        itemsHTML += `
            <tr>
                <td>${item.nama_menu}</td>
                <td style="text-align: right;">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 10px; color: #666;">
                    ${item.qty}x @ Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}
                </td>
            </tr>
        `;
    });
    
    document.getElementById('receiptItems').innerHTML = itemsHTML;
    document.getElementById('receiptTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    
    // Store data for print
    window.receiptData = {
        pesanan_id: data.pesanan_id,
        transaction_id: data.transaction_id || '-',
        items: data.items,
        total: total,
        date: date,
        time: time,
        status_bayar: data.status_bayar
    };
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('ReceiptModal'));
    modal.show();
}

function printReceiptById(pesananId) {
    fetch('/receipt/' + pesananId)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.receiptData = {
                    pesanan_id: data.pesanan_id,
                    transaction_id: data.transaction_id || '-',
                    items: data.items,
                    total: data.total,
                    date: new Date(data.timestamp).toLocaleDateString('id-ID'),
                    time: new Date(data.timestamp).toLocaleTimeString('id-ID'),
                    status_bayar: data.status_bayar
                };
                printReceipt();
            }
        })
        .catch(err => console.error('Error:', err));
}

function printReceipt() {
    if (!window.receiptData) return;
    
    const data = window.receiptData;
    
    // Build items HTML for print
    let itemsHTML = '';
    data.items.forEach(item => {
        const subtotal = item.harga * item.qty;
        itemsHTML += `
            <tr>
                <td>${item.nama_menu}</td>
                <td style="text-align: right;">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 10px; color: #666;">
                    ${item.qty}x @ Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}
                </td>
            </tr>
        `;
    });
    
    const qrcodeUrl = `/qrcode/${data.pesanan_id}`;
    
    const printHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Struk Pembayaran - ${data.pesanan_id}</title>
            <style>
                body {
                    font-family: monospace;
                    margin: 0;
                    padding: 20px;
                    background: white;
                }
                .receipt {
                    text-align: center;
                    border: 2px dotted #000;
                    padding: 15px;
                    width: 100%;
                    max-width: 300px;
                    margin: 0 auto;
                }
                table { width: 100%; border-collapse: collapse; }
                @media print { body { margin: 0; padding: 0; } }
            </style>
        </head>
        <body>
            <div class="receipt">
                <h6 style="margin: 0 0 10px 0;"><strong>STRUK PEMBAYARAN</strong></h6>
                
                <div style="margin: 10px 0;">
                    <img src="${qrcodeUrl}" alt="QR Code" style="width: 100px; height: 100px;">
                </div>
                
                <p style="margin: 5px 0; font-size: 11px;">
                    ${data.date}<br>
                    ${data.time}
                </p>
                
                <p style="margin: 0 0 10px 0; font-size: 11px;">
                    ID Pesanan: <strong>${data.pesanan_id}</strong>
                </p>
                
                <table>
                    <tr style="border-bottom: 1px dotted #000;">
                        <td colspan="2"></td>
                    </tr>
                    ${itemsHTML}
                    <tr style="border-top: 1px dotted #000;">
                        <td><strong>TOTAL</strong></td>
                        <td style="text-align: right;">
                            <strong>Rp ${new Intl.NumberFormat('id-ID').format(data.total)}</strong>
                        </td>
                    </tr>
                </table>
                
                <p style="margin: 10px 0 5px 0; font-size: 10px; color: #666;">
                    Transaksi: ${data.transaction_id}
                </p>
                
                <p style="margin: 0; font-size: 11px; color: #27ae60;">
                    <strong>✓ PEMBAYARAN ${data.status_bayar == 1 ? 'BERHASIL' : 'PENDING'}</strong>
                </p>
                
                <p style="margin: 10px 0 0 0; font-size: 10px;">
                    === TERIMA KASIH ===
                </p>
            </div>
            <script>
                window.print();
                window.setTimeout(() => window.close(), 500);
            <\/script>
        </body>
        </html>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printHTML);
    printWindow.document.close();
}

// Load history saat halaman dibuka
document.addEventListener('DOMContentLoaded', function() {
    loadOrderHistory();
});
</script>
@endsection
