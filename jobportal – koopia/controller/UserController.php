<?php
class UserController
{
    public function profile(): void
    {
        $u = require_login();
        if ($u['role'] === 'employer') {
            redirect(url('employer'));
        }
        if ($u['role'] === 'admin') {
            redirect('jobportal_admin/index.php');
        }
        $id = (int)$u['id'];
        $cvs = CV::byUser($id);
        render('profile', [
            'title'        => 'Minu konto',
            'u'            => $u,
            'applications' => Application::byUser($id, 5),
            'appCount'     => count(Application::byUser($id)),
            'favCount'     => count(Favorite::ids($id)),
            'notifCount'   => unread_counts($id)['notifications'],
            'cvs'          => $cvs,
        ]);
    }

    public function edit(): void
    {
        $u = require_login();
        if (is_post()) {
            $name = trim($_POST['name'] ?? '');
            $err = [];
            if (mb_strlen($name) < 2) $err[] = 'Nimi on liiga lühike.';
            if ($err) {
                foreach ($err as $m) flash('danger', $m);
            } else {
                User::updateProfile((int)$u['id'], $name, trim($_POST['phone'] ?? '') ?: null, trim($_POST['city'] ?? '') ?: null, trim($_POST['about'] ?? '') ?: null);
                flash('success', 'Profiil salvestatud.');
                redirect(url('profile_edit'));
            }
        }
        render('profile_edit', ['title' => 'Muuda profiili', 'u' => $u, 'cvs' => CV::byUser((int)$u['id'])]);
    }

    public function cvUpload(): void
    {
        $u = require_login();
        try {
            if (empty($_FILES['cv'])) throw new RuntimeException('Vali fail.');
            [$stored, $orig, $size] = save_upload($_FILES['cv'], 'cv', CV_TYPES, CV_MAX_SIZE);
            CV::add((int)$u['id'], $stored, $orig, $size);
            flash('success', 'CV üles laaditud.');
        } catch (RuntimeException $e) {
            flash('danger', $e->getMessage());
        }
        redirect(url('profile_edit'));
    }

    public function cvDelete(): void
    {
        $u = require_login();
        CV::delete((int)($_POST['id'] ?? 0), (int)$u['id']);
        flash('success', 'CV kustutatud.');
        redirect(url('profile_edit'));
    }

    /** CV allalaadimine – ainult omanik, kandideerimist näinud tööandja või admin */
    public function cvDownload(): void
    {
        $u = require_login();
        $cv = CV::find((int)($_GET['id'] ?? 0));
        $allowed = false;
        if ($cv) {
            if ((int)$cv['user_id'] === (int)$u['id'] || $u['role'] === 'admin') {
                $allowed = true;
            } elseif ($u['role'] === 'employer') {
                $c = Company::byUser((int)$u['id']);
                $allowed = $c && CV::employerCanView((int)$cv['id'], (int)$c['id']);
            }
        }
        $path = $cv ? UPLOAD_DIR . '/cv/' . $cv['stored_name'] : '';
        if (!$allowed || !is_file($path)) {
            http_response_code(403);
            exit('Faili ei leitud või puudub õigus.');
        }
        $ext = strtolower(pathinfo($cv['stored_name'], PATHINFO_EXTENSION));
        $types = ['pdf' => 'application/pdf', 'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
        header('Content-Disposition: attachment; filename="' . rawurlencode($cv['original_name']) . '"');
        header('X-Content-Type-Options: nosniff');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    public function notifications(): void
    {
        $u = require_login();
        $list = Notification::byUser((int)$u['id']);
        Notification::markAllRead((int)$u['id']);
        render('notifications', ['title' => 'Teavitused', 'list' => $list]);
    }
}
