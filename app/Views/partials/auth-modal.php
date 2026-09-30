<?php
use App\Config\App;
use App\Config\Security;

$modalCsrf = Security::generateCsrfToken();
?>

<!-- Auth Overlay Modal (Login & Register Popup) -->
<div id="auth-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm transition-opacity duration-200" role="dialog" aria-modal="true" aria-labelledby="auth-modal-title">
    <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[92vh] transition-all transform duration-200">
        
        <!-- Header & Segmented Tab Switcher -->
        <div class="p-5 pb-3 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
            <!-- Tabs -->
            <div class="inline-flex items-center p-1 bg-slate-100 dark:bg-slate-800/80 rounded-2xl border border-slate-200/80 dark:border-slate-700/60">
                <button type="button" id="tab-btn-login" onclick="switchAuthTab('login')" class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center justify-center gap-2 text-amber-600 dark:text-amber-400 bg-white dark:bg-slate-900 shadow-sm leading-none">
                    <span class="material-symbols-outlined text-[18px] leading-none">login</span>
                    <span>Masuk</span>
                </button>
                <button type="button" id="tab-btn-register" onclick="switchAuthTab('register')" class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center justify-center gap-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white leading-none">
                    <span class="material-symbols-outlined text-[18px] leading-none">how_to_reg</span>
                    <span>Daftar Anggota</span>
                </button>
            </div>

            <!-- Close Button -->
            <button type="button" onclick="closeAuthModal()" class="w-9 h-9 rounded-xl inline-flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Tutup Modal">
                <span class="material-symbols-outlined text-xl leading-none">close</span>
            </button>
        </div>

        <!-- Body / Scrollable Content -->
        <div class="p-6 overflow-y-auto space-y-6">

            <!-- Panel 1: Login Form -->
            <div id="auth-panel-login" class="space-y-5">
                <div class="text-left space-y-1">
                    <h3 id="auth-modal-title" class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">
                        Masuk ke Akun
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Gunakan Email atau NIM yang terdaftar di K'mplang Salatiga.
                    </p>
                </div>

                <form action="<?= App::baseUrl('/login') ?>" method="POST" class="space-y-4">
                    <input type="hidden" name="_csrf_token" value="<?= $modalCsrf ?>">

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Email atau NIM
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">person</span>
                            <input type="text" name="identifier" required placeholder="nama@email.com atau 672024xxx" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">key</span>
                            <input type="password" id="modal-login-password" name="password" required placeholder="Masukkan kata sandi akun" class="w-full h-11 pl-11 pr-11 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                            <button type="button" onclick="togglePasswordVisibility('modal-login-password', this)" class="absolute right-2.5 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-[20px] leading-none">visibility</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full h-11 rounded-xl font-bold text-xs bg-amber-500 hover:bg-amber-400 text-slate-950 dark:bg-amber-400 dark:hover:bg-amber-300 shadow-sm hover:shadow transition-all inline-flex items-center justify-center gap-2 leading-none">
                        <span class="material-symbols-outlined text-[19px] leading-none">login</span>
                        <span>Masuk Sekarang</span>
                    </button>
                </form>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 text-center text-xs text-slate-600 dark:text-slate-400">
                    <span>Belum terdaftar sebagai anggota?</span>
                    <button type="button" onclick="switchAuthTab('register')" class="font-bold text-amber-600 dark:text-amber-400 hover:underline ml-1">
                        Daftar di sini
                    </button>
                </div>
            </div>

            <!-- Panel 2: Register Form -->
            <div id="auth-panel-register" class="space-y-5 hidden">
                <div class="text-left space-y-1">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-montserrat tracking-tight">
                        Pendaftaran Anggota
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Bergabunglah bersama keluarga mahasiswa perantau Lampung di Salatiga.
                    </p>
                </div>

                <form action="<?= App::baseUrl('/register') ?>" method="POST" class="space-y-4">
                    <input type="hidden" name="_csrf_token" value="<?= $modalCsrf ?>">

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">badge</span>
                            <input type="text" name="nama_lengkap" required placeholder="Masukkan nama lengkap Anda" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                        </div>
                    </div>

                    <!-- NIM & Fakultas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                NIM (Nomor Mahasiswa)
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">tag</span>
                                <input type="text" name="nim" placeholder="Contoh: 672024xxx" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Fakultas / Kampus
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">school</span>
                                <input type="text" name="fakultas" placeholder="Contoh: FTI UKSW" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Asal Daerah -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Asal Daerah di Lampung <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">location_on</span>
                            <input type="text" name="asal_daerah" required placeholder="Contoh: Bandar Lampung / Metro / Lampung Timur" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                        </div>
                    </div>

                    <!-- WhatsApp & Tanggal Lahir -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Nomor WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">chat</span>
                                <input type="tel" name="whatsapp" required placeholder="08xxxxxxxxxx" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Tanggal Lahir
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">calendar_today</span>
                                <input type="date" name="tanggal_lahir" class="w-full h-11 pl-11 pr-3 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- Email & Password -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Email Pribadi <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">mail</span>
                                <input type="email" name="email" required placeholder="nama@email.com" class="w-full h-11 pl-11 pr-4 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">key</span>
                                <input type="password" id="modal-register-password" name="password" required placeholder="Minimal 6 karakter" class="w-full h-11 pl-11 pr-11 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all">
                                <button type="button" onclick="togglePasswordVisibility('modal-register-password', this)" class="absolute right-2.5 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center transition-colors">
                                    <span class="material-symbols-outlined text-[20px] leading-none">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Motivasi (Opsional) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Motivasi Bergabung <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-3 text-slate-400 dark:text-slate-400 text-[20px] pointer-events-none select-none flex items-center justify-center leading-none">edit_note</span>
                            <textarea name="motivasi" rows="2" placeholder="Ceritakan motivasi singkat Anda bergabung bersama K'mplang..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950/80 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-amber-500/20 transition-all"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full h-11 rounded-xl font-bold text-xs bg-amber-500 hover:bg-amber-400 text-slate-950 dark:bg-amber-400 dark:hover:bg-amber-300 shadow-sm hover:shadow transition-all inline-flex items-center justify-center gap-2 leading-none">
                        <span class="material-symbols-outlined text-[19px] leading-none">how_to_reg</span>
                        <span>Kirim Pendaftaran &amp; Buat Akun</span>
                    </button>
                </form>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 text-center text-xs text-slate-600 dark:text-slate-400">
                    <span>Sudah memiliki akun?</span>
                    <button type="button" onclick="switchAuthTab('login')" class="font-bold text-amber-600 dark:text-amber-400 hover:underline ml-1">
                        Masuk di sini
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function openAuthModal(tab = 'login') {
        const modal = document.getElementById('auth-modal');
        if (!modal) return;
        switchAuthTab(tab);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeAuthModal() {
        const modal = document.getElementById('auth-modal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function switchAuthTab(tab) {
        const panelLogin = document.getElementById('auth-panel-login');
        const panelRegister = document.getElementById('auth-panel-register');
        const tabBtnLogin = document.getElementById('tab-btn-login');
        const tabBtnRegister = document.getElementById('tab-btn-register');

        const activeClasses = ['text-amber-600', 'dark:text-amber-400', 'bg-white', 'dark:bg-slate-900', 'shadow-sm'];
        const inactiveClasses = ['text-slate-600', 'dark:text-slate-400', 'hover:text-slate-900', 'dark:hover:text-white'];

        if (tab === 'login') {
            if (panelLogin) panelLogin.classList.remove('hidden');
            if (panelRegister) panelRegister.classList.add('hidden');

            if (tabBtnLogin) {
                tabBtnLogin.classList.add(...activeClasses);
                tabBtnLogin.classList.remove(...inactiveClasses);
            }
            if (tabBtnRegister) {
                tabBtnRegister.classList.remove(...activeClasses);
                tabBtnRegister.classList.add(...inactiveClasses);
            }
        } else {
            if (panelLogin) panelLogin.classList.add('hidden');
            if (panelRegister) panelRegister.classList.remove('hidden');

            if (tabBtnRegister) {
                tabBtnRegister.classList.add(...activeClasses);
                tabBtnRegister.classList.remove(...inactiveClasses);
            }
            if (tabBtnLogin) {
                tabBtnLogin.classList.remove(...activeClasses);
                tabBtnLogin.classList.add(...inactiveClasses);
            }
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const icon = btn.querySelector('.material-symbols-outlined');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            if (icon) icon.textContent = 'visibility';
        }
    }

    // Close on backdrop click & ESC key
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('auth-modal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeAuthModal();
                }
            });
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeAuthModal();
            }
        });

        // Hash auto open
        const hash = window.location.hash.toLowerCase();
        if (hash === '#login' || hash === '#auth-login') {
            openAuthModal('login');
        } else if (hash === '#register' || hash === '#daftar') {
            openAuthModal('register');
        }
    });
</script>
