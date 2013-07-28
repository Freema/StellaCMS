<?php
namespace AdminModule\Forms;

use Nette\Object;
/**
 * Description of form
 *
 * @author Tomáš
 */
abstract class BaseForm extends Object {
    
    /**
     * @var object
     */
    protected $_defaults;

    protected function prepareForFormItem(array $items, $filter = 'Name', $isArray = FALSE)
    {
        if($isArray == TRUE)
        {
            if(count($items))
            {
                $prepared = array();
                foreach($items as $item)
                {
                    $prepared[$item[$filter]] = $item[$filter];
                }
                return $prepared;
            }
        }
        else
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
        }

        return $items;
    }
    
    protected function FormItemsDif($old, $new)
    {
        $diff = array();
        
        if($new)
        {
            $diff['added'] = array_diff($new, $old);
            $diff['remove'] = array_diff($old, $new);
        }
        else
        {
            $diff['added'] = NULL;
            $diff['remove'] = $old;
        }
        
        return $diff;
    }
    
    protected function FormItemsUpadates(\Nette\ArrayHash $values)
    {
        if(empty($this->_defaults))
        {
            throw new \BadMethodCallException('You can not call this function because it lacks argument _defaults');
        }

        $updates = array();
        foreach($this->_defaults as $key => $value)
        {
            if(isset($values->$key))
            {
                if($values->$key !== $value)
                {
                    $updates[$key] = $values->$key;
                }
            }
        }
        
        return $updates;
    }
}