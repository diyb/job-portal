<?php
class AdminDashboardController
{
    public function index(): void
    {
        render('dashboard', ['title' => 'Dashboard', 't' => AdminStats::totals(), 'recent' => AdminStats::recent()]);
    }
}
