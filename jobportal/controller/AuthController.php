<?php
class AuthController
{
    public function login(): void
    {
        if (current_user()) {
            redirect(url('profile'));
        }
        $email = '';
        if (is_post()) {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $u = User::byEmail($email);
            if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
                if ($u['status'] !== 'active') {
                    flash('danger', 'Sinu konto on blokeeritud. Võta ühendust administraatoriga.');
                } else {
                    login_user($u);
                    flash('success', 'Tere tulemast tagasi, ' . $u['name'] . '!');
                    redirect(match ($u['role']) {
                        'employer' => url('employer'),
                        'admin'    => 'jobportal_admin/index.php',
                        default    => url('profile'),
                    });
                }
            } else {
                flash('danger', 'Vale e-post või parool.');
            }
        }
        render('login', ['title' => 'Logi sisse', 'email' => $email]);
    }

    public function register(): void
    {
        if (current_user()) {
            redirect(url('profile'));
        }
        $old = ['name' => '', 'email' => '', 'role' => 'user', 'company' => ''];
        if (is_post()) {
            $old = [
                'name'    => trim($_POST['name'] ?? ''),
                'email'   => strtolower(trim($_POST['email'] ?? '')),
                'role'    => ($_POST['role'] ?? 'user') === 'employer' ? 'employer' : 'user',
                'company' => trim($_POST['company'] ?? ''),
            ];
            $pass = $_POST['password'] ?? '';
            $err = [];
            if (mb_strlen($old['name']) < 2) $err[] = 'Nimi on liiga lühike.';
            if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $err[] = 'E-posti aadress ei ole korrektne.';
            if (strlen($pass) < 8) $err[] = 'Parool peab olema vähemalt 8 märki.';
            if ($pass !== ($_POST['password2'] ?? '')) $err[] = 'Paroolid ei kattu.';
            if ($old['role'] === 'employer' && mb_strlen($old['company']) < 2) $err[] = 'Sisesta ettevõtte nimi.';
            if (!$err && User::byEmail($old['email'])) $err[] = 'Selle e-postiga konto on juba olemas.';

            if (!$err) {
                $pdo = db();
                $pdo->beginTransaction();
                try {
                    $id = User::create($old['name'], $old['email'], $pass, $old['role']);
                    if ($old['role'] === 'employer') {
                        Company::create($id, $old['company']);
                    }
                    $pdo->commit();
                } catch (Throwable $t) {
                    $pdo->rollBack();
                    throw $t;
                }
                login_user(['id' => $id]);
                notify($id, 'Tere tulemast JobPortali!', url($old['role'] === 'employer' ? 'employer' : 'profile'));
                flash('success', 'Konto loodud. Tere tulemast!');
                redirect(url($old['role'] === 'employer' ? 'employer_company' : 'profile'));
            }
            foreach ($err as $m) {
                flash('danger', $m);
            }
        }
        render('register', ['title' => 'Registreeru', 'old' => $old]);
    }

    public function logout(): void
    {
        logout_user();
        session_start();
        flash('success', 'Oled välja logitud.');
        redirect(url('start'));
    }
}
