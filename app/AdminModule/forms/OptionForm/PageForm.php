<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Models\Omptions\Page;
use Nette\Application\UI\Form;

/**
 * Description of PageForm
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class PageForm extends BaseForm {
    
    /** @var EntityManager */
    protected $_em;
    
    /** @var Page */
    protected $_PageService;
    
    /** @var array data form database! */
    protected $_defaults;

    public function __construct(EntityManager $em, Page $service) {
        $this->_em = $em;
        $this->_PageService = $service;
    }

    /**
     * @param array $defaults
     * @return type
     */
    public function createForm($defaults)
    {
        $this->_defaults = $this->getPageData($defaults);        
        return $this->_updateForm();
    }
    
    private function _updateForm()
    {
        $form = new Form;
        
        $form->addCheckbox('Page_Options_Cash', 'Cashovat nastavení stranky')
             ->setDefaultValue($this->_defaults['Page_Options_Cash']);
        
        $form->addText('Page_Options_Page_Name', 'Název webu: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Page_Options_Page_Name']);        

        $form->addText('Page_Options_Page_Description', 'Popis webu: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Page_Options_Page_Description']);        
        
        $form->addText('Page_Options_Admin_Email', 'Emailová adresa: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->addRule(Form::EMAIL, null)
             ->setDefaultValue($this->_defaults['Page_Options_Admin_Email']);    
                
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
       
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('span class="glyphicon glyphicon-ok"');
        $vybratBtn->add(' Upravit udaje');
        
        return $form;        
    }
    
    protected function getPageData($defaults)
    {
        $data = array();
        foreach($defaults as $option)
        {
            $data[$option['option_name']] = $option['option_value'];
        }
        
        return $data;
    }
    
    public function onsuccess(Form $form)
    {
        $values = $form->values;        
        $updateValue = $this->FormItemsUpadates($values);
        $this->_PageService->updatePagedata($updateValue);
        
        $form->presenter->flashMessage('Nastaveni stranky bylo upraveno.', 'success');
        $form->presenter->redirect('Options:default#page');
    }  
}