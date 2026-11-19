<footer class="jp-footer">
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-2 py-4">
        <div><i class="bi bi-briefcase-fill brand-icon"></i> <strong><?= e(setting('site_name', 'JobPortal')) ?></strong> · tööotsimise ja värbamise keskkond</div>
        <div class="text-muted small"><a href="mailto:<?= e(setting('contact_email')) ?>"><?= e(setting('contact_email')) ?></a> · © <?= date('Y') ?></div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
