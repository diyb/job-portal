<?php
class Report
{
    public static function create(int $reporterId, int $jobId, string $reason): void
    {
        db()->prepare('INSERT INTO reports (reporter_id, job_id, reason) VALUES (?,?,?)')->execute([$reporterId, $jobId, $reason]);
    }
}
