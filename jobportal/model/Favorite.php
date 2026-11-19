<?php
class Favorite
{
    public static function has(int $userId, int $jobId): bool
    {
        $st = db()->prepare('SELECT 1 FROM favorites WHERE user_id=? AND job_id=?');
        $st->execute([$userId, $jobId]);
        return (bool)$st->fetchColumn();
    }

    public static function toggle(int $userId, int $jobId): bool
    {
        if (self::has($userId, $jobId)) {
            db()->prepare('DELETE FROM favorites WHERE user_id=? AND job_id=?')->execute([$userId, $jobId]);
            return false;
        }
        db()->prepare('INSERT INTO favorites (user_id, job_id) VALUES (?,?)')->execute([$userId, $jobId]);
        return true;
    }

    /** Kasutaja lemmikute job_id-d (kiireks südamekese märkimiseks nimekirjades) */
    public static function ids(int $userId): array
    {
        $st = db()->prepare('SELECT job_id FROM favorites WHERE user_id=?');
        $st->execute([$userId]);
        return array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN));
    }

    public static function jobs(int $userId): array
    {
        $st = db()->prepare("SELECT j.*, c.name AS company_name, c.logo AS company_logo, l.name AS location_name, cat.name AS category_name
                             FROM favorites f JOIN jobs j ON j.id=f.job_id JOIN companies c ON c.id=j.company_id
                             LEFT JOIN job_locations l ON l.id=j.location_id
                             LEFT JOIN categories cat ON cat.id=j.category_id
                             WHERE f.user_id=? AND j.status='active' ORDER BY f.created_at DESC");
        $st->execute([$userId]);
        return $st->fetchAll();
    }
}
