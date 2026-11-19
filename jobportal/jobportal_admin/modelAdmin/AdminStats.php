<?php
class AdminStats
{
    private static function n(string $sql): int
    {
        return (int)db()->query($sql)->fetchColumn();
    }

    public static function totals(): array
    {
        return [
            'users'        => self::n("SELECT COUNT(*) FROM users WHERE role='user'"),
            'companies'    => self::n('SELECT COUNT(*) FROM companies'),
            'jobs'         => self::n('SELECT COUNT(*) FROM jobs'),
            'applications' => self::n('SELECT COUNT(*) FROM applications'),
            'reports_open' => self::n("SELECT COUNT(*) FROM reports WHERE status='open'"),
        ];
    }

    /** Viimased tegevused: uued kuulutused ja kasutajad */
    public static function recent(): array
    {
        $jobs = db()->query("SELECT 'Uus töökuulutus' AS label, j.title AS what, j.created_at FROM jobs j ORDER BY j.created_at DESC, j.id DESC LIMIT 4")->fetchAll();
        $users = db()->query("SELECT 'Uus kasutaja' AS label, u.email AS what, u.created_at FROM users u ORDER BY u.created_at DESC, u.id DESC LIMIT 4")->fetchAll();
        $all = array_merge($jobs, $users);
        usort($all, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));
        return array_slice($all, 0, 6);
    }
}
