@extends('layouts.customer')

@section('title', 'POS - Pemesanan')

@section('content')
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
                            alert('Pembayaran berhasil! Pesanan Anda telah dicatat.');
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