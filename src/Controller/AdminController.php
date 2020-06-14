<?php

namespace Hardkode\Controller;

use Hardkode\Exception\PermissionException;
use Hardkode\Form\ArticleForm;
use Hardkode\Model\Article;
use Hardkode\Service\Paginator;
use ORM\Exception\IncompletePrimaryKey;
use ORM\Exception\NoEntity;
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

    /**
     * @return ResponseInterface
     *
     * @throws PermissionException
     */
    public function articles()
    {
        $this->requiresPermission('admin');

        try {
            $articles = $this->getEntityManager()->fetch(Article::class)->orderBy('id', 'DESC')->all();
        } catch (NoEntity | IncompletePrimaryKey $e) {
            $articles = [];
        }

        $query = [];
        parse_str($this->getRequest()->getUri()->getQuery(), $query);
        $currentPageCount = (int)($query['page'] ?? 1);

        $paginator = new Paginator($articles);
        $currentPage = $paginator->getPage($currentPageCount);

        return $this->render('/admin/articles.phtml', [
            'articles' => $currentPage,
            'pagination' => $paginator->getPagination($currentPageCount)
        ]);

    }

    /**
     * @return ResponseInterface
     * @throws PermissionException
     */
    public function articleCreate()
    {
        $this->requiresPermission('admin');

        $form = new ArticleForm($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {

            echo "Ja";
            die();

        }

        return $this->render('/admin/article-form.phtml', [
            'form' => $form
        ]);
    }

    public function tools()
    {
        $this->requiresPermission('admin');

        return $this->render('/admin/tools.phtml');
    }

}