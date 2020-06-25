<?php

namespace Hardkode\Controller;

use Hardkode\Exception\PermissionException;
use Hardkode\Form\ArticleForm;
use Hardkode\Model\Article;
use Hardkode\Model\User;
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

        $form = $this->createForm(ArticleForm::class);

        if ($form->isSubmitted() && $form->isValid()) {

            try {

                $title = $form->getData()['title'];
                $titleExists = $this->getEntityManager()->fetch(Article::class)->where('title', '=', $title)->all();

                if ($titleExists) {
                    throw new \PDOException('Unique key constraint violation, Title "' . $title . '" already exists.', '23000');
                }

                $entity          = new Article();
                $entity->title   = $title;
                $entity->content = $form->getData()['text'];
                $author          = $this->getEntityManager()
                    ->fetch(User::class, $this->getSession()->get('userId'));

                $entity->setRelated('author', $author);

                $entity->save();

                return $this->redirect('admin:articles');

            } catch (\PDOException $e) {

                if (23000 === (int)$e->getCode()) {
                    $this->getSession()->setFlashMessage('form.error', 'cms.title.unique.error');
                }

            } catch (NoEntity | IncompletePrimaryKey $e) {
                $this->getSession()->setFlashMessage('form.error', $e->getMessage());
            }

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