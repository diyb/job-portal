<?php
class AdminAuthController
{
    public function login(): void
    {
        $u = current_user();
        if ($u && $u['role'] === 'admin') {
            redirect(url('dashboard'));
        }
        $email = '';
        if (is_post()) {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $found = User::byEmail($email);
            if ($found && $found['role'] === 'admin' && $found['status'] === 'active' && password_verify($_POST['password'] ?? '', $found['password'])) {
                login_user($found);
                redirect(url('dashboard'));
            }
            flash('danger', 'Vale e-post või parool, või puudub admini õigus.');
        }
        render('login', ['title' => 'Admin sisselogimine', 'email' => $email], 'bare');
    }

    public function logout(): void
    {
        logout_user();
        session_start();
        redirect(url('login'));
    }
}
