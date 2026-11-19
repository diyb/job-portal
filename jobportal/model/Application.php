<?php
class Application
{
    public const STATUSES = ['Uus', 'Ülevaatamisel', 'Sobiv', 'Tagasi lükatud'];

    public static function exists(int $userId, int $jobId): bool
    {
        $st = db()->prepare('SELECT 1 FROM applications WHERE user_id=? AND job_id=?');
        $st->execute([$userId, $jobId]);
        return (bool)$st->fetchColumn();
    }

    public static function create(int $userId, int $jobId, ?int $cvId, ?string $message): int
    {
        $st = db()->prepare('INSERT INTO applications (user_id, job_id, cv_id, message) VALUES (?,?,?,?)');
        $st->execute([$userId, $jobId, $cvId, $message]);
        return (int)db()->lastInsertId();
    }

    public static function byUser(int $userId, ?int $limit = null): array
    {
        $sql = 'SELECT a.*, j.title, c.id AS company_id, c.name AS company_name, c.logo AS company_logo
                FROM applications a JOIN jobs j ON j.id=a.job_id JOIN companies c ON c.id=j.company_id
                WHERE a.user_id=? ORDER BY a.created_at DESC, a.id DESC' . ($limit ? ' LIMIT ' . (int)$limit : '');
        $st = db()->prepare($sql);
        $st->execute([$userId]);
        return $st->fetchAll();
    }

    public static function byJob(int $jobId): array
    {
        $st = db()->prepare('SELECT a.*, u.name AS user_name, u.email AS user_email, u.phone AS user_phone, u.city AS user_city,
                                    cv.original_name AS cv_name
                             FROM applications a JOIN users u ON u.id=a.user_id
                             LEFT JOIN cv_files cv ON cv.id=a.cv_id
                             WHERE a.job_id=? ORDER BY a.created_at DESC, a.id DESC');
        $st->execute([$jobId]);
        return $st->fetchAll();
    }

    /** Leiab kandideerimise koos tööandja ettevõtte id-ga (õiguste kontrolliks) */
    public static function find(int $id): ?array
    {
        $st = db()->prepare('SELECT a.*, j.title, j.company_id FROM applications a JOIN jobs j ON j.id=a.job_id WHERE a.id=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function setStatus(int $id, string $status): void
    {
        db()->prepare('UPDATE applications SET status=? WHERE id=?')->execute([$status, $id]);
    }

    public static function latestForCompany(int $companyId, int $limit = 5): array
    {
        $st = db()->prepare('SELECT a.*, u.name AS user_name, j.title
                             FROM applications a JOIN users u ON u.id=a.user_id JOIN jobs j ON j.id=a.job_id
                             WHERE j.company_id=? ORDER BY a.created_at DESC, a.id DESC LIMIT ' . (int)$limit);
        $st->execute([$companyId]);
        return $st->fetchAll();
    }

    public static function companyStats(int $companyId): array
    {
        $q = fn(string $sql) => (function () use ($sql, $companyId) {
            $st = db()->prepare($sql);
            $st->execute([$companyId]);
            return (int)$st->fetchColumn();
        })();
        return [
            'active_jobs' => $q("SELECT COUNT(*) FROM jobs WHERE company_id=? AND status='active'"),
            'applicants'  => $q('SELECT COUNT(*) FROM applications a JOIN jobs j ON j.id=a.job_id WHERE j.company_id=?'),
            'new'         => $q("SELECT COUNT(*) FROM applications a JOIN jobs j ON j.id=a.job_id WHERE j.company_id=? AND a.status='Uus'"),
            'suitable'    => $q("SELECT COUNT(*) FROM applications a JOIN jobs j ON j.id=a.job_id WHERE j.company_id=? AND a.status='Sobiv'"),
        ];
    }

    /** Kandideerimised viimase 7 päeva lõikes (statistika graafikule) */
    public static function perDay(int $companyId, int $days = 7): array
    {
        $st = db()->prepare('SELECT DATE(a.created_at) d, COUNT(*) n FROM applications a JOIN jobs j ON j.id=a.job_id
                             WHERE j.company_id=? AND a.created_at >= ? GROUP BY DATE(a.created_at)');
        $from = date('Y-m-d', strtotime('-' . ($days - 1) . ' days'));
        $st->execute([$companyId, $from]);
        $map = array_column($st->fetchAll(), 'n', 'd');
        $out = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $out[] = ['label' => date('d.m', strtotime($d)), 'n' => (int)($map[$d] ?? 0)];
        }
        return $out;
    }
}
