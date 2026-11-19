<?php
class Location
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM job_locations ORDER BY name')->fetchAll();
    }
}
