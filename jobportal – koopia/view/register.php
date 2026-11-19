<div class="container py-5">
    <div class="auth-box mx-auto">
        <h1 class="h3 fw-bold mb-1">Loo konto</h1>
        <p class="text-muted">Alusta tööotsingut või leia uusi töötajaid.</p>
        <form method="post" action="<?= e(url('register')) ?>"><?= csrf_field() ?>
            <div class="btn-group w-100 mb-3" role="group">
                <input type="radio" class="btn-check" name="role" id="r1" value="user" <?= $old['role'] === 'user' ? 'checked' : '' ?>>
                <label class="btn btn-outline-primary" for="r1"><i class="bi bi-person"></i> Tööotsija</label>
                <input type="radio" class="btn-check" name="role" id="r2" value="employer" <?= $old['role'] === 'employer' ? 'checked' : '' ?>>
                <label class="btn btn-outline-primary" for="r2"><i class="bi bi-building"></i> Tööandja</label>
            </div>
            <label class="form-label">Nimi</label>
            <input name="name" value="<?= e($old['name']) ?>" class="form-control mb-3" required>
            <div id="companyField" class="<?= $old['role'] === 'employer' ? '' : 'd-none' ?>">
                <label class="form-label">Ettevõtte nimi</label>
                <input name="company" value="<?= e($old['company']) ?>" class="form-control mb-3">
            </div>
            <label class="form-label">E-post</label>
            <input type="email" name="email" value="<?= e($old['email']) ?>" class="form-control mb-3" required>
            <label class="form-label">Parool <span class="text-muted small">(vähemalt 8 märki)</span></label>
            <input type="password" name="password" class="form-control mb-3" required minlength="8">
            <label class="form-label">Korda parooli</label>
            <input type="password" name="password2" class="form-control mb-3" required>
            <button class="btn btn-primary w-100">Registreeru</button>
        </form>
        <p class="text-center small mt-3 mb-0">Konto olemas? <a href="<?= e(url('login')) ?>">Logi sisse</a></p>
    </div>
</div>
