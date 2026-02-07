{{-- resources/views/siswa/tagihan.blade.php --}}
@extends('layouts.app')

@section('title', 'Tagihan & Pembayaran')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tagihan & Pembayaran</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
        @endif

        <!-- Tagihan Belum Dibayar -->
        @if(count($tagihanBelumDibayar) > 0)
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Tagihan Belum Dibayar
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($tagihanBelumDibayar as $tagihan)
                    <div class="col-md-4">
                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <h3 class="card-title">{{ $tagihan['jenis_pembayaran']->nama }}</h3>
                                <div class="card-tools">
                                    @if($tagihan['status'] == 'pending')
                                        <span class="badge badge-warning">MENUNGGU VERIFIKASI</span>
                                    @else
                                        <span class="badge badge-danger">BELUM BAYAR</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">
                                    <strong>Nominal:</strong><br>
                                    <span class="h4 text-danger">Rp {{ number_format($tagihan['jenis_pembayaran']->nominal, 0, ',', '.') }}</span>
                                </p>
                                <p class="mb-2">
                                    <strong>Kategori:</strong><br>
                                    {{ $tagihan['jenis_pembayaran']->kategori }}
                                </p>

                                @if($tagihan['status'] == 'pending')
                                    <button class="btn btn-secondary btn-block mt-3" disabled>
                                        <i class="fas fa-clock mr-2"></i> Sedang Diproses
                                    </button>
                                @else
                                    <button class="btn btn-primary btn-block mt-3" data-toggle="modal" data-target="#modalBayar" 
                                            data-jenis="{{ $tagihan['jenis_pembayaran']->id }}"
                                            data-nama="{{ $tagihan['jenis_pembayaran']->nama }}"
                                            data-nominal="{{ $tagihan['jenis_pembayaran']->nominal }}">
                                        <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Modal Bayar -->
        <div class="modal fade" id="modalBayar" tabindex="-1" role="dialog" aria-labelledby="modalBayarLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBayarLabel">Bayar Tagihan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('siswa.tagihan.bayar') }}" method="POST" enctype="multipart/form-data" id="formBayar">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="jenis_pembayaran_id" id="jenis_pembayaran_id">
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Silakan transfer sesuai nominal yang tertera lalu upload buktinya di sini.
                            </div>

                            <div class="form-group">
                                <label>Pembayaran Untuk</label>
                                <input type="text" id="display_jenis" class="form-control" readonly style="background-color: #e9ecef; font-weight: bold;">
                            </div>

                            <div class="form-group">
                                <label>Tanggal Bayar</label>
                                <input type="date" name="tanggal_bayar" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="form-group">
                                <label>Bukti Transfer <small class="text-danger">*</small></label>
                                <div class="custom-file">
                                    <input type="file" name="bukti" class="custom-file-input" id="buktiFile" accept=".jpg,.jpeg,.png,.pdf" required>
                                    <label class="custom-file-label" for="buktiFile">Pilih file...</label>
                                </div>
                                <small class="form-text text-muted">Format: JPG, PNG, PDF (Maks: 2MB)</small>
                            </div>

                            <div class="form-group">
                                <label>Keterangan (Opsional)</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Transfer atas nama Budi..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btnSubmit">
                                <i class="fas fa-upload mr-2"></i> Kirim Bukti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pembayaran Saya</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Jenis Pembayaran</th>
                                <th width="10%">Nominal</th>
                                <th width="10%">Tanggal Bayar</th>
                                <th width="12%">Tenggat Waktu</th>
                                <th width="10%">Status</th>
                                <th width="15%">Keterangan</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembayaranSaya as $key => $pembayaran)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $pembayaran->jenisPembayaran->nama }}</td>
                                <td class="font-weight-bold">Rp {{ number_format($pembayaran->jenisPembayaran->nominal, 0, ',', '.') }}</td>
                                <td>{{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($pembayaran->tenggat_waktu)
                                        {!! $pembayaran->tenggat_badge !!}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{!! $pembayaran->status_badge !!}</td>
                                <td>
                                    @if($pembayaran->keterangan)
                                        <span title="{{ $pembayaran->keterangan }}">
                                            {{ Str::limit($pembayaran->keterangan, 30) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('siswa.tagihan.show', $pembayaran->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    Belum ada riwayat pembayaran.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Modal bayar
    $('[data-target="#modalBayar"]').click(function() {
        var jenisId = $(this).data('jenis');
        var jenisNama = $(this).data('nama');
        var nominal = $(this).data('nominal');
        
        $('#jenis_pembayaran_id').val(jenisId);
        $('#display_jenis').val(jenisNama + ' - Rp ' + nominal.toLocaleString('id-ID'));
        $('#btnSubmit').prop('disabled', false);
    });

    // Update nama file saat dipilih
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Validasi form
    $('#formBayar').submit(function() {
        if (!$('#jenis_pembayaran_id').val()) {
            Swal.fire('Error', 'Terjadi kesalahan teknis. Silakan refresh halaman.', 'error');
            return false;
        }
        
        // Disable tombol submit agar tidak double submit
        $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');
        return true;
    });
});
</script>
@endsection