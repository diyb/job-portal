<?php
class User
{
    public static function byId(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM users WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function byEmail(string $email): ?array
    {
        $st = db()->prepare('SELECT * FROM users WHERE email = ?');
        $st->execute([$email]);
        return $st->fetch() ?: null;
    }

    public static function create(string $name, string $email, string $password, string $role): int
    {
        $st = db()->prepare('INSERT INTO users (name, email, password, role) VALUES (?,?,?,?)');
        $st->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
        return (int)db()->lastInsertId();
    }

    public static function updateProfile(int $id, string $name, ?string $phone, ?string $city, ?string $about): void
    {
        db()->prepare('UPDATE users SET name=?, phone=?, city=?, about=? WHERE id=?')
            ->execute([$name, $phone, $city, $about, $id]);
    }

    public static function updatePassword(int $id, string $password): void
    {
        db()->prepare('UPDATE users SET password=? WHERE id=?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
    }
}
