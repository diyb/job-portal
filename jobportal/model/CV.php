<?php
class CV
{
    public static function byUser(int $userId): array
    {
        $st = db()->prepare('SELECT * FROM cv_files WHERE user_id=? ORDER BY created_at DESC, id DESC');
        $st->execute([$userId]);
        return $st->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $st = db()->prepare('SELECT * FROM cv_files WHERE id=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function add(int $userId, string $stored, string $original, int $size): int
    {
        $st = db()->prepare('INSERT INTO cv_files (user_id, stored_name, original_name, size) VALUES (?,?,?,?)');
        $st->execute([$userId, $stored, $original, $size]);
        return (int)db()->lastInsertId();
    }

    public static function delete(int $id, int $userId): void
    {
        $cv = self::find($id);
        if ($cv && (int)$cv['user_id'] === $userId) {
            @unlink(UPLOAD_DIR . '/cv/' . $cv['stored_name']);
            db()->prepare('DELETE FROM cv_files WHERE id=? AND user_id=?')->execute([$id, $userId]);
        }
    }

    /** Kas tööandja (ettevõte) tohib selle CV-d näha – ainult kui kasutaja on tema kuulutusele kandideerinud selle CV-ga */
    public static function employerCanView(int $cvId, int $companyId): bool
    {
        $st = db()->prepare('SELECT 1 FROM applications a JOIN jobs j ON j.id=a.job_id WHERE a.cv_id=? AND j.company_id=?');
        $st->execute([$cvId, $companyId]);
        return (bool)$st->fetchColumn();
    }
}
