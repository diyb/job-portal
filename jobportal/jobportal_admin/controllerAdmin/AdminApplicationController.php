<?php
class AdminApplicationController
{
    public function index(): void
    {
        $status = $_GET['status'] ?? '';
        if (!in_array($status, Application::STATUSES, true)) {
            $status = '';
        }
        render('applications', ['title' => 'Kandideerimised', 'apps' => AdminData::applications($status), 'status' => $status, 'statuses' => Application::STATUSES]);
    }
}
