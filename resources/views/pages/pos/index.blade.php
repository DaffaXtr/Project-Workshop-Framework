@extends('layouts.customer')

@section('title', 'POS - Pemesanan')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Sistem Pemesanan</h4>
            <a href="{{ route('receipt.history') }}" class="btn btn-info btn-sm">
                Riwayat Pesanan
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pemesanan</h5>
                
<div class="form-group mb-3">
    <label for="vendor">Vendor</label>
    <select id="vendor" class="form-control" onchange="loadMenu(this.value)">
        <option value="">-- Pilih Vendor --</option>
        @foreach($vendors as $vendor)
            <option value="{{ $vendor->idvendor }}">{{ $vendor->nama_vendor }}</option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label for="menu">Menu</label>
    <select id="menu" class="form-control">
        <option value="">-- Pilih Menu --</option>
    </select>
</div>

<div class="form-group mb-3">
    <label for="qty">Jumlah</label>
    <input type="number" id="qty" class="form-control" value="1" min="1">
</div>

<button class="btn btn-primary" onclick="addToCart()">Tambah ke Keranjang</button>

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Keranjang</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                    </tbody>
                </table>
                <h6 class="mt-3">Total: <strong id="totalAmount">Rp 0</strong></h6>
                <button class="btn btn-success w-100 mt-3" onclick="checkout()" id="checkoutBtn" disabled>Bayar</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL STRUK ===================== -->
<div class="modal fade" id="ReceiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-receipt"></i> Struk Pembayaran</h5>
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
                        Transaksi: <span id="receiptTransactionId"></span>
                    </p>

                    <p style="margin: 0; font-size: 11px; color: #27ae60;">
                        <strong>✓ PEMBAYARAN BERHASIL</strong>
                    </p>

                    <p style="margin: 10px 0 0 0; font-size: 10px;">
                        === TERIMA KASIH ===
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printReceipt()"><i class="fas fa-print"></i> Cetak</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Print Area -->
<div id="printArea" style="display:none;"></div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
let cart = [];

function loadMenu(vendorId) {
    if (!vendorId) {
        document.getElementById('menu').innerHTML = '<option value="">-- Pilih Menu --</option>';
        return;
    }

    fetch('/menu/' + vendorId)
        .then(res => res.json())
        .then(data => {
            let menuSelect = document.getElementById('menu');
            menuSelect.innerHTML = '<option value="">-- Pilih Menu --</option>';
            data.forEach(m => {
                menuSelect.innerHTML += `<option value="${m.idmenu}" data-harga="${m.harga}" data-nama="${m.nama_menu}">${m.nama_menu} (Rp ${new Intl.NumberFormat('id-ID').format(m.harga)})</option>`;
            });
        })
        .catch(err => {
            alert('Error memuat menu: ' + err);
            console.error(err);
        });
}

function addToCart() {
    let menuSelect = document.getElementById('menu');
    let qtyInput = document.getElementById('qty');

    if (!menuSelect.value) {
        alert('Pilih menu terlebih dahulu');
        return;
    }

    let selected = menuSelect.options[menuSelect.selectedIndex];
    let qty = parseInt(qtyInput.value) || 1;

    let existingItem = cart.find(item => item.idmenu == selected.value);
    
    if (existingItem) {
        existingItem.qty += qty;
    } else {
        cart.push({
            idmenu: selected.value,
            harga: parseInt(selected.dataset.harga),
            nama_menu: selected.dataset.nama,
            qty: qty
        });
    }

    qtyInput.value = '1';
    updateCartDisplay();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

function updateCartDisplay() {
    let cartBody = document.getElementById('cartBody');
    let totalAmount = document.getElementById('totalAmount');
    let checkoutBtn = document.getElementById('checkoutBtn');

    cartBody.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        let subtotal = item.harga * item.qty;
        total += subtotal;

        cartBody.innerHTML += `
            <tr>
                <td>${item.nama_menu}</td>
                <td>Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</td>
                <td>${item.qty}</td>
                <td>Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
                <td><button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">Hapus</button></td>
            </tr>
        `;
    });

    totalAmount.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    checkoutBtn.disabled = cart.length === 0;
}

function getCsrfToken() {
    // Coba ambil dari meta tag
    let token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        return token.content;
    }
    // Coba ambil dari cookie jika meta tag tidak ada
    let cookies = document.cookie.split(';');
    for (let cookie of cookies) {
        let [name, value] = cookie.trim().split('=');
        if (name === 'XSRF-TOKEN') {
            return decodeURIComponent(value);
        }
    }
    return '';
}

function displayReceipt(data) {
    const now = new Date();
    
    // Set date and time
    document.getElementById('receiptDate').textContent = now.toLocaleDateString('id-ID');
    document.getElementById('receiptTime').textContent = now.toLocaleTimeString('id-ID');
    
    // Set pesanan ID
    document.getElementById('receiptPesananId').textContent = data.pesanan_id;
    
    // Set transaction ID
    document.getElementById('receiptTransactionId').textContent = data.transaction_id;
    
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
        transaction_id: data.transaction_id,
        items: data.items,
        total: total,
        date: now.toLocaleDateString('id-ID'),
        time: now.toLocaleTimeString('id-ID')
    };
    
    // SAVE TO LOCALSTORAGE - Track order history
    saveOrderToHistory(data.pesanan_id);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('ReceiptModal'));
    modal.show();
}

// Function untuk save pesanan ke localStorage
function saveOrderToHistory(pesananId) {
    let orderHistory = JSON.parse(localStorage.getItem('orderHistory')) || [];
    
    // Jika pesanan belum ada di history, tambahkan
    if (!orderHistory.includes(pesananId)) {
        orderHistory.unshift(pesananId); // Add to beginning
        
        // Limit history to 50 items
        if (orderHistory.length > 50) {
            orderHistory = orderHistory.slice(0, 50);
        }
        
        localStorage.setItem('orderHistory', JSON.stringify(orderHistory));
        console.log('Order saved to history:', pesananId);
    }
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
                    <strong>✓ PEMBAYARAN BERHASIL</strong>
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

function checkout() {
    console.log('Checkout clicked, cart:', cart);
    
    if (cart.length === 0) {
        alert('Keranjang kosong. Tambahkan menu terlebih dahulu.');
        return;
    }

    console.log('Sending checkout request with items:', cart);

    fetch('/checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({items: cart})
    })
    .then(res => {
        console.log('Response status:', res.status);
        if (!res.ok) {
            return res.json().then(data => {
                throw new Error(data.message || 'HTTP Error ' + res.status);
            });
        }
        return res.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success && data.snap_token) {
            console.log('Snap token received, opening payment modal...');
            const pesananId = data.pesanan_id;
            
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    
                    // Update status di backend
                    fetch('/payment/update-status/' + pesananId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            transaction_id: result.transaction_id
                        })
                    })
                    .then(res => res.json())
                    .then(updateResult => {
                        if (updateResult.status) {
                            console.log('Database updated successfully');
                            
                            // Tampilkan struk
                            displayReceipt({
                                pesanan_id: pesananId,
                                transaction_id: result.transaction_id,
                                items: cart
                            });
                            
                            cart = [];
                            updateCartDisplay();
                        }
                    })
                    .catch(err => {
                        console.error('Update status error:', err);
                        alert('Pembayaran berhasil namun ada error saat update data. Silahkan hubungi admin.');
                    });
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    alert('Pembayaran sedang diproses. Silahkan tunggu konfirmasi dari bank.');
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal. Silahkan coba lagi.');
                },
                onClose: function() {
                    console.log('Payment modal closed');
                    alert('Anda menutup popup pembayaran. Pembayaran belum selesai.');
                }
            });
        } else {
            console.error('Invalid response:', data);
            alert('Error: ' + (data.message || 'Tidak bisa mendapat snap token'));
        }
    })
    .catch(err => {
        console.error('Checkout error:', err);
        alert('Error checkout: ' + err.message);
    });
}
</script>
@endsection