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
    'tools' => [
        'path'   => '/tools',
        'class'  => PageController::class,
        'action' => 'tools',
        'link'   => 'Tools'
    ],
    'contact' => [
        'path'   => '/contact',
        'class'  => UserController::class,
        'action' => 'contact',
        'link'   => 'Kontakt'
    ],
    'article' => [
        'path'   => '/article/:id',
        'class'  => PageController::class,
        'action' => 'article'
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
        'link'   => 'Artikelliste'
    ],
    'admin:article' => [
        'path'   => '/admin/article/:id',
        'class'  => AdminController::class,
        'action' => 'article',
        'link'   => 'Details'
    ],
    'admin:article:create' => [
        'path'   => '/admin/article/create',
        'class'  => AdminController::class,
        'action' => 'articleCreate',
        'link'   => 'Artikel erstellen'
    ],
    'admin:article:edit' => [
        'path'   => '/admin/article/',
        'class'  => AdminController::class,
        'action' => 'articleCreate',
        'link'   => 'Artikel editieren'
    ]
];