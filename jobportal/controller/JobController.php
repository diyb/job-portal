<?php
class JobController
{
    public function home(): void
    {
        $u = current_user();
        render('start', [
            'title'      => 'Leia oma unistuste töö',
            'jobs'       => Job::latest(5),
            'stats'      => Job::stats(),
            'companies'  => array_slice(Company::all(), 0, 6),
            'categories' => Category::all(),
            'locations'  => Location::all(),
            'favIds'     => $u ? Favorite::ids((int)$u['id']) : [],
        ]);
    }

    public function index(): void
    {
        $u = current_user();
        $filters = [
            'q'        => trim($_GET['q'] ?? ''),
            'category' => (int)($_GET['category'] ?? 0),
            'location' => (int)($_GET['location'] ?? 0),
            'type'     => $_GET['type'] ?? '',
            'salary'   => array_map('strval', (array)($_GET['salary'] ?? [])),
            'sort'     => $_GET['sort'] ?? 'new',
        ];
        $total = Job::count($filters);
        $pg = paginate($total, max(1, (int)setting('jobs_per_page', '10')), (int)($_GET['p'] ?? 1));
        render('jobs', [
            'title'      => 'Tööpakkumised',
            'jobs'       => Job::search($filters, $pg['offset'], $pg['per']),
            'total'      => $total,
            'pg'         => $pg,
            'f'          => $filters,
            'categories' => Category::all(),
            'locations'  => Location::all(),
            'favIds'     => $u ? Favorite::ids((int)$u['id']) : [],
        ]);
    }

    public function show(): void
    {
        $job = Job::find((int)($_GET['id'] ?? 0));
        $u = current_user();
        $isOwner = $u && $u['role'] === 'employer' && ($c = Company::byUser((int)$u['id'])) && (int)$c['id'] === (int)($job['company_id'] ?? 0);
        if (!$job || ($job['status'] !== 'active' && !$isOwner && ($u['role'] ?? '') !== 'admin')) {
            http_response_code(404);
            render('404', ['title' => 'Kuulutust ei leitud']);
            return;
        }
        render('readjob', [
            'title'    => $job['title'],
            'job'      => $job,
            'company'  => Company::byId((int)$job['company_id']),
            'applied'  => $u ? Application::exists((int)$u['id'], (int)$job['id']) : false,
            'isFav'    => $u ? Favorite::has((int)$u['id'], (int)$job['id']) : false,
            'cvs'      => ($u && $u['role'] === 'user') ? CV::byUser((int)$u['id']) : [],
        ]);
    }

    public function report(): void
    {
        $u = require_login();
        $jobId = (int)($_POST['job_id'] ?? 0);
        $reason = trim($_POST['reason'] ?? '');
        if (!Job::find($jobId) || mb_strlen($reason) < 5) {
            flash('danger', 'Kaebuse esitamiseks kirjelda põhjust (vähemalt 5 märki).');
        } else {
            Report::create((int)$u['id'], $jobId, mb_substr($reason, 0, 2000));
            flash('success', 'Aitäh! Kaebus on edastatud administraatorile.');
        }
        redirect(url('job', ['id' => $jobId]));
    }
}
