<div class="container py-5">
    <div class="auth-box mx-auto">
        <h1 class="h3 fw-bold mb-1">Logi sisse</h1>
        <p class="text-muted">Tere tulemast tagasi!</p>
        <form method="post" action="<?= e(url('login')) ?>"><?= csrf_field() ?>
            <label class="form-label">E-post</label>
            <input type="email" name="email" value="<?= e($email) ?>" class="form-control mb-3" required autofocus>
            <label class="form-label">Parool</label>
            <input type="password" name="password" class="form-control mb-3" required>
            <button class="btn btn-primary w-100">Logi sisse</button>
        </form>
        <p class="text-center small mt-3 mb-0">Pole kontot? <a href="<?= e(url('register')) ?>">Registreeru</a></p>
    </div>
</div>
