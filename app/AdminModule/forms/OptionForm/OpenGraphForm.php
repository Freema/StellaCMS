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
    protected $_defaults;

    public function __construct(EntityManager $em, Facebook $service) {
        $this->_em = $em;
        $this->_FbService = $service;
    }

    public function createForm($defalts)
    {
        $this->_defaults = $this->getOptionsData($defalts);        
        return $this->_updateForm();
    }
    
    private function _updateForm()
    {
        $form = new Form;
       
        $disabled = $this->isDisabled();

        $form->addCheckbox('Open_Graph_Active', 'Aktivovat OG hlavičku: ')
             ->setDefaultValue($this->_defaults['Open_Graph_Active']);
        
        $form->addCheckbox('Open_Graph_Cash', 'Cashovat hlavičku: ')
             ->setDefaultValue($this->_defaults['Open_Graph_Cash'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Title', 'Title: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Title'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Type', 'Type: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Type'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Url', 'Url: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Url'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Image', 'Obrazek: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Image'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Site_Name', 'Jméno stránky: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Site_Name'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Description', 'Popis stránky: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Description'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Latitude', 'Zeměpisná šířka: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Latitude'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Street_Address', 'Jmeno ulice: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Street_Address'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Region', 'Region: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Region'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Postal_Code', 'PSČ: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Postal_Code'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Country_Name', 'Název země: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Country_Name'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Email', 'Email: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Email'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Phone_Number', 'Telefoní číslo: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Phone_Number'])
             ->setDisabled($disabled);
        
        $form->addText('Open_Graph_Fax_Number', 'Číslo faxu: ')
             ->addRule(Form::MAX_LENGTH, null, 100)
             ->setDefaultValue($this->_defaults['Open_Graph_Fax_Number'])
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
    
    protected function getOptionsData($defaults)
    {
        $data = array();
        foreach($defaults as $option)
        {
            $data[$option['option_name']] = $option['option_value'];
        }
        
        return $data;
    }
    
    protected function isDisabled()
    {
        if(!empty($this->_defaults))
        {
            if(isset($this->_defaults['Open_Graph_Active']))
            {
                return !(bool) $this->_defaults['Open_Graph_Active'];
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
        $values = $form->values;
       
        $updateValue = $this->FormItemsUpadates($values);
        $this->_FbService->updateOGdata($updateValue);
        
        $form->presenter->flashMessage('OG hlavička upravena.', 'success');
        $form->presenter->redirect('Options:default#facebook');
    }  
}
