<div class="auth-box mx-auto">
    <div class="text-center mb-3"><i class="bi bi-shield-lock-fill fs-1 text-primary"></i></div>
    <h1 class="h4 fw-bold text-center mb-3">JobPortal Admin</h1>
    <form method="post" action="<?= e(url('login')) ?>"><?= csrf_field() ?>
        <label class="form-label">E-post</label>
        <input type="email" name="email" value="<?= e($email) ?>" class="form-control mb-3" required autofocus>
        <label class="form-label">Parool</label>
        <input type="password" name="password" class="form-control mb-3" required>
        <button class="btn btn-primary w-100">Logi sisse</button>
    </form>
</div>
