<?php

namespace AdminModule\Forms;

use Nette\Environment;
use Nette\Forms\Container;
use Nette\Forms\Controls\UploadControl;
/**
 * Description of MultyFileUpload
 *
 * @author Tomáš Grasl
 */

class MultyFileUpload extends UploadControl {
    
    /**
    * Maximum selected files in one input
    * @var int
    */
    public $maxFiles;
    
    /**
    * Maximum file size of single uploaded file
    * @var int
    */
    protected $maxFileSize;

    public $token;

    public function __construct($label = NULL, $maxSelectedFiles = NULL) {
    parent::__construct($label);

    $this->control->type = 'file';
    $this->control->multiple = 'true';
    if ($maxSelectedFiles !== NULL) {
            $this->setSelectedFiles($maxSelectedFiles);
    }    
    }


    public function setSelectedFiles($maxSelectedFiles)
    {
            $this->maxFiles = $maxSelectedFiles;
            return $this;
    }  

    /**
    * Generates control's HTML element.
    *
    * @return \Nette\Utils\Html
    */
    public function getControl()
    {
        $control = parent::getControl();
        $control->name = $this->getHtmlName() . "[]";
        $control->class[] = "multiple-file-upload";

        return $control;
    }

    /**
    * Sets control's value.
    *
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
    * Filled validator: has been any file uploaded?
    * @param  IFormControl
    * @return bool
    */
    public static function validateFilled(\Nette\Forms\IControl $control) {
    $files = $control->getValue();
    return (count($files) > 0);
    }

    /**
    * FileSize validator: is file size in limit?
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
    * MimeType validator: has file specified mime type?
    * @param  FileUpload
    * @param  array|string  mime type
    * @return bool
    */
    public static function validateMimeType(\Nette\Forms\Controls\UploadControl $control, $mimeType) {
    throw new \Nette\NotSupportedException("Can't validate mime type! This is MULTIPLE file upload control.");
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