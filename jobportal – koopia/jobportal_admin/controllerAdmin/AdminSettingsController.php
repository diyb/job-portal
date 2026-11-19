<?php
class AdminSettingsController
{
    public function index(): void
    {
        render('settings', ['title' => 'Seaded']);
    }

    public function save(): void
    {
        $name = trim($_POST['site_name'] ?? '');
        $mail = trim($_POST['contact_email'] ?? '');
        $per = (int)($_POST['jobs_per_page'] ?? 10);
        if ($name === '' || !filter_var($mail, FILTER_VALIDATE_EMAIL) || $per < 1 || $per > 100) {
            flash('danger', 'Kontrolli andmeid: nimi, korrektne e-post ja lehe suurus 1–100.');
            redirect(url('settings'));
        }
        AdminData::saveSetting('site_name', mb_substr($name, 0, 80));
        AdminData::saveSetting('contact_email', $mail);
        AdminData::saveSetting('jobs_per_page', (string)$per);
        flash('success', 'Seaded salvestatud.');
        redirect(url('settings'));
    }
}
