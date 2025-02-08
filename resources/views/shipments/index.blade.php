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
                        <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-info btn-sm">Detail</a>
                        <!-- Tombol untuk membuka modal -->
                        @if (Auth:: user()->role == 'admin' || Auth:: user()->role == 'superadmin' || Auth:: user()->role == 'logistik')
                        {{-- <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal" data-id="{{ $shipment->id }}">
                            Update Status
                        </button> --}}
                        {{-- @if (!$shipment->statuses->last()->status == 'Barang Sudah Sampai') --}}
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#DialogForm{{$shipment->id}}">
                            Update Status
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
            {{-- <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Sale</th>
                        <th>Delivery Date</th>
                        <th>Arrival Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shipments as $shipment)
                        <tr>
                            <td>{{ $shipment->sale->invoice_number }}</td>
                            <td>{{ $shipment->delivery_date }}</td>
                            <td>{{ $shipment->arrival_date ?? '-' }}</td>
                            <td>
                                @foreach ($shipment->statuses as $status)
                                    <div>{{ $status->timestamp }}: {{ $status->status }}</div>
                                @endforeach
                            </td>
                            <td>
                                <td>
                                    <a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-info">Lihat Detail</a>
                                    <form action="{{ route('shipments.updateStatus', $shipment->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                
                                        <select name="status" class="form-control mb-2">
                                            <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                                            <option value="Tertunda">Tertunda</option>
                                            <option value="Sampai">Sampai</option>
                                        </select>
                                
                                        <input type="file" name="photo_proof" class="form-control mb-2" accept="image/*">
                                        <button type="submit" class="btn btn-primary">Update Status</button>
                                    </form>
                                </td>
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table> --}}
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
