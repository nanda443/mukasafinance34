{{-- resources/views/bendahara/show-pembayaran.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Pembayaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <!-- PERBAIKAN: Gunakan route yang benar -->
                    <li class="breadcrumb-item"><a href="{{ route('bendahara.approval-pembayaran.index') }}">Approval Pembayaran</a></li>
                    <li class="breadcrumb-item active">Detail Pembayaran</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Informasi Pembayaran -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>ID Pembayaran</strong></td>
                                <td>#{{ $pembayaran->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Siswa</strong></td>
                                <td>{{ $pembayaran->user->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIS</strong></td>
                                <td>{{ $pembayaran->user->nis ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kelas</strong></td>
                                <td>{{ $pembayaran->user->kelas ?? '-' }} {{ $pembayaran->user->jurusan ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Pembayaran</strong></td>
                                <td>{{ $pembayaran->jenisPembayaran->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nominal</strong></td>
                                <td class="font-weight-bold text-success">
                                    Rp {{ number_format($pembayaran->jenisPembayaran->nominal ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Status & Aksi -->
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Status Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if($pembayaran->status == 'approved')
                                <span class="badge badge-success badge-lg p-2" style="font-size: 1.2rem;">
                                    <i class="fas fa-check-circle"></i> DISETUJUI
                                </span>
                            @elseif($pembayaran->status == 'rejected')
                                <span class="badge badge-danger badge-lg p-2" style="font-size: 1.2rem;">
                                    <i class="fas fa-times-circle"></i> DITOLAK
                                </span>
                            @else
                                <span class="badge badge-warning badge-lg p-2" style="font-size: 1.2rem;">
                                    <i class="fas fa-clock"></i> MENUNGGU
                                </span>
                            @endif
                        </div>

                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Tanggal Dibuat</strong></td>
                                <td>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>
                                    @if($pembayaran->status == 'approved')
                                        <span class="badge badge-success">DISETUJUI</span>
                                    @elseif($pembayaran->status == 'rejected')
                                        <span class="badge badge-danger">DITOLAK</span>
                                    @else
                                        <span class="badge badge-warning">MENUNGGU</span>
                                    @endif
                                </td>
                            </tr>
                            @if($pembayaran->tenggat_waktu)
                            <tr>
                                <td><strong>Tenggat Waktu</strong></td>
                                <td>
                                    {{ \Carbon\Carbon::parse($pembayaran->tenggat_waktu)->format('d/m/Y') }}
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $tenggat = \Carbon\Carbon::parse($pembayaran->tenggat_waktu);
                                    @endphp
                                    @if($tenggat->lt($now))
                                        <span class="badge badge-danger ml-2">TERLAMBAT</span>
                                    @elseif($tenggat->diffInDays($now) <= 3)
                                        <span class="badge badge-warning ml-2">MENDEKATI</span>
                                    @else
                                        <span class="badge badge-success ml-2">AMAN</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @if($pembayaran->approved_at)
                            <tr>
                                <td><strong>Disetujui Pada</strong></td>
                                <td>{{ \Carbon\Carbon::parse($pembayaran->approved_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Aksi -->
                @if($pembayaran->status == 'pending')
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Tindakan</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="btn-group btn-group-lg" role="group">
                            <button class="btn btn-success btn-approve" data-id="{{ $pembayaran->id }}">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                            <button class="btn btn-danger btn-reject" data-id="{{ $pembayaran->id }}">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Keterangan & Informasi Tambahan -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Tambahan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($pembayaran->alasan_reject)
                            <div class="col-md-6">
                                <div class="callout callout-danger">
                                    <h5>Alasan Penolakan</h5>
                                    <p>{{ $pembayaran->alasan_reject }}</p>
                                </div>
                            </div>
                            @endif

                            @if($pembayaran->keterangan_admin)
                            <div class="col-md-6">
                                <div class="callout callout-info">
                                    <h5>Keterangan Admin</h5>
                                    <p>{{ $pembayaran->keterangan_admin }}</p>
                                </div>
                            </div>
                            @endif

                            @if($pembayaran->keterangan_siswa)
                            <div class="col-md-6">
                                <div class="callout callout-warning">
                                    <h5>Keterangan Siswa</h5>
                                    <p>{{ $pembayaran->keterangan_siswa }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <!-- PERBAIKAN: Gunakan route yang benar -->
                        <a href="{{ route('bendahara.approval-pembayaran.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Approval Pembayaran
                        </a>
                        
                        @if($pembayaran->status == 'pending')
                            <button class="btn btn-success btn-approve" data-id="{{ $pembayaran->id }}">
                                <i class="fas fa-check"></i> Setujui Pembayaran
                            </button>
                            <button class="btn btn-danger btn-reject" data-id="{{ $pembayaran->id }}">
                                <i class="fas fa-times"></i> Tolak Pembayaran
                            </button>
                        @endif
                    </div>
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
                    <p><strong>{{ $pembayaran->jenisPembayaran->nama ?? 'Pembayaran' }}</strong></p>
                    <p><strong>Siswa:</strong> {{ $pembayaran->user->name ?? '-' }}</p>
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
                    <p><strong>{{ $pembayaran->jenisPembayaran->nama ?? 'Pembayaran' }}</strong></p>
                    <p><strong>Siswa:</strong> {{ $pembayaran->user->name ?? '-' }}</p>
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

    // Approve Pembayaran
    $('.btn-approve').click(function() {
        var pembayaranId = $(this).data('id');
        $('#approve_pembayaran_id').val(pembayaranId);
        $('#modalApprove').modal('show');
    });

    // Reject Pembayaran
    $('.btn-reject').click(function() {
        var pembayaranId = $(this).data('id');
        $('#reject_pembayaran_id').val(pembayaranId);
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
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
});
</script>
@endsection