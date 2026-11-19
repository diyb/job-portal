<?php
class FavoriteController
{
    public function index(): void
    {
        $u = require_login();
        $jobs = Favorite::jobs((int)$u['id']);
        render('favorites', ['title' => 'Lemmikud', 'jobs' => $jobs, 'favIds' => array_map(fn($j) => (int)$j['id'], $jobs)]);
    }

    public function toggle(): void
    {
        $u = require_login();
        $jobId = (int)($_POST['job_id'] ?? 0);
        if (Job::find($jobId)) {
            $added = Favorite::toggle((int)$u['id'], $jobId);
            flash('success', $added ? 'Lisatud lemmikutesse.' : 'Eemaldatud lemmikutest.');
        }
        // Tagasi samale lehele (ainult sama saidi sisene suunamine)
        $back = $_POST['back'] ?? '';
        redirect(preg_match('/^index\.php\?[A-Za-z0-9_=&%.\-\[\]]*$/', $back) ? $back : url('favorites'));
    }
}
