<?php
namespace AdminModule\Forms;

use Nette\Object;
/**
 * Description of form
 *
 * @author Tomáš
 */
abstract class BaseForm extends Object{
    
     protected function prepareForFormItem(array $items, $filter = 'name')
     {
         if(count($items)){
             $prepared = array();
             foreach($items as $item){
                 $prepared[$item->id] = $item->$filter;
             }
             return $prepared;
         }
 
         return $items;
     }
    
}