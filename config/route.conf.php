<?php

use Hardkode\Controller\PageController;
use Hardkode\Controller\UserController;
use Hardkode\Controller\AdminController;

return [
    'index' => [
        'path'   => '/',
        'class'  => PageController::class,
        'action' => 'index',
        'link'   => 'Startseite'
    ],
    'tutorials' => [
        'path'   => '/tutorials',
        'class'  => PageController::class,
        'action' => 'tutorials',
        'link'   => 'Tutorials'
    ],
    'downloads' => [
        'path'   => '/downloads',
        'class'  => PageController::class,
        'action' => 'tools',
        'link'   => 'Downloads'
    ],
    'contact' => [
        'path'   => '/contact',
        'class'  => PageController::class,
        'action' => 'contact',
        'link'   => 'Kontakt'
    ],
    'impress' => [
        'path'   => '/impress',
        'class'  => PageController::class,
        'action' => 'impress',
        'link'   => 'Impressum'
    ],
    'article' => [
        'path'   => '/article/:id',
        'class'  => PageController::class,
        'action' => 'article'
    ],
    'profile' => [
        'path'   => '/profile',
        'class'  => UserController::class,
        'action' => 'profile',
        'link'   => '<div id="user-profile-badge"><span class="font-small">Eingeloggt als</span><br />%s</div>'
    ],
    'login' => [
        'path'   => '/login',
        'class'  => UserController::class,
        'action' => 'login',
        'link'   => 'Login'
    ],
    'logout' => [
        'path'   => '/logout',
        'class'  => UserController::class,
        'action' => 'logout',
        'link'   => 'Logout'
    ],
    'register' => [
        'path'   => '/register',
        'class'  => UserController::class,
        'action' => 'register',
        'link'   => 'Registrieren'
    ],
    'api:upload' => [
        'path' => '/upload',
        'class' => \Hardkode\Controller\UploadController::class,
        'action' => 'process',
        'link' => 'Upload'
    ],
    'admin:dashboard' => [
        'path'   => '/admin',
        'class'  => AdminController::class,
        'action' => 'dashboard',
        'link'   => 'Dashboard'
    ],
    'admin:articles' => [
        'path'   => '/admin/articles',
        'class'  => AdminController::class,
        'action' => 'articles',
        'link'   => 'Artikel'
    ],
    'admin:article' => [
        'path'   => '/admin/article/:id',
        'class'  => AdminController::class,
        'action' => 'article',
        'link'   => '%s',
        'constraints' => [
            'id' => '\d+'
        ]
    ],
    'admin:article:create' => [
        'path'   => '/admin/article/create',
        'class'  => AdminController::class,
        'action' => 'articleCreate',
        'link'   => 'Artikel erstellen'
    ],
    'admin:article:edit' => [
        'path'   => '/admin/article/:id/edit',
        'class'  => AdminController::class,
        'action' => 'articleEdit',
        'link'   => 'Artikel editieren'
    ],
    'admin:tools' => [
        'path'   => '/admin/tools',
        'class'  => AdminController::class,
        'action' => 'tools',
        'link'   => 'Tools'
    ]
];