@extends('layouts.master')

@section('title', 'Keranjang')

@section('content')
    <div class="container-fluid bg-dark py-5 mb-5">
        <div class="container py-5 text-center">
            <h1 class="display-4 text-white mb-3">Keranjang Belanja</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="/" class="text-white">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Keranjang</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (empty($cart))
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-cart-x display-1 text-muted"></i>
                </div>
                <h3 class="fw-bold mb-3">Keranjang Anda Kosong</h3>
                <p class="text-muted mb-4">Mulai berbelanja dan temukan produk menarik untuk Anda</p>
                <a href="/products" class="btn btn-primary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Lanjutkan Belanja
                </a>
            </div>
        @else
            <div class="card mb-4">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th>Subtotal</th>
                                <th class="pe-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $subtotal = 0;
                            @endphp

                            @foreach ($cart as $item)
                                @php
                                    $itemTotal = $item['price'] * $item['qty'];
                                    $subtotal += $itemTotal;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('img_item_upload/' . $item['image']) }}"
                                                class="img-thumbnail me-3"
                                                style="width: 80px; height: 80px; object-fit: cover;"
                                                alt="{{ $item['name'] }}"
                                                onerror="this.onerror=null;this.src='{{ $item['image'] }}';">
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $item['name'] }}</h6>
                                                <small class="text-muted">SKU: {{ $item['id'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span>Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex justify-content-center">
                                            <div class="input-group" style="width: 120px;">
                                                <button class="btn btn-outline-secondary"
                                                    onclick="updateQuantity({{ $item['id'] }}, -1)">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="text" class="form-control text-center"
                                                    id="qty-{{ $item['id'] }}" value="{{ $item['qty'] }}" readonly>
                                                <button class="btn btn-outline-secondary"
                                                    onclick="updateQuantity({{ $item['id'] }}, 1)">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span
                                            class="fw-bold">Rp{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="pe-4 align-middle">
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="if(confirm('Apakah Anda yakin ingin menghapus item ini?')) { removeItemFromCart({{ $item['id'] }}) }">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Kupon Diskon</h5>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Masukkan kode kupon">
                                <button class="btn btn-primary" type="button">Gunakan</button>
                            </div>
                            <a href="/products" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-1"></i> Lanjutkan Belanja
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Ringkasan Belanja</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>PPN (10%)</span>
                                <span>Rp{{ number_format($subtotal * 0.1, 0, ',', '.') }}</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <h5 class="mb-0">Total</h5>
                                <h5 class="mb-0 text-primary">Rp{{ number_format($subtotal * 1.1, 0, ',', '.') }}</h5>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('checkout') }}" class="btn btn-primary py-3">
                                    <i class="bi bi-credit-card me-2"></i> Lanjut ke Pembayaran
                                </a>
                                <a href="{{ route('cart.clear') }}" class="btn btn-outline-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">
                                    <i class="bi bi-trash me-2"></i> Kosongkan Keranjang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        function updateQuantity(itemId, change) {
            var qtyInput = document.getElementById('qty-' + itemId);
            var currentQty = parseInt(qtyInput.value);
            var newQty = currentQty + change;

            // Jika jumlah kurang dari atau sama dengan 0, hapus item
            if (newQty <= 0) {
                if (confirm("Apakah Anda yakin ingin menghapus item ini?")) {
                    removeItemFromCart(itemId);
                }
                return;
            }

            // Kirim permintaan AJAX untuk memperbarui jumlah
            fetch("{{ route('cart.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        itemId: itemId,
                        qty: newQty
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        qtyInput.value = newQty; // Update jumlah di input
                        location.reload(); // Reload halaman untuk memperbarui total dan subtotal
                    } else {
                        alert('Gagal memperbarui keranjang');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }

        // Fungsi untuk menghapus item dari keranjang
        function removeItemFromCart(itemId) {
            fetch("{{ route('cart.remove') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        itemId: itemId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload halaman untuk memperbarui keranjang
                    } else {
                        alert('Gagal menghapus item');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus item');
                });
        }
    </script>


@endsection
