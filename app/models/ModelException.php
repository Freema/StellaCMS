<?php
namespace Stella;

use Exception;
use Nette\Diagnostics\Debugger;

class ModelException extends Exception {
   private $_systemLogMessage;
   
   public function __construct($message, $logMessage = NULL) {
     parent::__construct($message);
     $this->_systemLogMessage = $logMessage;
     if(!$logMessage == NULL)
     {
         Debugger::log($logMessage);     
     }
   }

   public function getSystemLogMessage() 
   {
       return $this->_systemLogMessage;
   }
}
