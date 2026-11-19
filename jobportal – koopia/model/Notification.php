<?php
class Notification
{
    public static function byUser(int $userId, int $limit = 50): array
    {
        $st = db()->prepare('SELECT * FROM notifications WHERE user_id=? ORDER BY id DESC LIMIT ' . (int)$limit);
        $st->execute([$userId]);
        return $st->fetchAll();
    }

    public static function markAllRead(int $userId): void
    {
        db()->prepare('UPDATE notifications SET is_read=1 WHERE user_id=?')->execute([$userId]);
    }
}
