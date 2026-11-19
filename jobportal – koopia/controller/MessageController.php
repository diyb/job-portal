<?php
class MessageController
{
    public function inbox(): void
    {
        $u = require_login();
        render('messages', ['title' => 'Sõnumid', 'convs' => Message::conversations((int)$u['id'])]);
    }

    public function thread(): void
    {
        $u = require_login();
        $other = User::byId((int)($_GET['with'] ?? 0));
        if (!$other || (int)$other['id'] === (int)$u['id'] || $other['status'] !== 'active') {
            flash('danger', 'Vestluspartnerit ei leitud.');
            redirect(url('messages'));
        }
        // Sõnumeid saavad vahetada ainult tööotsija ja tööandja
        if ($u['role'] === $other['role'] || $u['role'] === 'admin' || $other['role'] === 'admin') {
            http_response_code(403);
            exit('Sõnumeid saab vahetada tööotsija ja tööandja vahel.');
        }
        Message::markRead((int)$u['id'], (int)$other['id']);
        render('thread', [
            'title'  => 'Vestlus: ' . $other['name'],
            'other'  => $other,
            'msgs'   => Message::thread((int)$u['id'], (int)$other['id']),
            'me'     => $u,
        ]);
    }

    public function send(): void
    {
        $u = require_login();
        $other = User::byId((int)($_POST['to'] ?? 0));
        $body = trim($_POST['body'] ?? '');
        if (!$other || $u['role'] === $other['role'] || $other['role'] === 'admin' || $u['role'] === 'admin') {
            http_response_code(403);
            exit('Sõnumit ei saa saata.');
        }
        if ($body !== '') {
            Message::send((int)$u['id'], (int)$other['id'], mb_substr($body, 0, 3000));
            notify((int)$other['id'], 'Uus sõnum: ' . $u['name'], url('thread', ['with' => $u['id']]));
        }
        redirect(url('thread', ['with' => $other['id']]));
    }
}
