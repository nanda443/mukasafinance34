{{-- resources/views/bendahara/approval-pembayaran.blade.php --}}
@extends('layouts.app')

@section('title', 'Approval Pembayaran')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Approval Pembayaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bendahara.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Approval Pembayaran</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Section -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Data Pembayaran</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kelas</label>
                            <select class="form-control" id="filterKelas">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $k)
                                <option value="{{ $k }}">Kelas {{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Jurusan</label>
                            <select class="form-control" id="filterJurusan">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusan as $j)
                                <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="pending">Menunggu</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Jenis Pembayaran</label>
                            <select class="form-control" id="filterJenisPembayaran">
                                <option value="">Semua Jenis</option>
                                @foreach($jenisPembayaran as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary mr-2" id="btnFilter">
                                <i class="fas fa-filter"></i> Terapkan Filter
                            </button>
                            <button class="btn btn-secondary" id="btnReset">
                                <i class="fas fa-refresh"></i> Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="pendingCount">0</h3>
                        <p>Menunggu Approval</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="approvedCount">0</h3>
                        <p>Disetujui</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="rejectedCount">0</h3>
                        <p>Ditolak</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="totalCount">0</h3>
                        <p>Total Pembayaran</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pembayaran</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover w-100" id="approvalTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nama Siswa</th>
                                <th width="10%">NIS</th>
                                <th width="10%">Kelas</th>
                                <th width="15%">Jenis Pembayaran</th>
                                <th width="10%">Nominal</th>
                                <th width="10%">Status</th>
                                <th width="10%">Tenggat Waktu</th>
                                <th width="10%">Hari Tersisa</th>
                                <th width="10%">Bukti</th>
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

<!-- Modal Approve -->
<div class="modal fade" id="modalApprove">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Setujui Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formApprove">
                @csrf
                <input type="hidden" name="pembayaran_id" id="approve_pembayaran_id">
                <div class="modal-body">
                    <p>Anda akan menyetujui pembayaran untuk:</p>
                    <p><strong id="approve_jenis_pembayaran"></strong></p>
                    <div class="form-group">
                        <label>Keterangan (Opsional)</label>
                        <textarea name="keterangan_admin" class="form-control" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="modalReject">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tolak Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formReject">
                @csrf
                <input type="hidden" name="pembayaran_id" id="reject_pembayaran_id">
                <div class="modal-body">
                    <p>Anda akan menolak pembayaran untuk:</p>
                    <p><strong id="reject_jenis_pembayaran"></strong></p>
                    <div class="form-group">
                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_reject" class="form-control" rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Keterangan (Opsional)</label>
                        <textarea name="keterangan_admin" class="form-control" rows="2" placeholder="Tambahkan keterangan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Bukti -->
<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="buktiContent">
                    <!-- Content will be loaded here -->
                    <i class="fas fa-spinner fa-spin fa-3x"></i>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="downloadBuktiBtn" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download"></i> Download / Buka Full
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
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
    var table = $('#approvalTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('bendahara.approval-pembayaran.index') }}",
            type: "GET",
            data: function (d) {
                d.kelas = $('#filterKelas').val();
                d.jurusan = $('#filterJurusan').val();
                d.status = $('#filterStatus').val();
                d.jenis_pembayaran = $('#filterJenisPembayaran').val();
            },
            error: function(xhr, error, thrown) {
                console.log('DataTables error:', xhr, error, thrown);
                if (xhr.status === 500) {
                    Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                }
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            { 
                data: 'nama_siswa', 
                name: 'nama_siswa'
            },
            { 
                data: 'nis', 
                name: 'nis'
            },
            { 
                data: 'kelas_jurusan', 
                name: 'kelas_jurusan'
            },
            { 
                data: 'jenis_pembayaran', 
                name: 'jenis_pembayaran'
            },
            { 
                data: 'nominal', 
                name: 'nominal',
                orderable: false,
                searchable: false
            },
            { 
                data: 'status_badge', 
                name: 'status',
                orderable: false,
                searchable: false
            },
            { 
                data: 'tenggat_badge', 
                name: 'tenggat_waktu',
                orderable: false,
                searchable: false
            },
            { 
                data: 'hari_tersisa', 
                name: 'hari_tersisa',
                orderable: false,
                searchable: false
            },
            { 
                data: 'bukti', 
                name: 'bukti',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/id.json",
            processing: '<i class="fas fa-spinner fa-spin fa-2x"></i> Memuat data...'
        },
        order: [[0, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        drawCallback: function(settings) {
            // Update statistics after table draw
            updateStatistics();
        },
        initComplete: function() {
            console.log('DataTable initialized successfully');
        }
    });

    // Update statistics
    function updateStatistics() {
        // Get data from server for statistics
        $.ajax({
            url: "{{ route('bendahara.approval-pembayaran.index') }}",
            type: "GET",
            data: {
                draw: 1,
                start: 0,
                length: 1, // Just to get the statistics
                kelas: $('#filterKelas').val(),
                jurusan: $('#filterJurusan').val(),
                status: $('#filterStatus').val(),
                jenis_pembayaran: $('#filterJenisPembayaran').val()
            },
            success: function(response) {
                $('#pendingCount').text(response.recordsFiltered || 0);
                $('#approvedCount').text(response.approvedCount || 0);
                $('#rejectedCount').text(response.rejectedCount || 0);
                $('#totalCount').text(response.recordsTotal || 0);
            }
        });
    }

    // Filter functionality
    $('#btnFilter').click(function() {
        table.ajax.reload();
        updateStatistics();
    });

    $('#btnReset').click(function() {
        $('#filterKelas').val('');
        $('#filterJurusan').val('');
        $('#filterStatus').val('');
        $('#filterJenisPembayaran').val('');
        table.ajax.reload();
        updateStatistics();
    });

    // Change events for filters
    $('#filterKelas, #filterJurusan, #filterStatus, #filterJenisPembayaran').change(function() {
        table.ajax.reload();
        updateStatistics();
    });

    // Approve Pembayaran
    $(document).on('click', '.approve-btn', function() {
        var pembayaranId = $(this).data('id');
        var jenisPembayaran = $(this).closest('tr').find('td:eq(4)').text();
        
        $('#approve_pembayaran_id').val(pembayaranId);
        $('#approve_jenis_pembayaran').text(jenisPembayaran);
        $('#modalApprove').modal('show');
    });

    // View Bukti Pembayaran
    $(document).on('click', '.view-bukti-btn', function() {
        var url = $(this).data('url');
        var extension = url.split('.').pop().toLowerCase();
        var contentHtml = '';

        if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
            contentHtml = '<img src="' + url + '" class="img-fluid" style="max-height: 500px;">';
        } else if (extension === 'pdf') {
            contentHtml = '<iframe src="' + url + '" style="width: 100%; height: 500px;" frameborder="0"></iframe>';
        } else {
            contentHtml = '<div class="alert alert-warning">Format file tidak dapat dipreview. Silakan download.</div>';
        }

        $('#buktiContent').html(contentHtml);
        $('#downloadBuktiBtn').attr('href', url);
        $('#modalBukti').modal('show');
    });

    // Reject Pembayaran
    $(document).on('click', '.reject-btn', function() {
        var pembayaranId = $(this).data('id');
        var jenisPembayaran = $(this).closest('tr').find('td:eq(4)').text();
        
        $('#reject_pembayaran_id').val(pembayaranId);
        $('#reject_jenis_pembayaran').text(jenisPembayaran);
        $('#modalReject').modal('show');
    });

    // Form Approve
    $('#formApprove').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var pembayaranId = $('#approve_pembayaran_id').val();
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: "{{ url('bendahara/approval-pembayaran') }}/" + pembayaranId + "/approve",
            method: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#modalApprove').modal('hide');
                    table.ajax.reload(null, false);
                    updateStatistics();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    submitBtn.prop('disabled', false).html(originalText);
                    Swal.fire('Error', response.message || 'Gagal menyetujui pembayaran', 'error');
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                let errorMessage = 'Terjadi kesalahan saat menyetujui pembayaran';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = '';
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessage += value + '<br>';
                    });
                }
                
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    });

    // Form Reject
    $('#formReject').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var pembayaranId = $('#reject_pembayaran_id').val();
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: "{{ url('bendahara/approval-pembayaran') }}/" + pembayaranId + "/reject",
            method: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#modalReject').modal('hide');
                    table.ajax.reload(null, false);
                    updateStatistics();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    submitBtn.prop('disabled', false).html(originalText);
                    Swal.fire('Error', response.message || 'Gagal menolak pembayaran', 'error');
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                let errorMessage = 'Terjadi kesalahan saat menolak pembayaran';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = '';
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessage += value + '<br>';
                    });
                }
                
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    });

    // Reset modal forms when closed
    $('#modalApprove, #modalReject').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('button[type="submit"]').prop('disabled', false).html(function() {
            return $(this).hasClass('btn-success') ? 'Setujui' : 'Tolak';
        });
    });

    // Initial statistics load
    updateStatistics();
});
</script>
@endsection