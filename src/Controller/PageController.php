<?php

namespace Hardkode\Controller;

use Hardkode\Form\Contact;
use Hardkode\Model\Article;
use Hardkode\Model\User;
use ORM\Exception\IncompletePrimaryKey;
use ORM\Exception\NoEntity;
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
        $articleList = [];

        try {

            $articleList = $this->getEntityManager()
                ->fetch(Article::class)
                ->orderBy('insertedAt', 'DESC')
                ->all(5);

        } catch (IncompletePrimaryKey | NoEntity $e) {
            $this->getLogger()->error($e->getMessage(), ['exception' => $e]);
        } catch (\Throwable $t) {
            $this->getLogger()->critical($t->getMessage(), ['exception' => $t]);
        }

        return $this->render('/page/index.phtml', [
            'articleList' => $articleList
        ]);
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
        $form = $this->createForm(Contact::class);

        if ($form->isSubmitted() && $form->isValid()) {
            echo "Ja";
        }

        return $this->render('/page/contact.phtml', [
            'form' => $form
        ]);
    }

    /**
     * @return void
     */
    public function addDefaultAssets()
    {
        $this->getRenderer()->addStylesheet('/css/main.css');
        $this->getRenderer()->addStylesheet('/css/form.css');
        $this->getRenderer()->addStylesheet('/css/icon.css');
        $this->getRenderer()->addStylesheet('/css/navigation.css');
        $this->getRenderer()->addScript('/js/engine.js');
    }

}