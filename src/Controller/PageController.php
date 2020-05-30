<?php

namespace Hardkode\Controller;

use Hardkode\Model\User;
use Psr\Http\Message\ResponseInterface;

/**
 * Class PageController
 * @package Hardkode\Controller
 */
class PageController extends AbstractController
{

    /**
     * @return ResponseInterface
     */
    public function index()
    {
        $result = $this->getEntityManager()->fetch(User::class)->where('name', '=', 'Faulancer')->one();

        var_dump($result);

        return $this->render('/page/index.phtml');
    }

    /**
     * @param string $id
     * @return ResponseInterface
     */
    public function article(string $id)
    {
        return $this->render('/page/article.phtml', [
            'title' => $id
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public function tutorials()
    {
        return $this->render('/page/tutorials.phtml', [
            'title' => 'Tutorials'
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public function tools()
    {
        return $this->render('/page/tools.phtml', [
            'title' => 'Tutorials'
        ]);
    }

    /**
     * @return ResponseInterface
     */
    public function contact()
    {
        return $this->render('/page/contact.phtml');
    }

    /**
     * @return void
     */
    public function addDefaultAssets()
    {
        $this->getRenderer()->addStylesheet('/css/main.css');
        $this->getRenderer()->addStylesheet('/css/navigation.css');
        $this->getRenderer()->addScript('/js/engine.js');
    }

}