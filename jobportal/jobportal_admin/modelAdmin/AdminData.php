<?php
/** Admini päringud: kasutajad, ettevõtted, kuulutused, kategooriad, kandideerimised, kaebused, seaded */
class AdminData
{
    private static function all(string $sql, array $p = []): array
    {
        $st = db()->prepare($sql);
        $st->execute($p);
        return $st->fetchAll();
    }

    private static function run(string $sql, array $p = []): void
    {
        db()->prepare($sql)->execute($p);
    }

    /* Kasutajad */
    public static function users(string $q = ''): array
    {
        $sql = 'SELECT * FROM users';
        $p = [];
        if ($q !== '') {
            $sql .= ' WHERE name LIKE ? OR email LIKE ?';
            $p = ['%' . $q . '%', '%' . $q . '%'];
        }
        return self::all($sql . ' ORDER BY id DESC', $p);
    }

    public static function setUserStatus(int $id, string $status): void
    {
        self::run('UPDATE users SET status=? WHERE id=?', [$status, $id]);
    }

    public static function setUserRole(int $id, string $role): void
    {
        self::run('UPDATE users SET role=? WHERE id=?', [$role, $id]);
    }

    public static function deleteUser(int $id): void
    {
        self::run('DELETE FROM users WHERE id=?', [$id]);
    }

    /* Ettevõtted */
    public static function companies(): array
    {
        return self::all('SELECT c.*, u.email AS owner_email, u.status AS owner_status,
                          (SELECT COUNT(*) FROM jobs j WHERE j.company_id=c.id) AS job_count
                          FROM companies c JOIN users u ON u.id=c.user_id ORDER BY c.id DESC');
    }

    public static function deleteCompany(int $id): void
    {
        self::run('DELETE FROM companies WHERE id=?', [$id]);
    }

    /* Kuulutused */
    public static function jobs(string $q = ''): array
    {
        $sql = 'SELECT j.*, c.name AS company_name, cat.name AS category_name,
                (SELECT COUNT(*) FROM applications a WHERE a.job_id=j.id) AS applicants
                FROM jobs j JOIN companies c ON c.id=j.company_id LEFT JOIN categories cat ON cat.id=j.category_id';
        $p = [];
        if ($q !== '') {
            $sql .= ' WHERE j.title LIKE ? OR c.name LIKE ?';
            $p = ['%' . $q . '%', '%' . $q . '%'];
        }
        return self::all($sql . ' ORDER BY j.id DESC', $p);
    }

    public static function updateJob(int $id, array $d): void
    {
        self::run('UPDATE jobs SET title=?, description=?, requirements=?, category_id=?, location_id=?, status=? WHERE id=?',
            [$d['title'], $d['description'], $d['requirements'], $d['category_id'], $d['location_id'], $d['status'], $id]);
    }

    public static function setJobStatus(int $id, string $status): void
    {
        self::run('UPDATE jobs SET status=? WHERE id=?', [$status, $id]);
    }

    public static function deleteJob(int $id): void
    {
        self::run('DELETE FROM jobs WHERE id=?', [$id]);
    }

    /* Kategooriad ja asukohad (table on kindel lubatud nimekiri) */
    private static function table(string $type): string
    {
        return $type === 'location' ? 'job_locations' : 'categories';
    }

    public static function lookup(string $type): array
    {
        $t = self::table($type);
        $fk = $type === 'location' ? 'location_id' : 'category_id';
        return self::all("SELECT x.*, (SELECT COUNT(*) FROM jobs j WHERE j.$fk = x.id) AS uses FROM $t x ORDER BY x.name");
    }

    public static function saveLookup(string $type, ?int $id, string $name): void
    {
        $t = self::table($type);
        if ($id) {
            self::run("UPDATE $t SET name=? WHERE id=?", [$name, $id]);
        } else {
            self::run("INSERT INTO $t (name) VALUES (?)", [$name]);
        }
    }

    public static function deleteLookup(string $type, int $id): void
    {
        self::run('DELETE FROM ' . self::table($type) . ' WHERE id=?', [$id]);
    }

    /* Kandideerimised */
    public static function applications(string $status = ''): array
    {
        $sql = 'SELECT a.*, u.name AS user_name, u.email AS user_email, j.title, c.name AS company_name
                FROM applications a JOIN users u ON u.id=a.user_id JOIN jobs j ON j.id=a.job_id JOIN companies c ON c.id=j.company_id';
        $p = [];
        if ($status !== '') {
            $sql .= ' WHERE a.status=?';
            $p[] = $status;
        }
        return self::all($sql . ' ORDER BY a.id DESC LIMIT 300', $p);
    }

    /* Kaebused */
    public static function reports(): array
    {
        return self::all('SELECT r.*, u.name AS reporter_name, j.title AS job_title
                          FROM reports r JOIN users u ON u.id=r.reporter_id LEFT JOIN jobs j ON j.id=r.job_id
                          ORDER BY r.status=\'open\' DESC, r.id DESC');
    }

    public static function setReportStatus(int $id, string $status): void
    {
        self::run('UPDATE reports SET status=? WHERE id=?', [$status, $id]);
    }

    /* Seaded */
    public static function saveSetting(string $k, string $v): void
    {
        self::run('INSERT INTO settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)', [$k, $v]);
    }
}
