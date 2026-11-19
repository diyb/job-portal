<?php
class AdminJobController
{
    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        render('jobs', ['title' => 'Töökuulutused', 'jobs' => AdminData::jobs($q), 'q' => $q]);
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $job = Job::find($id);
        if (!$job) {
            flash('danger', 'Kuulutust ei leitud.');
            redirect(url('jobs'));
        }
        if (is_post()) {
            $title = trim($_POST['title'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            if (mb_strlen($title) < 3 || mb_strlen($desc) < 10) {
                flash('danger', 'Pealkiri ja kirjeldus on kohustuslikud.');
            } else {
                AdminData::updateJob($id, [
                    'title' => $title, 'description' => $desc,
                    'requirements' => trim($_POST['requirements'] ?? '') ?: null,
                    'category_id' => (int)($_POST['category_id'] ?? 0) ?: null,
                    'location_id' => (int)($_POST['location_id'] ?? 0) ?: null,
                    'status' => ($_POST['status'] ?? '') === 'closed' ? 'closed' : 'active',
                ]);
                flash('success', 'Kuulutus uuendatud.');
                redirect(url('jobs'));
            }
        }
        render('job_edit', ['title' => 'Muuda kuulutust', 'job' => $job, 'categories' => Category::all(), 'locations' => Location::all()]);
    }

    public function toggle(): void
    {
        $job = Job::find((int)($_POST['id'] ?? 0));
        if ($job) {
            AdminData::setJobStatus((int)$job['id'], $job['status'] === 'active' ? 'closed' : 'active');
            flash('success', 'Staatus muudetud.');
        }
        redirect(url('jobs'));
    }

    public function delete(): void
    {
        AdminData::deleteJob((int)($_POST['id'] ?? 0));
        flash('success', 'Kuulutus kustutatud.');
        redirect(url('jobs'));
    }
}
