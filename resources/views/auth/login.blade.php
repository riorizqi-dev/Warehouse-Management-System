<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container">
        <div class="curved-shape"></div>
        <div class="curved-shape2"></div>
        
        <div class="form-box Login">
            <h2 class="animation" style="--D:0; --S:21">Masuk</h2>
            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf
                <input type="text" name="fake_username" autocomplete="username" tabindex="-1" style="position:absolute;left:-9999px;opacity:0;">
                <input type="password" name="fake_password" autocomplete="current-password" tabindex="-1" style="position:absolute;left:-9999px;opacity:0;">

                @if($errors->any())
                    <div id="error-toast" class="alert-toast alert-error">
                        <div class="alert-content">
                            <box-icon name='error-circle' type='solid' color="#fff" size="20px"></box-icon>
                            <div class="alert-message">
                                @if($errors->has('login') || $errors->has('email'))
                                    <strong>Email/Username salah!</strong><br>
                                    <small>Pastikan email atau username sudah benar.</small>
                                @elseif($errors->has('password'))
                                    <strong>Password salah!</strong><br>
                                    <small>Pastikan password yang Anda masukkan benar.</small>
                                @else
                                    <strong>Terjadi kesalahan!</strong><br>
                                    <small>{{ $errors->first() }}</small>
                                @endif
                            </div>
                            <button type="button" onclick="this.parentElement.parentElement.remove()" class="alert-close">&times;</button>
                        </div>
                    </div>
                @endif

                <div class="input-box animation" style="--D:1; --S:22">
                    <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" class="@error('login') error-input @enderror">
                    <label for="login">Nama Pengguna</label>
                    <box-icon type='solid' name='user' color="gray"></box-icon>
                </div>

                <div class="input-box has-password-toggle animation" style="--D:2; --S:23">
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="@error('password') error-input @enderror">
                    <label for="password">Kata Sandi</label>
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                        <box-icon name='show' type='solid' color="gray"></box-icon>
                    </button>
                </div>

                <div class="input-box animation" style="--D:3; --S:24">
                    <button class="btn" type="submit">Masuk</button>
                </div>

                <div class="regi-link animation" style="--D:4; --S:25">
                    <p>Belum punya akun? <br> <a href="{{ route('register') }}" class="SignUpLink">Daftar</a></p>
                </div>
            </form>
        </div>

        <div class="form-box Register">
            <h2 class="animation" style="--li:17; --S:0">Daftar</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                @if($errors->any())
                    <div id="error-toast-register" class="alert-toast alert-error">
                        <div class="alert-content">
                            <box-icon name='error-circle' type='solid' color="#fff" size="20px"></box-icon>
                            <div class="alert-message">
                                @if($errors->has('name'))
                                    <strong>Nama belum valid!</strong><br>
                                    <small>Silakan isi nama lengkap.</small>
                                @elseif($errors->has('email'))
                                    <strong>Email sudah terdaftar!</strong><br>
                                    <small>Email ini sudah digunakan, gunakan email lain atau login.</small>
                                @elseif($errors->has('password'))
                                    <strong>Password tidak valid!</strong><br>
                                    <small>Password minimal 8 karakter atau tidak sesuai konfirmasi.</small>
                                @else
                                    <strong>Terjadi kesalahan!</strong><br>
                                    <small>{{ $errors->first() }}</small>
                                @endif
                            </div>
                            <button type="button" onclick="this.parentElement.parentElement.remove()" class="alert-close">&times;</button>
                        </div>
                    </div>
                @endif

                <div class="input-box animation" style="--li:18; --S:1">
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="@error('name') error-input @enderror">
                    <label for="name">Nama Lengkap</label>
                    <box-icon type='solid' name='user' color="gray"></box-icon>
                </div>

                <div class="input-box animation" style="--li:19; --S:2">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="@error('email') error-input @enderror">
                    <label for="email">Email</label>
                    <box-icon name='envelope' type='solid' color="gray"></box-icon>
                </div>

                <div class="input-box animation" style="--li:19; --S:2">
                    <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp') }}" required autocomplete="tel">
                    <label for="no_telp">No HP</label>
                    <box-icon name='phone' type='solid' color="gray"></box-icon>
                </div>

                <div class="input-box animation" style="--li:19; --S:2">
                    <input id="kota" type="text" name="kota" value="{{ old('kota') }}" autocomplete="address-level2">
                    <label for="kota">Kota</label>
                    <box-icon name='map' type='solid' color="gray"></box-icon>
                </div>

                <div class="input-box animation" style="--li:19; --S:2">
                    <input id="alamat" type="text" name="alamat" value="{{ old('alamat') }}" required autocomplete="street-address">
                    <label for="alamat">Alamat</label>
                    <box-icon name='home' type='solid' color="gray"></box-icon>
                </div>

                <div class="input-box has-password-toggle animation" style="--li:19; --S:3">
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="@error('password') error-input @enderror">
                    <label for="password">Kata Sandi</label>
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                        <box-icon name='show' type='solid' color="gray"></box-icon>
                    </button>
                </div>

                <div class="input-box has-password-toggle animation" style="--li:19; --S:4">
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                        <box-icon name='show' type='solid' color="gray"></box-icon>
                    </button>
                </div>

                <div class="input-box animation" style="--li:20; --S:4">
                    <button class="btn" type="submit">Daftar</button>
                </div>

                <div class="regi-link animation" style="--li:21; --S:5">
                    <p>Sudah punya akun? <br> <a href="{{ route('login') }}" class="SignInLink">Masuk</a></p>
                </div>
            </form>
        </div>

        <div class="info-content Login">
            <h2 class="animation" style="--D:0; --S:20">SELAMAT DATANG KEMBALI!</h2>
            <p class="animation" style="--D:1; --S:21">Kami senang Anda kembali bersama kami. Jika ada yang dibutuhkan, kami siap membantu.</p>
            <div class="info-panel animation" style="--D:2; --S:22">
                <div class="info-chip"><box-icon name='package' color="#67e8f9" size="18px"></box-icon><span>Kelola barang realtime</span></div>
                <div class="info-chip"><box-icon name='line-chart' color="#67e8f9" size="18px"></box-icon><span>Analitik stok harian</span></div>
                <div class="info-chip"><box-icon name='shield' color="#67e8f9" size="18px"></box-icon><span>Akses admin aman</span></div>
            </div>
            <div class="kpi-grid animation" style="--D:3; --S:23">
                <div class="kpi-item">
                    <strong>1.2K+</strong>
                    <small>Produk Aktif</small>
                </div>
                <div class="kpi-item">
                    <strong>24/7</strong>
                    <small>Monitoring</small>
                </div>
            </div>
        </div>

        <div class="info-content Register">
            <h2 class="animation" style="--li:17; --S:0">SELAMAT DATANG!</h2>
            <p class="animation" style="--li:18; --S:1">Kami senang Anda bergabung dengan kami. Jika ada yang dibutuhkan, kami siap membantu.</p>
            <div class="info-panel animation" style="--li:19; --S:2">
                <div class="info-chip"><box-icon name='archive-in' color="#67e8f9" size="18px"></box-icon><span>Catat barang masuk</span></div>
                <div class="info-chip"><box-icon name='archive-out' color="#67e8f9" size="18px"></box-icon><span>Lacak barang keluar</span></div>
                <div class="info-chip"><box-icon name='file' color="#67e8f9" size="18px"></box-icon><span>Laporan otomatis</span></div>
            </div>
            <div class="kpi-grid animation" style="--li:20; --S:3">
                <div class="kpi-item">
                    <strong>99.9%</strong>
                    <small>Data Akurat</small>
                </div>
                <div class="kpi-item">
                    <strong>Fast</strong>
                    <small>Workflow Gudang</small>
                </div>
            </div>
        </div>
    </div>

    <script>
        const errorToast = document.getElementById('error-toast');
        if (errorToast) {
            setTimeout(() => {
                errorToast.style.opacity = '0';
                errorToast.style.transform = 'translateX(400px)';
                setTimeout(() => errorToast.remove(), 300);
            }, 5000);
        }

        const errorToastRegister = document.getElementById('error-toast-register');
        if (errorToastRegister) {
            setTimeout(() => {
                errorToastRegister.style.opacity = '0';
                errorToastRegister.style.transform = 'translateX(400px)';
                setTimeout(() => errorToastRegister.remove(), 300);
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const passwordInputs = document.querySelectorAll('input[type="password"]');
            
            passwordInputs.forEach(input => {
                const inputBox = input.closest('.input-box');
                if (!inputBox) return;
                
                const toggleBtn = inputBox.querySelector('.password-toggle');
                if (!toggleBtn) return;
                
                const icon = toggleBtn.querySelector('box-icon');
                
                toggleBtn.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    if (type === 'text') {
                        icon.setAttribute('name', 'hide');
                    } else {
                        icon.setAttribute('name', 'show');
                    }
                });
            });

            const hasOldLogin = @json((bool) old('login'));
            if (!hasOldLogin) {
                const loginInput = document.getElementById('login');
                const passwordInput = document.getElementById('password');
                if (loginInput) loginInput.value = '';
                if (passwordInput) passwordInput.value = '';
                setTimeout(() => {
                    if (loginInput) loginInput.value = '';
                    if (passwordInput) passwordInput.value = '';
                }, 100);
            }
        });
    </script>
</x-guest-layout>
