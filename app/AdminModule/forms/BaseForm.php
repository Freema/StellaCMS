<?php
namespace AdminModule\Forms;

use Nette\Object;
/**
 * Description of form
 *
 * @author Tomáš
 */
abstract class BaseForm extends Object{
    
    protected function prepareForFormItem(array $items, $filter = 'Name')
    {
        $filter = ucfirst($filter);
        $function = 'get'.$filter;

        if(count($items)){
            $prepared = array();
            foreach($items as $item){
                $prepared[$item->getId()] = $item->$function();
            }
            return $prepared;
        }

        return $items;
    }
    
}