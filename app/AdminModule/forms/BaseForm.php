<?php
namespace AdminModule\Forms;

use Nette\Object;
/**
 * Description of form
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */
abstract class BaseForm extends Object {
    
    /**
     * @var object
     */
    protected $_defaults;
    
    /**
     * @var array | string 
     */
    private $_formItems;

    /**
     * @param array $items
     * @param string $filter
     * @return array
     */
    protected function prepareForFormItem(array $items, $filter = 'Name', $parent = FALSE)
    {
        if($parent == TRUE)
        {
            $filter = ucfirst($filter);
            $function = 'get'.$filter;        

            foreach ($items as $item)
            {
                if($item->getParent() == NULL)
                {
                    $parent = NULL;
                }
                else
                {
                    $parent = $item->getParent()->getId();
                }
                $pre_tree[$item->getId()] = array(
                    'parent'    => $parent,
                    'title'     => $item->$function(), 
                );
            }            
            
            $split = $this->_menuSplit($pre_tree);
            $this->_formItems($split);
            return $this->_formItems;
        }
        else
        {
            if(count($items))
            {
                $prepared = array();
                foreach($items as $item)
                {
                    if(is_array($item))
                    {
                        $prepared[$item[$filter]] = $item[$filter];
                    }
                    elseif(is_object($item))
                    {
                        $filter = ucfirst($filter);
                        $function = 'get'.$filter;
                        $prepared[$item->getId()] = $item->$function();
                    }
                }
                return $prepared;
            }
            return $items;            
        }
    }
    
    /**
     * @param array $menuItems
     * @return array
     */
    private function _menuSplit(array $menuItems)
    {
        foreach ($menuItems as &$menuItem)
            $menuItem['children'] = array();

        foreach ($menuItems as $id => &$menuItem)
        {
            if ($menuItem['parent'] != null)
                $menuItems[$menuItem['parent']]['children'][$id] = &$menuItem;
        }

        foreach (array_keys($menuItems) as $id)
        {
            if ($menuItems[$id]['parent'] != null)
                unset($menuItems[$id]);
        }

        return $menuItems;
    }
    
    /**
     * create depth view in select box
     * @param array $items
     * @param integer $depth
     */
    private function _formItems(array $items, $depth = NULL)
    {
        foreach ($items as $key => $value)
        {
            if($depth)
            {
                $separator = null;
                for ($i = 0; $i < $depth; $i++) {
                    $separator .= '_';
                }
            }
            else
            {
                $separator = NULL;
            }            
            if(empty($value['children']))
            {

                $this->_formItems[$key] = $separator.' '.$value['title'];                
            }
            else
            {
                $this->_formItems[$key] = $separator.' '.$value['title'];  
                $this->_formItems($value['children'], $depth + 1);
            }
        }
    }
    
    /**
     * Find differences between the two arrays.
     * 
     * @param array $old
     * @param array | null $new
     * @return array
     */
    protected function FormItemsDif(array $old, $new)
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
    
    /**
     * @param \Nette\ArrayHash $values
     * @return array
     * @throws \BadMethodCallException
     */
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