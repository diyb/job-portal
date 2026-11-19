<?php
class CompanyController
{
    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        render('companies', ['title' => 'Ettevõtted', 'companies' => Company::all($q), 'q' => $q]);
    }

    public function show(): void
    {
        $c = Company::byId((int)($_GET['id'] ?? 0));
        if (!$c) {
            http_response_code(404);
            render('404', ['title' => 'Ettevõtet ei leitud']);
            return;
        }
        $u = current_user();
        render('company', [
            'title'  => $c['name'],
            'c'      => $c,
            'jobs'   => Job::byCompany((int)$c['id'], true),
            'favIds' => $u ? Favorite::ids((int)$u['id']) : [],
        ]);
    }
}
