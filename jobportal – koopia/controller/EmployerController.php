<?php
class EmployerController
{
    /** Sisseloginud tööandja ettevõte (luuakse, kui puudub) */
    private function myCompany(): array
    {
        $u = current_user();
        $c = Company::byUser((int)$u['id']);
        if (!$c) {
            $id = Company::create((int)$u['id'], $u['name']);
            $c = Company::byId($id);
        }
        return $c;
    }

    public function dashboard(): void
    {
        $c = $this->myCompany();
        $cid = (int)$c['id'];
        $stats = Application::companyStats($cid);
        render('employer/dashboard', [
            'title'  => 'Tööandja paneel',
            'c'      => $c,
            'stats'  => $stats,
            'chart'  => Application::perDay($cid),
            'latest' => Application::latestForCompany($cid, 5),
        ], 'employer/layout');
    }

    public function jobs(): void
    {
        $c = $this->myCompany();
        render('employer/jobs', ['title' => 'Minu töökuulutused', 'c' => $c, 'jobs' => Job::byCompany((int)$c['id'])], 'employer/layout');
    }

    public function jobForm(): void
    {
        $c = $this->myCompany();
        $job = null;
        if (!empty($_GET['id'])) {
            $job = Job::find((int)$_GET['id']);
            if (!$job || (int)$job['company_id'] !== (int)$c['id']) {
                http_response_code(403);
                exit('Sul puudub õigus seda kuulutust muuta.');
            }
        }
        render('employer/job_form', [
            'title'      => $job ? 'Muuda töökuulutust' : 'Lisa töökuulutus',
            'c'          => $c,
            'job'        => $job,
            'categories' => Category::all(),
            'locations'  => Location::all(),
        ], 'employer/layout');
    }

    public function jobSave(): void
    {
        $c = $this->myCompany();
        $id = (int)($_POST['id'] ?? 0) ?: null;
        if ($id) {
            $existing = Job::find($id);
            if (!$existing || (int)$existing['company_id'] !== (int)$c['id']) {
                http_response_code(403);
                exit('Sul puudub õigus seda kuulutust muuta.');
            }
        }
        $types = ['Täistööaeg', 'Osaline tööaeg', 'Kaugtöö', 'Praktika'];
        $int = fn($k) => ($_POST[$k] ?? '') !== '' ? max(0, (int)$_POST[$k]) : null;
        $d = [
            'title'           => trim($_POST['title'] ?? ''),
            'description'     => trim($_POST['description'] ?? ''),
            'requirements'    => trim($_POST['requirements'] ?? '') ?: null,
            'category_id'     => (int)($_POST['category_id'] ?? 0) ?: null,
            'location_id'     => (int)($_POST['location_id'] ?? 0) ?: null,
            'salary_min'      => $int('salary_min'),
            'salary_max'      => $int('salary_max'),
            'employment_type' => in_array($_POST['employment_type'] ?? '', $types, true) ? $_POST['employment_type'] : 'Täistööaeg',
            'start_date'      => trim($_POST['start_date'] ?? '') ?: 'Esimesel võimalusel',
            'status'          => ($_POST['status'] ?? 'active') === 'closed' ? 'closed' : 'active',
        ];
        $err = [];
        if (mb_strlen($d['title']) < 3) $err[] = 'Ametinimetus on kohustuslik.';
        if (mb_strlen($d['description']) < 20) $err[] = 'Kirjeldus peab olema vähemalt 20 märki.';
        if ($d['salary_min'] && $d['salary_max'] && $d['salary_min'] > $d['salary_max']) $err[] = 'Minimaalne palk ei tohi ületada maksimaalset.';
        if ($err) {
            foreach ($err as $m) flash('danger', $m);
            render('employer/job_form', [
                'title' => $id ? 'Muuda töökuulutust' : 'Lisa töökuulutus', 'c' => $c, 'job' => $d + ['id' => $id],
                'categories' => Category::all(), 'locations' => Location::all(),
            ], 'employer/layout');
            return;
        }
        Job::save($id, (int)$c['id'], $d);
        flash('success', $id ? 'Kuulutus uuendatud.' : 'Kuulutus avaldatud.');
        redirect(url('employer_jobs'));
    }

    public function jobDelete(): void
    {
        $c = $this->myCompany();
        Job::delete((int)($_POST['id'] ?? 0), (int)$c['id']);
        flash('success', 'Kuulutus kustutatud.');
        redirect(url('employer_jobs'));
    }

    public function candidates(): void
    {
        $c = $this->myCompany();
        $job = Job::find((int)($_GET['job_id'] ?? 0));
        if (!$job || (int)$job['company_id'] !== (int)$c['id']) {
            http_response_code(403);
            exit('Sul puudub õigus selle kuulutuse kandidaate vaadata.');
        }
        render('employer/candidates', [
            'title' => 'Kandidaadid: ' . $job['title'], 'c' => $c, 'job' => $job,
            'apps' => Application::byJob((int)$job['id']), 'statuses' => Application::STATUSES,
        ], 'employer/layout');
    }

    public function appStatus(): void
    {
        $c = $this->myCompany();
        $app = Application::find((int)($_POST['id'] ?? 0));
        $status = $_POST['status'] ?? '';
        if (!$app || (int)$app['company_id'] !== (int)$c['id'] || !in_array($status, Application::STATUSES, true)) {
            http_response_code(403);
            exit('Sul puudub õigus seda kandideerimist muuta.');
        }
        if ($app['status'] !== $status) {
            Application::setStatus((int)$app['id'], $status);
            notify((int)$app['user_id'], 'Kandideerimise „' . $app['title'] . '" staatus: ' . $status, url('applications'));
        }
        flash('success', 'Staatus uuendatud.');
        redirect(url('employer_candidates', ['job_id' => $app['job_id']]));
    }

    public function company(): void
    {
        $c = $this->myCompany();
        if (is_post()) {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $site = trim($_POST['website'] ?? '');
            $err = [];
            if (mb_strlen($name) < 2) $err[] = 'Ettevõtte nimi on kohustuslik.';
            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $err[] = 'Kontakt e-post ei ole korrektne.';
            if ($site !== '' && !preg_match('#^https?://#i', $site)) $err[] = 'Veebileht peab algama http:// või https://.';
            if ($err) {
                foreach ($err as $m) flash('danger', $m);
            } else {
                Company::update((int)$c['id'], [
                    'name' => $name, 'description' => trim($_POST['description'] ?? '') ?: null,
                    'website' => $site ?: null, 'email' => $email ?: null,
                    'phone' => trim($_POST['phone'] ?? '') ?: null, 'city' => trim($_POST['city'] ?? '') ?: null,
                ]);
                if (!empty($_FILES['logo']['name'])) {
                    try {
                        [$stored] = save_upload($_FILES['logo'], 'logos', LOGO_TYPES, LOGO_MAX_SIZE, ROOT_PATH . '/public');
                        if ($c['logo']) @unlink(ROOT_PATH . '/public/logos/' . $c['logo']);
                        Company::setLogo((int)$c['id'], $stored);
                    } catch (RuntimeException $e) {
                        flash('warning', 'Logo: ' . $e->getMessage());
                    }
                }
                flash('success', 'Ettevõtte profiil salvestatud.');
                redirect(url('employer_company'));
            }
        }
        render('employer/company', ['title' => 'Ettevõtte profiil', 'c' => Company::byId((int)$c['id'])], 'employer/layout');
    }

    public function stats(): void
    {
        $c = $this->myCompany();
        $jobs = Job::byCompany((int)$c['id']);
        render('employer/stats', [
            'title' => 'Statistika', 'c' => $c, 'jobs' => $jobs,
            'stats' => Application::companyStats((int)$c['id']), 'chart' => Application::perDay((int)$c['id'], 14),
        ], 'employer/layout');
    }
}
