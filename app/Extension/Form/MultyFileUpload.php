<?php

namespace AdminModule\Forms;

use Nette\Forms\Container;
use Nette\Forms\Controls\UploadControl;
use Nette\Http\FileUpload;
/**
 * Description of MultyFileUpload
 *
 * @author Tomáš Grasl
 */

class MultyFileUpload extends UploadControl {
    
    public function __construct($label = NULL) {
        parent::__construct($label);

        $this->control->type = 'file';
        $this->control->multiple = 'multiple';

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
     * @param array $value
     * @return MultyFileUpload
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            $this->value = $value;

        } elseif ($value instanceof FileUpload2) {
            $this->value = $value;

        } else {
            $this->value = new FileUpload2(null);
        }

        return $this;
    }
    
    /**
    * @param  UploadControl $control
    * @param  integer $limit
    * @return boolean
    */
    public static function validateFileSize(UploadControl $control, $limit) {
        $files = $control->getValue();
        $size = 0;

        foreach ($files AS $file) {
          $size += $file->getSize();
        }
        
        return $size <= $limit;
    }


    /**
     * @param UploadControl $control
     * @param $mimeType
     * @return bool
     */
    public static function validateMimeType(UploadControl $control, $mimeType) {
        $files = $control->getValue();

        if (is_array($files) and count($files)) 
        {
            foreach ($files as $file) {
                if (self::validateFileMimeType($file, $mimeType))
                    return true;
            }
        } else {
            if (self::validateFileMimeType($files, $mimeType))
                return true;
        }

        return false;
    }

    /**
     * @param $file
     * @param $mimeType
     * @return bool
     */
    protected static function validateFileMimeType($file, $mimeType) {
        if ($file instanceof FileUpload) {
            $type = strtolower($file->getContentType());
            $mimeTypes = is_array($mimeType) ? $mimeType : explode(',', $mimeType);
            if (in_array($type, $mimeTypes, true)) {
                return true;
            }
            if (in_array(preg_replace('#/.*#', '/*', $type), $mimeTypes, true)) {
                return true;
            }
        }
    }

    /**
    * @param \Nette\Forms\Controls\UploadControl $control
    * @return bool
    */
    public static function validateImage(UploadControl $control) {
        $files = $control->getValue();

        if (is_array($files))
        {
            foreach ($files as $file) {
                if (!$file instanceof FileUpload or !$file->isImage())
                    return false;
            }

            return true;
        } else {
            return $files instanceof FileUpload && $files->isImage();
        }
    }

    /**
     * Adds addMultyFileUpload() method to Nette\Forms\Container
     */
    public static function register()
    {
        Container::extensionMethod('addMultyFileUpload', function (Container $_this, $name, $label) {

                return $_this[$name] = new MultyFileUpload($label);
        });
    }  
}