<?php
/**
 * Marsruutimine: ?page=<nimi>  ->  [Kontroller, meetod, valikud]
 * Valikud: 'role' => nõutav roll, 'post' => ainult POST (CSRF kontrollitakse alati kõigil POST päringutel)
 */
return [
    // Avalikud lehed
    'start'      => ['JobController', 'home'],
    'jobs'       => ['JobController', 'index'],
    'job'        => ['JobController', 'show'],
    'report'     => ['JobController', 'report', ['post' => true]],
    'companies'  => ['CompanyController', 'index'],
    'company'    => ['CompanyController', 'show'],

    // Autentimine
    'login'      => ['AuthController', 'login'],
    'register'   => ['AuthController', 'register'],
    'logout'     => ['AuthController', 'logout', ['post' => true]],

    // Tööotsija
    'profile'        => ['UserController', 'profile'],
    'profile_edit'   => ['UserController', 'edit'],
    'cv_upload'      => ['UserController', 'cvUpload', ['post' => true]],
    'cv_delete'      => ['UserController', 'cvDelete', ['post' => true]],
    'cv_download'    => ['UserController', 'cvDownload'],
    'notifications'  => ['UserController', 'notifications'],
    'apply'          => ['ApplicationController', 'apply', ['post' => true]],
    'applications'   => ['ApplicationController', 'mine'],
    'favorites'      => ['FavoriteController', 'index'],
    'favorite_toggle'=> ['FavoriteController', 'toggle', ['post' => true]],
    'messages'       => ['MessageController', 'inbox'],
    'thread'         => ['MessageController', 'thread'],
    'message_send'   => ['MessageController', 'send', ['post' => true]],

    // Tööandja
    'employer'              => ['EmployerController', 'dashboard',   ['role' => 'employer']],
    'employer_jobs'         => ['EmployerController', 'jobs',        ['role' => 'employer']],
    'employer_job_form'     => ['EmployerController', 'jobForm',     ['role' => 'employer']],
    'employer_job_save'     => ['EmployerController', 'jobSave',     ['role' => 'employer', 'post' => true]],
    'employer_job_delete'   => ['EmployerController', 'jobDelete',   ['role' => 'employer', 'post' => true]],
    'employer_candidates'   => ['EmployerController', 'candidates',  ['role' => 'employer']],
    'employer_app_status'   => ['EmployerController', 'appStatus',   ['role' => 'employer', 'post' => true]],
    'employer_company'      => ['EmployerController', 'company',     ['role' => 'employer']],
    'employer_stats'        => ['EmployerController', 'stats',       ['role' => 'employer']],
];
