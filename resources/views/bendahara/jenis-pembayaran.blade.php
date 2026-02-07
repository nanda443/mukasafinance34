@extends('layouts.app')

@section('title', 'Jenis Pembayaran')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Jenis Pembayaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bendahara.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Jenis Pembayaran</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Info Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalJenisAktif ?? 0 }}</h3>
                        <p>Jenis Pembayaran Aktif</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalPembayaran ?? 0 }}</h3>
                        <p>Total Pembayaran Disetujui</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</h3>
                        <p>Total Pemasukan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Jenis Pembayaran -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Jenis Pembayaran</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover w-100" id="jenisPembayaranTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama Pembayaran</th>
                                <th width="15%">Nominal</th>
                                <th width="25%">Keterangan</th>
                                <th width="10%">Status</th>
                                <th width="15%">Total Pembayaran</th>
                                <th width="15%">Total Pemasukan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan di-load via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Edit Jenis Pembayaran -->
<div class="modal fade" id="editJenisModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jenis Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editJenisForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pembayaran *</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nominal *</label>
                        <input type="number" name="nominal" id="edit_nominal" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Setup CSRF token untuk semua AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    var table = $('#jenisPembayaranTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('bendahara.jenis-pembayaran.index') }}",
            type: "GET",
            error: function(xhr, error, thrown) {
                console.log('DataTables error:', xhr, error, thrown);
                if (xhr.status === 419) {
                    alert('Session expired. Please refresh the page.');
                    location.reload();
                } else if (xhr.status === 500) {
                    alert('Terjadi kesalahan server. Silakan refresh halaman.');
                }
            }
        },
        columns: [
            {
                data: null,
                name: 'no',
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { 
                data: 'nama', 
                name: 'nama'
            },
            { 
                data: 'nominal_formatted', 
                name: 'nominal',
                orderable: false,
                searchable: false
            },
            { 
                data: 'keterangan', 
                name: 'keterangan',
                defaultContent: '-',
                render: function(data, type, row) {
                    return data ? data : '-';
                }
            },
            { 
                data: 'status_badge', 
                name: 'status',
                orderable: false,
                searchable: false
            },
            { 
                data: 'total_pembayaran', 
                name: 'total_pembayaran',
                orderable: false,
                searchable: false
            },
            { 
                data: 'total_pemasukan', 
                name: 'total_pemasukan',
                orderable: false,
                searchable: false
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/id.json"
        },
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    });

    // Edit jenis pembayaran - Load data
    $(document).on('click', '.edit-jenis', function() {
        var id = $(this).data('id');
        
        var url = "{{ route('bendahara.jenis-pembayaran.edit', ':id') }}";
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    var jenis = response.jenis;
                    
                    // Isi form dengan data yang ada
                    $('#edit_id').val(jenis.id);
                    $('#edit_nama').val(jenis.nama);
                    $('#edit_nominal').val(jenis.nominal);
                    $('#edit_keterangan').val(jenis.keterangan);
                    $('#edit_status').val(jenis.status ? '1' : '0');
                    
                    $('#editJenisModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Gagal memuat data jenis pembayaran'
                    });
                }
            },
            error: function(xhr) {
                console.error('Error loading jenis pembayaran:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memuat data jenis pembayaran'
                });
            }
        });
    });

    // Edit jenis pembayaran - Submit form
    $('#editJenisForm').submit(function(e) {
        e.preventDefault();
        
        var id = $('#edit_id').val();
        var formData = $(this).serialize();
        
        var url = "{{ route('bendahara.jenis-pembayaran.update', ':id') }}";
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengupdate...');
            },
            success: function(response) {
                if (response.success) {
                    $('#editJenisModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Terjadi kesalahan'
                    });
                }
            },
            error: function(xhr) {
                var errorMessage = 'Terjadi kesalahan saat mengupdate jenis pembayaran';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = '';
                    for (var key in errors) {
                        errorMessage += errors[key][0] + '\n';
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save"></i> Update');
            }
        });
    });

    // Reset form ketika modal ditutup
    $('#editJenisModal').on('hidden.bs.modal', function() {
        $('#editJenisForm')[0].reset();
    });

    // Delete jenis pembayaran
    $(document).on('click', '.delete-jenis', function() {
        var id = $(this).data('id');
        var nama = $(this).closest('tr').find('td:eq(1)').text();
        
        Swal.fire({
            title: 'Hapus Jenis Pembayaran?',
            html: `Anda akan menghapus: <strong>${nama}</strong><br><br>
                   <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Data yang sudah memiliki transaksi tidak dapat dihapus!</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "{{ route('bendahara.jenis-pembayaran.destroy', ':id') }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    method: 'DELETE',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Sedang menghapus data',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Gagal menghapus data'
                            });
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'Terjadi kesalahan saat menghapus data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection