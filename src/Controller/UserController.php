<?php

namespace Hardkode\Controller;

use Hardkode\Form\Login;
use Hardkode\Model\User;
use Nyholm\Psr7\Factory\Psr17Factory;
use ORM\Exception\IncompletePrimaryKey;
use ORM\Exception\NoEntity;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Class UserController
 * @package Hardkode\Controller
 */
class UserController extends PageController
{

    /**
     * @return ResponseInterface
     */
    public function login()
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
     * @return void
     */
    public function logout()
    {
        $_SESSION['userName'] = 'xxx';
        $_SESSION['userId']   = 'xxx';
        $_SESSION['userRole'] = 'xxx';

        unset($_SESSION['userName'], $_SESSION['userId'], $_SESSION['userRole']);

        $this->redirect('/');
    }

    public function register()
    {

    }

    public function profile()
    {
        return $this->render('/user/profile.phtml');
    }

}