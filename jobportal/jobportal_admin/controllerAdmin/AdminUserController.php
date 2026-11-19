<?php
class AdminUserController
{
    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        render('users', ['title' => 'Kasutajad', 'users' => AdminData::users($q), 'q' => $q]);
    }

    /** Administraator ei tohi blokeerida/kustutada iseennast */
    private function target(): array
    {
        $t = User::byId((int)($_POST['id'] ?? 0));
        if (!$t) {
            flash('danger', 'Kasutajat ei leitud.');
            redirect(url('users'));
        }
        if ((int)$t['id'] === (int)current_user()['id']) {
            flash('warning', 'Iseenda konto muutmine ei ole siin lubatud.');
            redirect(url('users'));
        }
        return $t;
    }

    public function toggleBlock(): void
    {
        $t = $this->target();
        $new = $t['status'] === 'active' ? 'blocked' : 'active';
        AdminData::setUserStatus((int)$t['id'], $new);
        flash('success', $new === 'blocked' ? 'Kasutaja blokeeritud.' : 'Kasutaja blokeering eemaldatud.');
        redirect(url('users'));
    }

    public function setRole(): void
    {
        $t = $this->target();
        $role = $_POST['role'] ?? '';
        if (in_array($role, ['user', 'employer', 'admin'], true)) {
            AdminData::setUserRole((int)$t['id'], $role);
            flash('success', 'Roll muudetud.');
        }
        redirect(url('users'));
    }

    public function delete(): void
    {
        $t = $this->target();
        AdminData::deleteUser((int)$t['id']);
        flash('success', 'Kasutaja kustutatud.');
        redirect(url('users'));
    }
}
