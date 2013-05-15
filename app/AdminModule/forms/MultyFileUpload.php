<?php

namespace AdminModule\Forms;

use Nette\Forms\Container;
use Nette\Forms\Controls\UploadControl;
/**
 * Description of MultyFileUpload
 *
 * @author Tomáš Grasl
 */

class MultyFileUpload extends UploadControl {
    
    /**
    * @var int
    */
    public $maxFiles;
    
    /**
    * @var int
    */
    protected $maxFileSize;

    public function __construct($label = NULL, $maxSelectedFiles = NULL) {
        parent::__construct($label);

        $this->control->type = 'file';
        $this->control->multiple = 'multiple';
        if ($maxSelectedFiles !== NULL) {
            $this->setSelectedFiles($maxSelectedFiles);
        }    
    }

    /**
     * @param integer $maxSelectedFiles
     * @return \AdminModule\Forms\MultyFileUpload
     */
    public function setSelectedFiles($maxSelectedFiles)
    {
        $this->maxFiles = $maxSelectedFiles;
        return $this;
    }  

    /**
    * @return \Nette\Utils\Html
    */
    public function getControl()
    {
        $control = parent::getControl();
        $control->name = $this->getHtmlName() . "[]";

        return $control;
    }

    /**
    * @param array|Nette\Http\FileUpload
    * @return Nette\Http\FileUpload provides a fluent interface
    */
    public function setValue($value)
    {
        if (is_array($value)) {
            $this->value = $value;

        } elseif ($value instanceof FileUpload) {
            $this->value = $value;

        } else {
            $this->value = new FileUpload(null);
        }

        return $this;
    }

    /**
    * @param  IFormControl
    * @return bool
    */
    public static function validateFilled(\Nette\Forms\IControl $control) {
        $files = $control->getValue();
        return (count($files) > 0);
    }

    /**
    * @param  MultipleFileUpload
    * @param  int  file size limit
    * @return bool
    */
    public static function validateFileSize(\Nette\Forms\Controls\UploadControl $control, $limit) {
        $files = $control->getValue();
        $size = 0;

        foreach ($files AS $file) {
          $size += $file->getSize();
        }
        
        return $size <= $limit;
    }

    /**
     * Adds addMultyFileUpload() method to Nette\Forms\Container
     */
    public static function register()
    {
        Container::extensionMethod('addMultyFileUpload', function (Container $_this, $name, $label, $maxFiles = NULL) {

                return $_this[$name] = new MultyFileUpload($label, $maxFiles);
        });
    }  
}