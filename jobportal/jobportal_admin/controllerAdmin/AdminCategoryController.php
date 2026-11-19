<?php
class AdminCategoryController
{
    private function type(): string
    {
        $t = $_POST['type'] ?? $_GET['type'] ?? 'category';
        return $t === 'location' ? 'location' : 'category';
    }

    public function index(): void
    {
        render('categories', [
            'title'      => 'Kategooriad',
            'categories' => AdminData::lookup('category'),
            'locations'  => AdminData::lookup('location'),
        ]);
    }

    public function save(): void
    {
        $name = trim($_POST['name'] ?? '');
        $id = (int)($_POST['id'] ?? 0) ?: null;
        if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
            flash('danger', 'Nimi peab olema 2–100 märki.');
        } else {
            try {
                AdminData::saveLookup($this->type(), $id, $name);
                flash('success', 'Salvestatud.');
            } catch (PDOException $e) {
                flash('danger', 'Selline nimi on juba olemas.');
            }
        }
        redirect(url('categories'));
    }

    public function delete(): void
    {
        AdminData::deleteLookup($this->type(), (int)($_POST['id'] ?? 0));
        flash('success', 'Kustutatud.');
        redirect(url('categories'));
    }
}
