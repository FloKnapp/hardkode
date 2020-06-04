<?php

namespace Hardkode\Controller;

use Hardkode\Form\Login;
use Hardkode\Model\User;
use ORM\Exception\NoEntity;
use ORM\Exception\IncompletePrimaryKey;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UserController
 * @package Hardkode\Controller
 */
class UserController extends PageController
{
    /**
     * @return ResponseInterface
     */
    public function login(): ResponseInterface
    {
        $loginForm = $this->createForm(Login::class);

        if ($loginForm->isSubmitted() && $loginForm->isValid()) {

            $user = null;

            try {

                $user = $this->getEntityManager()
                    ->fetch(User::class)
                    ->where('name', '=', $loginForm->getData()['username'])
                    ->andWhere('password', '=', $loginForm->getData()['password'])
                    ->one();

            } catch (NoEntity | IncompletePrimaryKey $e) {
                $this->getLogger()->error($e->getMessage(), ['exception' => $e]);
            }

            if (null === $user) {
                $this->getSession()->setFlashMessage('form.error', 'login.fail');
                return $this->redirect('login');
            }

            $this->getSession()->set('userRole', $user->roles[0]->name);
            $this->getSession()->set('userName', $user->name);
            $this->getSession()->set('userId', $user->id);

            return $this->redirect($this->getSession()->get('referer') ?? '/');

        }

        return $this->render('/user/login.phtml', [
            'form' => $loginForm
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public function logout(): ResponseInterface
    {
        $_SESSION['userName'] = 'xxx';
        $_SESSION['userId']   = 'xxx';
        $_SESSION['userRole'] = 'xxx';

        unset($_SESSION['userName'], $_SESSION['userId'], $_SESSION['userRole']);

        return $this->redirect('/');
    }

    /**
     * @return ResponseInterface
     */
    public function register()
    {
        return $this->render('/user/register.phtml');
    }

    /**
     * @return ResponseInterface
     */
    public function profile()
    {
        return $this->render('/user/profile.phtml');
    }

}