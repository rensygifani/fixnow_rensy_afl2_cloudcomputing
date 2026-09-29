<?php
$webConfig = require __DIR__ . '/firebase_web_config.php';
$socialReady = strpos($webConfig['apiKey'], 'GANTI') === false && strpos($webConfig['appId'], 'GANTI') === false;
?>
<?php if ($socialReady): ?>
<div class="d-flex align-items-center my-4 text-muted">
    <hr class="flex-grow-1 m-0"><span class="px-3 small">atau</span><hr class="flex-grow-1 m-0">
</div>

<div id="social-error" class="alert alert-danger d-none"></div>

<div class="d-grid gap-2">
    <button type="button" id="btn-google" class="btn btn-fn-outline py-2">
        <i class="bi bi-google"></i>&nbsp; Lanjutkan dengan Google
    </button>
    <button type="button" id="btn-github" class="btn btn-fn-outline py-2">
        <i class="bi bi-github"></i>&nbsp; Lanjutkan dengan GitHub
    </button>
</div>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.0/firebase-app.js";
    import { getAuth, signInWithPopup, GoogleAuthProvider, GithubAuthProvider }
        from "https://www.gstatic.com/firebasejs/10.13.0/firebase-auth.js";

    const app  = initializeApp(<?= json_encode($webConfig) ?>);
    const auth = getAuth(app);
    const box  = document.getElementById('social-error');

    const pesan = {
        'auth/popup-closed-by-user': 'Jendela login ditutup sebelum selesai.',
        'auth/cancelled-popup-request': 'Permintaan login dibatalkan.',
        'auth/popup-blocked': 'Popup diblokir browser. Izinkan popup untuk situs ini.',
        'auth/account-exists-with-different-credential':
            'Email ini sudah terdaftar dengan metode login lain. Login dengan metode yang dipakai saat pertama daftar.',
        'auth/unauthorized-domain': 'Domain ini belum ada di Authorized domains Firebase.',
        'auth/operation-not-allowed': 'Provider ini belum diaktifkan di Firebase Console.',
        'auth/network-request-failed': 'Koneksi bermasalah, coba lagi.'
    };

    function tampilError(text) {
        box.textContent = text;
        box.classList.remove('d-none');
    }

    async function loginDengan(provider, tombol) {
        box.classList.add('d-none');
        tombol.disabled = true;
        try {
            const hasil   = await signInWithPopup(auth, provider);
            const idToken = await hasil.user.getIdToken();

            const res  = await fetch('social_login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ idToken })
            });
            const data = await res.json();

            if (data.ok) {
                window.location.href = 'index.php';
            } else {
                tampilError(data.error || 'Login gagal.');
                tombol.disabled = false;
            }
        } catch (err) {
            tampilError(pesan[err.code] || ('Login gagal: ' + (err.code || err.message)));
            tombol.disabled = false;
        }
    }

    const google = document.getElementById('btn-google');
    const github = document.getElementById('btn-github');
    google.addEventListener('click', () => loginDengan(new GoogleAuthProvider(), google));
    github.addEventListener('click', () => loginDengan(new GithubAuthProvider(), github));
</script>
<?php endif; ?>
