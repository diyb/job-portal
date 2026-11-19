<?php
class AdminCompanyController
{
    public function index(): void
    {
        render('companies', ['title' => 'Ettevõtted', 'companies' => AdminData::companies()]);
    }

    public function delete(): void
    {
        AdminData::deleteCompany((int)($_POST['id'] ?? 0));
        flash('success', 'Ettevõte ja selle kuulutused kustutatud.');
        redirect(url('companies'));
    }
}
