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
    $this->monitor('Nette\Forms\Form');      
    parent::__construct($label);
    
    $this->control->type = 'file';
    $this->control->multiple = 'multiple';
    if ($maxSelectedFiles !== NULL) {
            $this->setSelectedFiles($maxSelectedFiles);
    }    
  }

  /**
* Handles uploading files
*/
  public static function handleUploads() {
    // Pokud už bylo voláno handleUploads -> skonči
    if (self::$handleUploadsCalled === true) {
      return;
}
    else {
      self::$handleUploadsCalled = true;
}

    $req = Environment::getHttpRequest();

    // Workaround for: http://forum.nettephp.com/cs/3680-httprequest-getheaders-a-content-type
    $contentType = $req->getHeader("content-type");
    if (!$contentType and isset($_SERVER["CONTENT_TYPE"])) {
      $contentType = $_SERVER["CONTENT_TYPE"];
    }

    if ($req->getMethod() !== "POST") {
      return;
    }

self::getQueuesModel()->initialize();

    foreach (self::getUIRegistrator()->getInterfaces() AS $interface) {
      if ($interface->isThisYourUpload()) {
        $ret = $interface->handleUploads();
        if ($ret === true) break;
      }
    }
  }  
  
  public function loadHttpData() {
    $name = strtr(str_replace(']', '', $this->getHtmlName()), '.', '_');
    $data = $this->getForm()->getHttpData();
    if (isset($data[$name])) {
      // Zjistí token fronty souborů, kterou jsou soubory doručeny
      // -> Jak JS tak bez JS (akorát s JS už dorazí pouze token - nic jiného)
      if (isset($data[$name]["token"])) {
        $this->token = $data[$name]["token"];
      }
      else {
        throw new \Nette\InvalidStateException("Token has not been received! Without token MultipleFileUploader can't identify which files has been received.");
      }
    }
  }

  

    public function setSelectedFiles($maxSelectedFiles)
    {
            $this->maxFiles = $maxSelectedFiles;
            return $this;
    }  
  
  /**
   * Monitoring
   * @param mixed $component
   */
  protected function attached($component) {
    if ($component instanceof Nette\Application\UI\Form) {
      $component->getElementPrototype()->enctype = 'multipart/form-data';
      $component->getElementPrototype()->method = 'post';
    }
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

  /*	 * ******************* Helpers ******************** */

  /**
   * Parses ini size
   * @param string $value
   * @return int
   */
  public static function parseIniSize($value) {
    $units = array('k' => 1024, 'm' => 1048576, 'g' => 1073741824);
    $unit = strtolower(substr($value, -1));
    if (is_numeric($unit) || !isset($units[$unit])) return $value;
    return ((int) $value) * $units[$unit];
  }
  
    /**
     * Adds addMultyFileUpload() method to Nette\Forms\Container
     */
    public static function register()
    {
            Container::extensionMethod('addMultyFileUpload', function (Container $_this, $name, $label, $maxFiles = NULL) {
                
                    $application = Environment::getApplication();
                    $application->onStartup[] = callback("AdminModule\Forms\MultipleFileUpload::handleUploads");
                    return $_this[$name] = new MultyFileUpload($label, $maxFiles);
            });
    }  

    
}