<?php
class Job
{
    private const SELECT = "SELECT j.*, c.name AS company_name, c.logo AS company_logo, c.city AS company_city,
                                   cat.name AS category_name, l.name AS location_name
                            FROM jobs j
                            JOIN companies c ON c.id = j.company_id
                            LEFT JOIN categories cat ON cat.id = j.category_id
                            LEFT JOIN job_locations l ON l.id = j.location_id";

    private static function where(array $f, array &$p): string
    {
        $w = ["j.status = 'active'"];
        if (!empty($f['q'])) {
            $like = '%' . $f['q'] . '%';
            $w[] = '(j.title LIKE ? OR j.description LIKE ? OR c.name LIKE ?)';
            array_push($p, $like, $like, $like);
        }
        if (!empty($f['category'])) {
            $w[] = 'j.category_id = ?';
            $p[] = (int)$f['category'];
        }
        if (!empty($f['location'])) {
            $w[] = 'j.location_id = ?';
            $p[] = (int)$f['location'];
        }
        if (!empty($f['type'])) {
            $w[] = 'j.employment_type = ?';
            $p[] = $f['type'];
        }
        // Palgavahemik (valitud kuulutuse kõrgeima/ainsa palga järgi)
        $eff = 'COALESCE(j.salary_max, j.salary_min, 0)';
        $salary = (array)($f['salary'] ?? []);
        $or = [];
        if (in_array('1', $salary, true)) $or[] = "($eff > 0 AND $eff <= 1000)";
        if (in_array('2', $salary, true)) $or[] = "($eff > 1000 AND $eff <= 2000)";
        if (in_array('3', $salary, true)) $or[] = "($eff > 2000)";
        if ($or) {
            $w[] = '(' . implode(' OR ', $or) . ')';
        }
        return ' WHERE ' . implode(' AND ', $w);
    }

    public static function count(array $f): int
    {
        $p = [];
        $sql = 'SELECT COUNT(*) FROM jobs j JOIN companies c ON c.id = j.company_id' . self::where($f, $p);
        $st = db()->prepare($sql);
        $st->execute($p);
        return (int)$st->fetchColumn();
    }

    public static function search(array $f, int $offset, int $limit): array
    {
        $p = [];
        $order = ($f['sort'] ?? '') === 'salary' ? 'COALESCE(j.salary_max, j.salary_min, 0) DESC, j.id DESC' : 'j.created_at DESC, j.id DESC';
        $sql = self::SELECT . self::where($f, $p) . " ORDER BY $order LIMIT " . (int)$limit . ' OFFSET ' . (int)$offset;
        $st = db()->prepare($sql);
        $st->execute($p);
        return $st->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $st = db()->prepare(self::SELECT . ' WHERE j.id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function latest(int $n = 6): array
    {
        return db()->query(self::SELECT . " WHERE j.status='active' ORDER BY j.created_at DESC, j.id DESC LIMIT " . (int)$n)->fetchAll();
    }

    public static function byCompany(int $companyId, bool $onlyActive = false): array
    {
        $sql = "SELECT j.*, c.name AS company_name, c.logo AS company_logo, l.name AS location_name, cat.name AS category_name,
                       (SELECT COUNT(*) FROM applications a WHERE a.job_id = j.id) AS applicants
                FROM jobs j
                JOIN companies c ON c.id = j.company_id
                LEFT JOIN job_locations l ON l.id = j.location_id
                LEFT JOIN categories cat ON cat.id = j.category_id
                WHERE j.company_id = ?" . ($onlyActive ? " AND j.status='active'" : '') . ' ORDER BY j.created_at DESC';
        $st = db()->prepare($sql);
        $st->execute([$companyId]);
        return $st->fetchAll();
    }

    public static function save(?int $id, int $companyId, array $d): int
    {
        $vals = [$d['category_id'], $d['location_id'], $d['title'], $d['description'], $d['requirements'],
                 $d['salary_min'], $d['salary_max'], $d['employment_type'], $d['start_date'], $d['status']];
        if ($id) {
            $st = db()->prepare('UPDATE jobs SET category_id=?, location_id=?, title=?, description=?, requirements=?,
                                 salary_min=?, salary_max=?, employment_type=?, start_date=?, status=? WHERE id=? AND company_id=?');
            $st->execute([...$vals, $id, $companyId]);
            return $id;
        }
        $st = db()->prepare('INSERT INTO jobs (category_id, location_id, title, description, requirements,
                             salary_min, salary_max, employment_type, start_date, status, company_id)
                             VALUES (?,?,?,?,?,?,?,?,?,?,?)');
        $st->execute([...$vals, $companyId]);
        return (int)db()->lastInsertId();
    }

    public static function delete(int $id, int $companyId): void
    {
        db()->prepare('DELETE FROM jobs WHERE id=? AND company_id=?')->execute([$id, $companyId]);
    }

    public static function stats(): array
    {
        return [
            'jobs'      => (int)db()->query("SELECT COUNT(*) FROM jobs WHERE status='active'")->fetchColumn(),
            'companies' => (int)db()->query('SELECT COUNT(*) FROM companies')->fetchColumn(),
            'users'     => (int)db()->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn(),
        ];
    }
}
