@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Riwayat Pembayaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Riwayat Pembayaran</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline student-mobile-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Daftar Riwayat Pembayaran
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-bordered table-striped table-hover" id="table-riwayat">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis Pembayaran</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembayarans as $key => $pembayaran)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $pembayaran->jenisPembayaran->nama }}</td>
                                <td>Rp {{ number_format($pembayaran->jenisPembayaran->nominal, 0, ',', '.') }}</td>
                                <td>{!! $pembayaran->status_badge !!}</td>
                                <td>{{ Str::limit($pembayaran->keterangan, 30) }}</td>
                                <td>
                                    <a href="{{ route('siswa.riwayat.show', $pembayaran->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="student-mobile-list d-md-none student-mobile-only">
                    @forelse($pembayarans as $pembayaran)
                    <a href="{{ route('siswa.riwayat.show', $pembayaran->id) }}" class="student-mobile-item text-decoration-none">
                        <span class="student-mobile-icon"><i class="fas fa-history"></i></span>
                        <div class="student-mobile-main">
                            <strong>{{ $pembayaran->jenisPembayaran->nama }}</strong>
                            <span>{{ $pembayaran->created_at->format('d M Y H:i') }}</span>
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('#table-riwayat').DataTable({
            "responsive": true,
            "order": [[1, "desc"]]
        });
    });
</script>
@endsection
