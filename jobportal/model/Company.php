<?php
class Company
{
    public static function byId(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM companies WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function byUser(int $userId): ?array
    {
        $st = db()->prepare('SELECT * FROM companies WHERE user_id = ?');
        $st->execute([$userId]);
        return $st->fetch() ?: null;
    }

    public static function create(int $userId, string $name): int
    {
        $st = db()->prepare('INSERT INTO companies (user_id, name) VALUES (?,?)');
        $st->execute([$userId, $name]);
        return (int)db()->lastInsertId();
    }

    /** Ettevõtete nimekiri koos aktiivsete kuulutuste arvuga */
    public static function all(string $q = ''): array
    {
        $sql = "SELECT c.*, (SELECT COUNT(*) FROM jobs j WHERE j.company_id = c.id AND j.status='active') AS job_count
                FROM companies c";
        $p = [];
        if ($q !== '') {
            $sql .= ' WHERE c.name LIKE ?';
            $p[] = '%' . $q . '%';
        }
        $st = db()->prepare($sql . ' ORDER BY c.name');
        $st->execute($p);
        return $st->fetchAll();
    }

    public static function update(int $id, array $d): void
    {
        db()->prepare('UPDATE companies SET name=?, description=?, website=?, email=?, phone=?, city=? WHERE id=?')
            ->execute([$d['name'], $d['description'], $d['website'], $d['email'], $d['phone'], $d['city'], $id]);
    }

    public static function setLogo(int $id, string $logo): void
    {
        db()->prepare('UPDATE companies SET logo=? WHERE id=?')->execute([$logo, $id]);
    }

    public static function count(): int
    {
        return (int)db()->query('SELECT COUNT(*) FROM companies')->fetchColumn();
    }
}
