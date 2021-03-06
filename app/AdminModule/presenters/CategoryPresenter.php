<?php

namespace AdminModule;

use AdminModule\Forms\CategoryForm;
use Components\Paginator\PagePaginator;
use Models\Category\Category;

/**
 * Description of CategoriePresenter
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class CategoryPresenter extends BasePresenter {

    /**
     * @var CategoryForm
     */
    private $_CategoryForm;

    /**
     * @var  Category
     */
    private $_Category;

    /**
     * @var \Models\Entity\Category\Category 
     */
    private $_Page;

    /** @persistent */
    public $page;

    /** @persistent */
    public $sort = array(
        'id' => 'NONE',
        'title' => 'NONE',
        'slug' => 'NONE',
        'posts' => 'NONE',
        'public' => 'NONE',
    );

    final function injectCategoryForm(CategoryForm $factory) {
        $this->_CategoryForm = $factory;

        return $this;
    }

    final function injectCategory(Category $service) {
        $this->_Category = $service;

        return $this;
    }

    protected function createComponentCategoryForm() {
        $factory = $this->_CategoryForm->createForm();
        if ($this->isAjax()) {
            $factory->onError = callback($this, "formMessageErrorResponse");
        }
        return $factory;
    }

    protected function createComponentEditCategoryForm() {
        return $this->_CategoryForm->createForm($this->_Page);
    }

    public function actionDefault($page, array $sort) {

        $this->_Category->setSort($sort);

        /* @var $paginator PagePaginator */
        $paginator = $this['pagination'];
        if (is_null($page)) {
            $page = 1;
        }

        $paginator->page = $page;
        $paginator->itemCount = $this->_Category->categoryItemsCount();

        $this->_Category->setFirstResult($paginator->getOffSet());
        $this->_Category->setMaxResults($paginator->getMaxResults());

        $this->template->tab = $this->_Category->loadCategoryTab();
    }

    public function actionAddCategory() {
        if ($this->isAjax()) {
            $this->setView('addCategoryAjax');
        }
    }

    public function actionEditCategory($id) {
        if (!($this->_Page = $this->_Category->getCategoryRepository()->getOne($id))) {
            $this->flashMessage('Category does not exist.', 'error');
            $this->redirect('default');
        }

        $this->template->data = $this->_Page;
    }

    public function handleDelete($id) {
        $delete = $this->_Category->deleteCategory($id);
        if ($delete) {
            $this->flashMessage('Kategorie byla vymazána!', 'success');
        } else {
            $this->flashMessage('Nastala chyba při mazání kategorie!', 'error');
        }

        $this->redirect('default');
    }

    protected function createComponentPagination() {
        $paginator = new PagePaginator();
        return $paginator;
    }

}
