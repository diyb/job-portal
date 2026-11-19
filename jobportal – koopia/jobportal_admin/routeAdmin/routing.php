<?php
return [
    'login'     => ['AdminAuthController', 'login', ['public' => true]],
    'logout'    => ['AdminAuthController', 'logout', ['post' => true]],
    'dashboard' => ['AdminDashboardController', 'index'],

    'users'          => ['AdminUserController', 'index'],
    'user_block'     => ['AdminUserController', 'toggleBlock', ['post' => true]],
    'user_role'      => ['AdminUserController', 'setRole', ['post' => true]],
    'user_delete'    => ['AdminUserController', 'delete', ['post' => true]],

    'companies'      => ['AdminCompanyController', 'index'],
    'company_delete' => ['AdminCompanyController', 'delete', ['post' => true]],

    'jobs'           => ['AdminJobController', 'index'],
    'job_edit'       => ['AdminJobController', 'edit'],
    'job_toggle'     => ['AdminJobController', 'toggle', ['post' => true]],
    'job_delete'     => ['AdminJobController', 'delete', ['post' => true]],

    'categories'     => ['AdminCategoryController', 'index'],
    'category_save'  => ['AdminCategoryController', 'save', ['post' => true]],
    'category_delete'=> ['AdminCategoryController', 'delete', ['post' => true]],

    'applications'   => ['AdminApplicationController', 'index'],

    'reports'        => ['AdminReportController', 'index'],
    'report_status'  => ['AdminReportController', 'setStatus', ['post' => true]],

    'settings'       => ['AdminSettingsController', 'index'],
    'settings_save'  => ['AdminSettingsController', 'save', ['post' => true]],
];
