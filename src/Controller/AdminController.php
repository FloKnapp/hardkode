<?php

namespace Hardkode\Controller;

use Hardkode\Exception\PermissionException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AdminController
 * @package Hardkode\Controller
 */
class AdminController extends PageController
{

    /**
     * @return ResponseInterface
     *
     * @throws PermissionException
     */
    public function dashboard()
    {
        $this->requiresPermission('admin');

        return $this->render('/admin/dashboard.phtml');
    }

}