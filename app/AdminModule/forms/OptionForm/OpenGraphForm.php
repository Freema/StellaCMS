<?php
namespace AdminModule\Forms;

use Doctrine\ORM\EntityManager;
use Models\Omptions\Facebook;
use Nette\Application\UI\Form;

/**
 * Description of OpenGraphForm
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
class OpenGraphForm extends BaseForm {
    
    /** @var EntityManager */
    protected $_em;
    
    /** @var Facebook */
    protected $_FbService;
    
    /** @var array data form database! */
    private $_data;

    public function __construct(EntityManager $em, Facebook $service) {
        $this->_em = $em;
        $this->_FbService = $service;
    }

    public function createForm()
    {
        return $this->_addForm();
    }
    
    private function _addForm()
    {
        $form = new Form;
        
        $this->_data = $this->getOptionsData();
        
        $disabled = $this->isDisabled();

        $form->addCheckbox('Open_Graph_Active', 'Aktivovat OG hlavičku: ');
        
        $form->addCheckbox('Open_Graph_Cash', 'Cashovat hlavičku: ');
        
        $form->addText('Open_Graph_Title', 'Title: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Type', 'Type: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Url', 'Url: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Image', 'Obrazek: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Site_Name', 'Jméno stránky: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Description', 'Popis stránky: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Latitude', 'Zeměpisná šířka: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Street_Address', 'Jmeno ulice: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Region', 'Region: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Postal_Code', 'PSČ: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Country_Name', 'Název země: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Email', 'Email: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Phone_Number', 'Telefoní číslo: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Fax_Number', 'Číslo faxu: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDisabled($disabled);
        
        $form->addSubmit('submit', NULL)
             ->setAttribute('class', 'btn btn-success');
        
        $form->onSuccess[] = callback($this, 'onsuccess');

        $vybratBtn = $form['submit']->getControlPrototype();
        $vybratBtn->setName("button");
        $vybratBtn->type = 'submit'; 
        $vybratBtn->create('i class="icon-ok-sign"');
        $vybratBtn->add(' Upravit udaje');
        
        return $form;        
    }
    
    protected function getOptionsData()
    {
        $options = $this->_FbService->getFacebookOptions();
        
        $data = array();
        foreach($options as $option)
        {
            $data[$option['option_name']] = $option['option_value'];
        }
        
        return $data;
    }
    
    protected function isDisabled()
    {
        if(!empty($this->_data))
        {
            if(isset($this->_data['Open_Graph_Active']))
            {
                return !(bool) $this->_data['Open_Graph_Active'];
            }
            else
            {
                return true;
            }
        }
        else
        {
            throw new FormException('Data not isset in class: ' . __class__);
        }
    }
    
    public function onsuccess(Form $form)
    {
        $value = $form->values;
        
        $form->presenter->redirect('Menu:default');        
    }  
}
