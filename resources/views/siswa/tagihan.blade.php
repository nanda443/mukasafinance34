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
        @php
            $tagihanSiapBayar = collect($tagihanBelumDibayar)->filter(function ($tagihan) {
                return $tagihan['status'] === 'belum_bayar';
            });
            $jumlahTagihanSiapBayar = $tagihanSiapBayar->count();
            $totalTagihanBelumBayar = $tagihanSiapBayar->sum(function ($tagihan) {
                return $tagihan['jenis_pembayaran']->nominal;
            });
            $firstTagihanSiapBayar = $tagihanSiapBayar->first();
            $nominalTagihanTerpilih = $firstTagihanSiapBayar ? $firstTagihanSiapBayar['jenis_pembayaran']->nominal : 0;
        @endphp
        <div class="card card-warning student-mobile-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Tagihan Belum Dibayar
                </h3>
            </div>
            <div class="card-body">
                <div class="student-bills-mobile d-md-none student-mobile-only">
                    <div class="student-bills-summary">
                        <div>
                            <small>Total Tagihan Terpilih</small>
                            <h4 id="selected_tagihan_total">Rp {{ number_format($nominalTagihanTerpilih, 0, ',', '.') }}</h4>
                        </div>
                        <span class="badge badge-pill badge-info">{{ $jumlahTagihanSiapBayar }} Pembayaran Aktif</span>
                    </div>

                    <div class="student-bills-count">
                        <i class="fas fa-check-square"></i>
                        Pilih salah satu tagihan untuk dibayar
                    </div>

                    <div class="student-bills-heading">Daftar Invoice</div>

                    @foreach($tagihanBelumDibayar as $tagihan)
                    <div class="student-bill-item {{ $tagihan['status'] == 'pending' ? 'is-pending' : '' }}">
                        <div class="student-bill-top">
                            <div class="student-bill-left">
                                @if($tagihan['status'] == 'pending')
                                    <i class="far fa-square mr-2"></i>
                                @else
                                    <input type="radio"
                                           name="selected_tagihan_mobile"
                                           class="student-bill-selector mr-2"
                                           value="{{ $tagihan['jenis_pembayaran']->id }}"
                                           data-jenis="{{ $tagihan['jenis_pembayaran']->id }}"
                                           data-nama="{{ $tagihan['jenis_pembayaran']->nama }}"
                                           data-nominal="{{ $tagihan['jenis_pembayaran']->nominal }}"
                                           {{ $loop->first ? 'checked' : '' }}>
                                @endif
                                <strong>{{ $tagihan['jenis_pembayaran']->nama }}</strong>
                            </div>
                            <div>
                                @if($tagihan['status'] == 'pending')
                                    <span class="badge badge-warning">Diproses</span>
                                @else
                                    <span class="badge badge-danger">Terlambat</span>
                                @endif
                            </div>
                        </div>

                        <div class="student-bill-meta text-muted">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ now()->translatedFormat('d F Y') }}
                        </div>

                        <div class="student-bill-bottom">
                            <span class="student-bill-amount">Rp {{ number_format($tagihan['jenis_pembayaran']->nominal, 0, ',', '.') }}</span>
                            @if($tagihan['status'] == 'pending')
                                <button class="btn btn-sm btn-light" disabled>Diproses</button>
                            @else
                                <button class="btn btn-sm btn-light border" data-toggle="modal" data-target="#modalBayar"
                                        data-jenis="{{ $tagihan['jenis_pembayaran']->id }}"
                                        data-nama="{{ $tagihan['jenis_pembayaran']->nama }}"
                                        data-nominal="{{ $tagihan['jenis_pembayaran']->nominal }}">
                                    Rincian
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    <div class="student-bills-note">
                        <i class="fas fa-info-circle mr-1"></i>
                        Pembayaran diproses per tagihan, via transfer bank dan upload bukti untuk verifikasi.
                    </div>

                    @if($firstTagihanSiapBayar)
                        <button class="btn btn-primary btn-block student-bills-pay-all" id="btnBayarSelected" data-toggle="modal" data-target="#modalBayar"
                                data-jenis="{{ $firstTagihanSiapBayar['jenis_pembayaran']->id }}"
                                data-nama="{{ $firstTagihanSiapBayar['jenis_pembayaran']->nama }}"
                                data-nominal="{{ $firstTagihanSiapBayar['jenis_pembayaran']->nominal }}">
                            Bayar Sekarang • Rp {{ number_format($nominalTagihanTerpilih, 0, ',', '.') }}
                        </button>
                    @endif
                </div>

                <div class="row d-none d-md-flex">
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
                <div class="modal-content checkout-modal">
                    <div class="modal-header checkout-header">
                        <div>
                            <h5 class="modal-title mb-1" id="modalBayarLabel">Secure Checkout</h5>
                            <small class="text-white-50">Upload bukti transfer untuk verifikasi</small>
                        </div>
                        <span class="badge badge-light">Encrypted</span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('siswa.tagihan.bayar') }}" method="POST" enctype="multipart/form-data" id="formBayar">
                        <div class="modal-body checkout-body">
                            @csrf
                            <input type="hidden" name="jenis_pembayaran_id" id="jenis_pembayaran_id">

                            <div class="checkout-student">
                                <strong class="d-block">{{ Auth::user()->name }}</strong>
                                <small class="text-muted">{{ Auth::user()->kelas }} {{ Auth::user()->jurusan }}</small>
                            </div>

                            <div class="checkout-summary">
                                <div class="checkout-row">
                                    <span>Tagihan</span>
                                    <strong id="checkout_nama">-</strong>
                                </div>
                                <div class="checkout-row checkout-total">
                                    <span>Total Payment</span>
                                    <strong id="checkout_total">Rp 0</strong>
                                </div>
                            </div>

                            <div class="checkout-methods mb-3">
                                <div class="checkout-method active">
                                    <div>
                                        <strong>Bank Transfer</strong>
                                        <small class="d-block text-muted">Sesuai fitur sistem: upload bukti transfer</small>
                                    </div>
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                            </div>

                            <div class="alert alert-info py-2 mb-3">
                                <i class="fas fa-info-circle"></i> Transfer sesuai nominal, lalu upload bukti pembayaran.
                            </div>

                            <div class="checkout-form-box">
                                <div class="form-group mb-2">
                                    <label>Tanggal Bayar</label>
                                    <input type="date" name="tanggal_bayar" class="form-control" required value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="form-group mb-2">
                                    <label>Bukti Transfer <small class="text-danger">*</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="bukti" class="custom-file-input" id="buktiFile" accept=".jpg,.jpeg,.png,.pdf" required>
                                        <label class="custom-file-label" for="buktiFile">Pilih file...</label>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, PNG, PDF (Maks: 2MB)</small>
                                </div>

                                <div class="form-group mb-0">
                                    <label>Keterangan (Opsional)</label>
                                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Transfer atas nama Budi..."></textarea>
                                </div>
                            </div>

                            <input type="hidden" id="display_jenis">
                        </div>
                        <div class="modal-footer checkout-footer">
                            <div class="checkout-footer-total">Total Amount <strong id="checkout_footer_total">Rp 0</strong></div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btnSubmit">
                                Confirm Payment
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
                <div class="table-responsive d-none d-md-block">
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

                <div class="student-mobile-list d-md-none student-mobile-only">
                    @forelse($pembayaranSaya as $pembayaran)
                    <a href="{{ route('siswa.tagihan.show', $pembayaran->id) }}" class="student-mobile-item text-decoration-none">
                        <span class="student-mobile-icon"><i class="fas fa-receipt"></i></span>
                        <div class="student-mobile-main">
                            <strong>{{ $pembayaran->jenisPembayaran->nama }}</strong>
                            <span>
                                {{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d M Y') : 'Belum upload bukti' }}
                                @if($pembayaran->tenggat_waktu)
                                    • Tenggat {{ $pembayaran->tenggat_waktu->format('d M Y') }}
                                @endif
                            </span>
                        </div>
                        <div class="student-mobile-side">
                            Rp {{ number_format($pembayaran->jenisPembayaran->nominal, 0, ',', '.') }}
                            <div>{!! $pembayaran->status_badge !!}</div>
                        </div>
                    </a>
                    @empty
                    <div class="p-3 text-center text-muted small">Belum ada riwayat pembayaran.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
@media (max-width: 767.98px) {
    body.role-siswa .student-mobile-card.card-warning > .card-header {
        background: linear-gradient(145deg, #0a5b7a 0%, #1f7da0 54%, #2f8eb2 100%);
        border-bottom: 0;
        color: #fff;
    }

    body.role-siswa .student-mobile-card.card-warning > .card-header .card-title,
    body.role-siswa .student-mobile-card.card-warning > .card-header .card-title i {
        color: #fff;
    }

    body.role-siswa .student-bills-summary {
        border: 1px solid rgba(14, 116, 144, .18);
        border-radius: 12px;
        background: #f0f9ff;
        padding: .7rem .75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .6rem;
        margin-bottom: .65rem;
    }

    body.role-siswa .student-bills-summary small {
        display: block;
        color: #64748b;
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    body.role-siswa .student-bills-summary h4 {
        margin: .2rem 0 0;
        color: #0369a1;
        font-size: 1.45rem;
        font-weight: 800;
    }

    body.role-siswa .student-bills-count {
        color: #0369a1;
        font-size: .78rem;
        font-weight: 600;
        margin-bottom: .55rem;
    }

    body.role-siswa .student-bills-heading {
        font-size: .73rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #0e7490;
        font-weight: 700;
        margin-bottom: .45rem;
    }

    body.role-siswa .student-bill-item {
        background: #fff;
        border: 1px solid rgba(14, 116, 144, .15);
        border-radius: 12px;
        padding: .62rem .68rem;
        margin-bottom: .5rem;
    }

    body.role-siswa .student-bill-item.is-pending {
        background: #f8fafc;
        border-color: rgba(148, 163, 184, .35);
    }

    body.role-siswa .student-bill-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: .5rem;
        margin-bottom: .25rem;
    }

    body.role-siswa .student-bill-left {
        display: flex;
        align-items: center;
        min-width: 0;
    }

    body.role-siswa .student-bill-selector {
        accent-color: #0284c7;
        width: 15px;
        height: 15px;
        margin-top: 0;
        flex-shrink: 0;
    }

    body.role-siswa .student-bill-left strong {
        font-size: .8rem;
        color: #0f172a;
        line-height: 1.25;
    }

    body.role-siswa .student-bill-meta {
        font-size: .66rem;
        margin-bottom: .35rem;
    }

    body.role-siswa .student-bill-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .5rem;
    }

    body.role-siswa .student-bill-amount {
        color: #0369a1;
        font-size: .95rem;
        font-weight: 700;
    }

    body.role-siswa .student-bill-item .badge-danger {
        background: #0ea5e9;
    }

    body.role-siswa .student-bill-item .badge-warning {
        background: #0c4a6e;
        color: #fff;
    }

    body.role-siswa .student-bill-item .btn-light {
        color: #0369a1;
        border-color: rgba(14, 116, 144, .28) !important;
        background: #f0f9ff;
        font-weight: 700;
    }

    body.role-siswa .student-bills-summary .badge-info {
        background: #0284c7;
        color: #fff;
    }

    body.role-siswa .student-bills-note {
        font-size: .7rem;
        color: #0f4c64;
        background: #f0f9ff;
        border: 1px solid rgba(14, 116, 144, .2);
        border-radius: 10px;
        padding: .55rem .62rem;
        margin: .25rem 0 .6rem;
    }

    body.role-siswa .student-bills-pay-all {
        border-radius: 10px;
        font-size: .76rem;
        font-weight: 700;
        background: linear-gradient(145deg, #0a5b7a 0%, #1f7da0 54%, #2f8eb2 100%);
        border-color: #0a5b7a;
        box-shadow: 0 10px 20px rgba(8, 89, 124, .22);
    }

    body.role-siswa .checkout-modal {
        border-radius: 16px;
        overflow: hidden;
    }

    body.role-siswa .checkout-header {
        background: linear-gradient(145deg, #0a5b7a 0%, #1f7da0 54%, #2f8eb2 100%);
        color: #fff;
        border: 0;
        align-items: flex-start;
        padding: .85rem .9rem;
        position: relative;
    }

    body.role-siswa .checkout-header .close {
        color: #fff;
        opacity: 1;
        text-shadow: none;
        margin: 0 0 0 .4rem;
    }

    body.role-siswa .checkout-body {
        background: #f8fafc;
        padding: .85rem;
    }

    body.role-siswa .checkout-student,
    body.role-siswa .checkout-summary,
    body.role-siswa .checkout-method,
    body.role-siswa .checkout-form-box {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 12px;
        padding: .7rem;
        margin-bottom: .65rem;
    }

    body.role-siswa .checkout-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .6rem;
        font-size: .8rem;
        color: #475569;
        margin-bottom: .35rem;
    }

    body.role-siswa .checkout-row strong {
        color: #0f172a;
        font-size: .82rem;
        text-align: right;
    }

    body.role-siswa .checkout-total {
        margin-bottom: 0;
        padding-top: .45rem;
        border-top: 1px dashed rgba(148, 163, 184, .45);
    }

    body.role-siswa .checkout-total strong {
        font-size: 1.3rem;
        color: #0b5f84;
    }

    body.role-siswa .checkout-method {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0;
    }

    body.role-siswa .checkout-method.active {
        border-color: rgba(14, 116, 144, .4);
        box-shadow: 0 0 0 3px rgba(14, 116, 144, .08);
    }

    body.role-siswa .checkout-footer {
        background: #f8fafc;
        border-top: 1px solid rgba(15, 23, 42, .08);
        padding: .7rem .85rem .85rem;
        gap: .5rem;
    }

    body.role-siswa .checkout-footer-total {
        width: 100%;
        text-align: right;
        color: #475569;
        font-size: .75rem;
        margin-bottom: .05rem;
    }

    body.role-siswa .checkout-footer-total strong {
        font-size: 1.35rem;
        color: #0b5f84;
        margin-left: .35rem;
    }

    body.role-siswa .checkout-footer #btnSubmit {
        background: #0a5b7a;
        border-color: #0a5b7a;
    }
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    function setSelectedTagihan(jenisId, jenisNama, nominal) {
        $('#selected_tagihan_total').text('Rp ' + Number(nominal).toLocaleString('id-ID'));

        const $btnBayarSelected = $('#btnBayarSelected');
        if ($btnBayarSelected.length) {
            $btnBayarSelected
                .attr('data-jenis', jenisId)
                .attr('data-nama', jenisNama)
                .attr('data-nominal', nominal)
                .text('Bayar Sekarang • Rp ' + Number(nominal).toLocaleString('id-ID'));
        }
    }

    const $selectedDefault = $('.student-bill-selector:checked').first();
    if ($selectedDefault.length) {
        setSelectedTagihan(
            $selectedDefault.data('jenis'),
            $selectedDefault.data('nama'),
            $selectedDefault.data('nominal')
        );
    }

    $('.student-bill-selector').on('change', function() {
        setSelectedTagihan(
            $(this).data('jenis'),
            $(this).data('nama'),
            $(this).data('nominal')
        );
    });

    // Modal bayar
    $('[data-target="#modalBayar"]').click(function() {
        var jenisId = $(this).data('jenis');
        var jenisNama = $(this).data('nama');
        var nominal = $(this).data('nominal');
        
        $('#jenis_pembayaran_id').val(jenisId);
        $('#display_jenis').val(jenisNama + ' - Rp ' + nominal.toLocaleString('id-ID'));
        $('#checkout_nama').text(jenisNama);
        $('#checkout_total').text('Rp ' + nominal.toLocaleString('id-ID'));
        $('#checkout_footer_total').text('Rp ' + nominal.toLocaleString('id-ID'));
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