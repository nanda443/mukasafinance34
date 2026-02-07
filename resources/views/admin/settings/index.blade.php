@extends('layouts.app')

@section('title', 'Admin - Pengaturan Sistem')

@section('content')
<div class="row">
    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif

        <div class="card card-warning collapsed-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bug"></i> Debug Info & Tools</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body" style="display: none;">
                <h5>Storage Check</h5>
                <ul>
                    <li><strong>Public Disk Root:</strong> {{ config('filesystems.disks.public.root') }}</li>
                    <li><strong>Is Writable:</strong> {{ is_writable(config('filesystems.disks.public.root')) ? 'YES' : 'NO' }}</li>
                    <li>
                        <strong>Test File Write:</strong>
                        @php
                            $testFile = config('filesystems.disks.public.root') . '/test_write.txt';
                            try {
                                file_put_contents($testFile, 'Test ' . date('Y-m-d H:i:s'));
                                echo file_exists($testFile) ? '<span class="text-success">SUCCESS</span>' : '<span class="text-danger">FAILED</span>';
                            } catch (\Exception $e) {
                                echo '<span class="text-danger">ERROR: ' . $e->getMessage() . '</span>';
                            }
                        @endphp
                    </li>
                </ul>

                <h5>Database Values (Raw)</h5>
                <ul>
                    <li><strong>Logo:</strong> {{ \App\Models\Setting::getValue('system_logo', 'NULL') }}</li>
                    <li><strong>Favicon:</strong> {{ \App\Models\Setting::getValue('favicon', 'NULL') }}</li>
                    <li><strong>Background:</strong> {{ \App\Models\Setting::getValue('login_bg_image', 'NULL') }}</li>
                </ul>

                <hr>
                
                <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-sync"></i> Force Clear Cache (Config & Route)
                    </button>
                    <small class="d-block text-muted mt-1">Gunakan ini jika perubahan tidak muncul.</small>
                </form>
            </div>
        </div>

        <div class="card card-primary card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-three-general-tab" data-toggle="pill" href="#custom-tabs-three-general" role="tab" aria-controls="custom-tabs-three-general" aria-selected="true">Umum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-images-tab" data-toggle="pill" href="#custom-tabs-three-images" role="tab" aria-controls="custom-tabs-three-images" aria-selected="false">Tampilan & Gambar</a>
                    </li>
                </ul>
            </div>
            
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <!-- Tab Umum -->
                        <div class="tab-pane fade show active" id="custom-tabs-three-general" role="tabpanel" aria-labelledby="custom-tabs-three-general-tab">
                            <div class="form-group">
                                <label for="system_name">Nama Sistem</label>
                                <input type="text" class="form-control" id="system_name" name="system_name" value="{{ $settings['system_name']->value ?? '' }}" placeholder="Contoh: SMA Muhammadiyah Kasihan">
                                <small class="text-muted">Nama yang muncul di judul halaman dan footer.</small>
                            </div>
                            <div class="form-group">
                                <label for="system_title">Judul Halaman Login</label>
                                <input type="text" class="form-control" id="system_title" name="system_title" value="{{ $settings['system_title']->value ?? '' }}" placeholder="Contoh: Sistem Informasi Keuangan Sekolah">
                                <small class="text-muted">Judul besar yang muncul di halaman login.</small>
                            </div>
                        </div>

                        <!-- Tab Gambar -->
                        <div class="tab-pane fade" id="custom-tabs-three-images" role="tabpanel" aria-labelledby="custom-tabs-three-images-tab">
                            <div class="form-group">
                                <label for="favicon">Favicon (Ikon Browser)</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="favicon" name="favicon" accept="image/*">
                                        <label class="custom-file-label" for="favicon">Pilih file</label>
                                    </div>
                                </div>
                                <small class="text-muted">Format yang disarankan: .ico atau .png (32x32 px)</small>
                                @if(isset($settings['favicon']) && $settings['favicon']->value)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $settings['favicon']->value) }}" alt="Favicon" style="height: 32px; width: 32px; object-fit: contain; border: 1px solid #ddd; padding: 2px;">
                                        <span class="ml-2 text-muted">Saat ini</span>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="system_logo">Logo Sistem</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="system_logo" name="system_logo" accept="image/*">
                                        <label class="custom-file-label" for="system_logo">Pilih file</label>
                                    </div>
                                </div>
                                @if(isset($settings['system_logo']) && $settings['system_logo']->value)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $settings['system_logo']->value) }}" alt="Logo" style="height: 50px; object-fit: contain; border: 1px solid #ddd; padding: 5px; background: #eee;">
                                        <span class="ml-2 text-muted">Saat ini</span>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="login_bg_image">Background Halaman Login</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="login_bg_image" name="login_bg_image" accept="image/*">
                                        <label class="custom-file-label" for="login_bg_image">Pilih file</label>
                                    </div>
                                </div>
                                <small class="text-muted">Gambar besar untuk sisi kiri halaman login (Disarankan 1920x1080 px)</small>
                                @if(isset($settings['login_bg_image']) && $settings['login_bg_image']->value)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $settings['login_bg_image']->value) }}" alt="Background" style="height: 100px; width: 200px; object-fit: cover; border: 1px solid #ddd;">
                                        <span class="ml-2 text-muted">Saat ini</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengaturan</button>
                    <button type="reset" class="btn btn-default float-right">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Tampilkan nama file yang dipilih di input custom file
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endsection
