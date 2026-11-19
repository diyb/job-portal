<?php
class Category
{
    public static function all(): array
    {
        return db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    }
}
