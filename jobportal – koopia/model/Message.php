<?php
class Message
{
    /** Vestluspartnerite nimekiri viimase sõnumiga */
    public static function conversations(int $userId): array
    {
        $st = db()->prepare("SELECT u.id AS other_id, u.name AS other_name, m.body, m.created_at,
                                    (SELECT COUNT(*) FROM messages x WHERE x.sender_id=u.id AND x.receiver_id=? AND x.is_read=0) AS unread
                             FROM messages m
                             JOIN users u ON u.id = IF(m.sender_id = ?, m.receiver_id, m.sender_id)
                             WHERE m.id IN (
                                 SELECT MAX(id) FROM messages WHERE sender_id=? OR receiver_id=?
                                 GROUP BY IF(sender_id=?, receiver_id, sender_id))
                             ORDER BY m.created_at DESC, m.id DESC");
        $st->execute([$userId, $userId, $userId, $userId, $userId]);
        return $st->fetchAll();
    }

    public static function thread(int $userId, int $otherId): array
    {
        $st = db()->prepare('SELECT * FROM messages WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) ORDER BY id');
        $st->execute([$userId, $otherId, $otherId, $userId]);
        return $st->fetchAll();
    }

    public static function markRead(int $userId, int $otherId): void
    {
        db()->prepare('UPDATE messages SET is_read=1 WHERE receiver_id=? AND sender_id=?')->execute([$userId, $otherId]);
    }

    public static function send(int $from, int $to, string $body): void
    {
        db()->prepare('INSERT INTO messages (sender_id, receiver_id, body) VALUES (?,?,?)')->execute([$from, $to, $body]);
    }
}
