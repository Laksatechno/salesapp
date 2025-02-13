@extends('layouts.app')
@section('header')
    @include('layouts.appHeaderback')
@endsection
@section('content')
<div class="section mt-2">
    <div class="section-heading">
        <h2 class="title">Pengiriman</h2>
    </div>
        <div class="transaction">

            @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
            <div class="transactions">
                <!-- item -->
                @foreach ($shipments as $shipment)
                <div class="item">
                    <div class="product">
                        <div class="product-title">
                            <div class="product-name">
                                <a href="{{ route('shipments.show', $shipment->id) }}">{{ $shipment->sale->invoice_number }}</a>
                                <p> {{ $shipment->sale->customer->name ?? $shipment->sale->users->name }} </p>
                                <p> Dikirim : {{tgl_indo($shipment->delivery_date)}}</p>
                                <p> Status : {{ $shipment->statuses->last()->status ?? 'Belum Ada Status' }} </p>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-info btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M12 17.8c4.034 0 7.686-2.25 9.648-5.8C19.686 8.45 16.034 6.2 12 6.2S4.314 8.45 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6m0 1.2a4 4 0 1 1 0-8a4 4 0 0 1 0 8"/></svg>
                            <span class="d-none d-md-inline">Lihat Detail</span>
                        </a><br>
                        <!-- Tombol untuk membuka modal -->
                        @if (Auth:: user()->role == 'admin' || Auth:: user()->role == 'superadmin' || Auth:: user()->role == 'logistik')
                        {{-- <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal" data-id="{{ $shipment->id }}">
                            Update Status
                        </button> --}}
                        {{-- @if (!$shipment->statuses->last()->status == 'Barang Sudah Sampai') --}}
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#DialogForm{{$shipment->id}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 21q-1.875 0-3.512-.712t-2.85-1.925t-1.925-2.85T3 12t.713-3.512t1.924-2.85t2.85-1.925T12 3q2.05 0 3.888.875T19 6.35V4h2v6h-6V8h2.75q-1.025-1.4-2.525-2.2T12 5Q9.075 5 7.038 7.038T5 12t2.038 4.963T12 19q2.625 0 4.588-1.7T18.9 13h2.05q-.375 3.425-2.937 5.713T12 21m2.8-4.8L11 12.4V7h2v4.6l3.2 3.2z"/></svg>
                            <span class="d-none d-md-inline">Proses Pengiriman</span>
                        </button>
                        {{-- @endif --}}
                                <!-- Dialog  -->
                            <div class="modal fade dialogbox" id="DialogForm{{$shipment->id}}" data-bs-backdrop="static" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Proses Pengiriman dari {{ $shipment->sale->customer->name ?? $shipment->sale->users->name }} ?
                                            </h5>
                                        </div>
                                            <div class="modal-body text-start mb-2">
                                            </div>
                                            <div class="modal-footer">
                                                <div class="btn-inline">
                                                    <button type="button" class="btn btn-text-secondary"
                                                        data-bs-dismiss="modal">BATAL</button>
                                                        <div class="btn-inline">
                                                            @php
                                                                // Ambil status terakhir dari shipment
                                                                $currentStatus = $shipment->statuses->last()->status;
                                                            @endphp
                                                        
                                                            <!-- Tombol akan ditampilkan berdasarkan status terakhir -->
                                                            @switch($currentStatus)
                                                                @case('Pesanan Anda Sudah Diserahkan ke Pihak Logistik')
                                                                    <!-- Jika status adalah "Pesanan Anda Sudah Diserahkan ke Pihak Logistik" -->
                                                                    <button type="button" class="btn btn-text-primary kirim-ekspedisi-btn" data-id="{{ $shipment->id }}">
                                                                        KIRIM EKSPEDISI
                                                                    </button>
                                                                    <button type="button" class="btn btn-text-primary kirim-mandiri-btn" data-id="{{ $shipment->id }}">
                                                                        KIRIM
                                                                    </button>
                                                                    @break
                                                        
                                                                @case('Barang Dikirim Melalui Ekspedisi')
                                                                    <!-- Jika status adalah "Barang Dikirim Melalui Ekspedisi" -->
                                                                    <button type="button" class="btn btn-text-primary sampai-ekspedisi-btn" data-id="{{ $shipment->id }}" 
                                                                            >
                                                                        SAMPAI EKSPEDISI
                                                                    </button>
                                                                    @break
                                                        
                                                                @case('Barang Sudah Diperjalanan')
                                                                    <!-- Jika status adalah "Barang Sudah Diperjalanan" -->
                                                                    <button type="button" class="btn btn-text-primary sampai-btn" data-id="{{ $shipment->id }}" 
                                                                            data-bs-toggle="modal" data-bs-target="#webcamModal">
                                                                        SAMPAI
                                                                    </button>
                                                                    @break
                                                        
                                                                @default
                                                                    <!-- Jika status tidak sesuai dengan kondisi di atas, tidak ada tombol tambahan yang ditampilkan -->
                                                            @endswitch
                                                        </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <!-- * Dialog  -->
                        @endif
                        {{-- <form action="{{ route('shipments.updateStatus', $shipment->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                    
                            <select name="status" class="form-control mb-2">
                                <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                                <option value="Tertunda">Tertunda</option>
                                <option value="Sampai">Sampai</option>
                            </select>
                    
                            <input type="file" name="photo_proof" class="form-control mb-2" accept="image/*">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </form> --}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
</div>

<!-- Modal -->
<div class="modal fade" id="webcamModal" tabindex="-1" aria-labelledby="webcamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="webcamModalLabel">Ambil Foto Bukti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="my_camera"></div> <!-- Elemen untuk menampilkan webcam -->
                <div id="results" style="display:none;">
                    <img id="imageprev" src="" />
                </div>
                <button type="button" class="btn btn-primary" id="takeSnapshotBtn" onclick="take_snapshot()">Ambil Gambar</button>
                <button type="button" class="btn btn-secondary" id="retakeSnapshotBtn" style="display:none;" onclick="retake_snapshot()">Ambil Gambar Ulang</button>
                <button type="button" class="btn btn-secondary" onclick="switchCamera()">Ganti Kamera</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="save_photo()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Update Status -->
{{-- <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Status Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                            <option value="Tertunda">Tertunda</option>
                            <option value="Sampai">Sampai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo_proof" class="form-label">Bukti Foto</label>
                        <input type="file" name="photo_proof" id="photo_proof" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
    let currentFacingMode = 'environment'; // 'environment' untuk kamera belakang, 'user' untuk kamera depan

    // Fungsi untuk menginisialisasi kamera
    function configureCamera(facingMode) {
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90,
            facingMode: facingMode
        });
        Webcam.attach('#my_camera'); // Menghidupkan kamera
    }

    // Fungsi untuk mengganti kamera (depan/belakang)
    function switchCamera() {
        if (currentFacingMode === 'environment') {
            currentFacingMode = 'user'; // Ganti ke kamera depan
        } else {
            currentFacingMode = 'environment'; // Ganti ke kamera belakang
        }
        Webcam.reset(); // Reset kamera sebelumnya
        configureCamera(currentFacingMode); // Inisialisasi kamera baru
    }

    // Fungsi untuk mengambil gambar
    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            // Sembunyikan kamera dan tombol "Ambil Gambar"
            document.getElementById('my_camera').style.display = 'none';
            document.getElementById('takeSnapshotBtn').style.display = 'none';

            // Tampilkan hasil gambar dan tombol "Ambil Gambar Ulang"
            document.getElementById('results').innerHTML = '<img id="imageprev" src="' + data_uri + '"/>';
            document.getElementById('results').style.display = 'block';
            document.getElementById('retakeSnapshotBtn').style.display = 'block';
        });
    }

    // Fungsi untuk mengambil gambar ulang
    function retake_snapshot() {
        // Sembunyikan hasil gambar dan tombol "Ambil Gambar Ulang"
        document.getElementById('results').style.display = 'none';
        document.getElementById('retakeSnapshotBtn').style.display = 'none';

        // Tampilkan kamera dan tombol "Ambil Gambar"
        document.getElementById('my_camera').style.display = 'block';
        document.getElementById('takeSnapshotBtn').style.display = 'block';

        // Inisialisasi ulang kamera
        configureCamera(currentFacingMode);
    }

    // Fungsi untuk menyimpan gambar
    function save_photo() {
        const imageSrc = document.getElementById('imageprev').src;
        const shipmentId = document.querySelector('.sampai-btn').getAttribute('data-id');

        fetch(`/shipment/${shipmentId}/sampai`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ photo_proof: imageSrc })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/shipments';
            }
        });
    }

    // Event listener untuk modal
    document.addEventListener('DOMContentLoaded', function() {
        const webcamModal = document.getElementById('webcamModal');

        // Saat modal ditampilkan, inisialisasi kamera
        webcamModal.addEventListener('show.bs.modal', function () {
            configureCamera(currentFacingMode);
        });

        // Saat modal disembunyikan, matikan kamera
        webcamModal.addEventListener('hide.bs.modal', function () {
            Webcam.reset(); // Mematikan kamera
        });
    });
</script>
<script>
    $(document).ready(function() {

        $('.sampai-ekspedisi-btn').on('click', function() {
            const shipmentId = $(this).data('id');  // Ambil invoice_id dari data atribut


            // Menampilkan SweetAlert dengan input file untuk gambar
            Swal.fire({
                title: 'Pilih Gambar Resi untuk Dikirim',
                html: `
                <div style="text-align: center; max-width: 100%;">
                    <input type="file" id="fileInput" class="swal2-input" accept="image/*" style="width: 100%; padding: 10px; box-sizing: border-box;" />
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const fileInput = document.getElementById('fileInput');
                    const file = fileInput ? fileInput.files[0] : null;

                    // Validasi jika tidak ada file yang dipilih
                    if (!file) {
                        Swal.showValidationMessage('File tidak boleh kosong');
                        return false;  // Mencegah submit jika tidak ada file yang dipilih
                    }

                    // Validasi tipe file (hanya gambar)
                    if (!file.type.startsWith('image/')) {
                        Swal.showValidationMessage('Tolong pilih file gambar yang valid!');
                        return false;  // Mencegah submit jika file bukan gambar
                    }

                    // Jika file valid, lanjutkan untuk mengirimkan file ke server
                    const formData = new FormData();
                    formData.append('photo_proof', file);
                    formData.append('id', shipmentId);  // Kirim invoice_id bersama file

                    // Menggunakan fetch untuk mengirimkan data ke server
                    return fetch(`/shipment/${shipmentId}/sampai`, {
                            method: 'POST',
                            headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                            body: formData
                        }).then(response => {
                        if (!response.ok) {
                            throw new Error('Upload gagal');
                        }
                        return response.json();
                    }).then(data => {
                        // Tindakan setelah upload berhasil menampilkan pesan 4 detik dengan reload ketika klik ok
                        Swal.fire('Berhasil!', 'File gambar telah berhasil dikirim!', 'success');
                        setTimeout(function() {
                            location.reload();
                        })
                    }).catch(error => {
                        // Menangani error jika upload gagal
                        Swal.fire('Gagal', 'Terjadi kesalahan: ' + error.message, 'error');
                    });
                }
            });
        });

        // Ketika modal dibuka
        $('#updateStatusModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Tombol yang memicu modal
            var shipmentId = button.data('id'); // Ambil ID dari atribut data-id

            // Set action form dengan route yang sesuai
            var form = $('#updateStatusForm');
            form.attr('action', '/shipments/' + shipmentId + '/update-status');
        });

        $('.kirim-mandiri-btn').on('click', function() {
            var shipmentId = $(this).data('id');
            console.log(shipmentId);
            Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Ingin memproses pengiriman barang ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, kirim!'
                })
                    .then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna mengkonfirmasi, lakukan AJAX request
                        $.ajax({
                            url: '/jalan/' + shipmentId,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire(
                                        'Berhasil!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload(); // Reload halaman untuk memperbarui status
                                    });
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                                Swal.fire(
                                    'Terjadi kesalahan!',
                                    'Permintaan gagal diproses. Silakan coba lagi.',
                                    'error'
                                );
                            }
                        });
                    }
                });
        });

        $('.kirim-ekspedisi-btn').on('click', function() {
            var shipmentId = $(this).data('id');
            console.log(shipmentId);
            Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Ingin memproses pengiriman barang ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, kirim!'
                })
                    .then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna mengkonfirmasi, lakukan AJAX request
                        $.ajax({
                            url: '/jalanekspedisi/' + shipmentId,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire(
                                        'Berhasil!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload(); // Reload halaman untuk memperbarui status
                                    });
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                                Swal.fire(
                                    'Terjadi kesalahan!',
                                    'Permintaan gagal diproses. Silakan coba lagi.',
                                    'error'
                                );
                            }
                        });
                    }
                });
        });
    });
</script>
@endsection
