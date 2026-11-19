<?php
class AdminReportController
{
    public function index(): void
    {
        render('reports', ['title' => 'Kaebused', 'reports' => AdminData::reports()]);
    }

    public function setStatus(): void
    {
        $status = ($_POST['status'] ?? '') === 'resolved' ? 'resolved' : 'open';
        AdminData::setReportStatus((int)($_POST['id'] ?? 0), $status);
        flash('success', 'Kaebuse staatus uuendatud.');
        redirect(url('reports'));
    }
}
