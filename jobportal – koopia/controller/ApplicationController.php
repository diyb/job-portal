<?php
class ApplicationController
{
    public function apply(): void
    {
        $u = require_login();
        $jobId = (int)($_POST['job_id'] ?? 0);
        if ($u['role'] !== 'user') {
            flash('warning', 'Kandideerida saavad ainult tööotsija kontod.');
            redirect(url('job', ['id' => $jobId]));
        }
        $job = Job::find($jobId);
        if (!$job || $job['status'] !== 'active') {
            flash('danger', 'Kuulutus ei ole enam saadaval.');
            redirect(url('jobs'));
        }
        if (Application::exists((int)$u['id'], $jobId)) {
            flash('info', 'Oled sellele kuulutusele juba kandideerinud.');
            redirect(url('job', ['id' => $jobId]));
        }
        // CV peab kuuluma sisseloginud kasutajale
        $cvId = (int)($_POST['cv_id'] ?? 0) ?: null;
        if ($cvId) {
            $cv = CV::find($cvId);
            if (!$cv || (int)$cv['user_id'] !== (int)$u['id']) {
                $cvId = null;
            }
        }
        $msg = trim($_POST['message'] ?? '');
        Application::create((int)$u['id'], $jobId, $cvId, $msg !== '' ? mb_substr($msg, 0, 3000) : null);

        $company = Company::byId((int)$job['company_id']);
        if ($company) {
            notify((int)$company['user_id'], $u['name'] . ' kandideeris ametikohale „' . $job['title'] . '".', url('employer_candidates', ['job_id' => $jobId]));
        }
        flash('success', 'Kandideerimine saadetud! Staatust näed jaotises „Kandideerimised".');
        redirect(url('applications'));
    }

    public function mine(): void
    {
        $u = require_login();
        render('applications', ['title' => 'Minu kandideerimised', 'list' => Application::byUser((int)$u['id'])]);
    }
}
