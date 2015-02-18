<?php
namespace Components\Breadcrumbs;

use Nette\Application\UI\Control;
use Nette\DI\Config\Adapters\NeonAdapter;

/**
 * Description of Breadcrumbs
 *
 * @author Tomáš
 */
class Breadcrumbs extends Control{
    
    private $_presenter;
    
    public function __construct($presenter) {
        parent::__construct();        
        
        $this->_presenter = $presenter;
    }
    /**
     * @return array
     */
    private function _createBreadCrumb()
    {
        $parts = explode(':', $this->_presenter->name);
        $parts[] = $this->_presenter->view;
        if($parameter = $this->_presenter->getParameter('id')){
                $parts[] = $parameter;
        }
        
        $list = $this->_breadCrumbProcesor($parts);
        
        return $list;
    }    

    /**
     * @return array
     */
    protected function _getNavMap()
    {
        $neon = new NeonAdapter;
        $config = $neon->load(__DIR__ .DIRECTORY_SEPARATOR.'BreadCrumbs.neon');
        
        return $config;
    } 
    
    /**
     * @param array $super
     * @return array
     */
    private function _breadCrumbProcesor(array $super)
    {
        $return = array();
        $action = $this->_getNavMap();
        
        foreach ($action as $model)
        {
            $return[] = array('label' => $model['label'], 'link'    => $model['link']);
            
            if(isset($model['children']))
            {
                foreach ($model['children'] as $key => $presenter)
                {
                    if($key == $super[1])
                    {
                        $return[] = array('label' => $presenter['label'], 'link'    => $presenter['link']);
                        if(isset($presenter['children']))
                        {
                            foreach ($presenter['children'] as $key => $action)
                            {
                                if($key == $super[2])
                                {
                                    $page = array('label' => $action['label'], 'link'    => $action['link']);
                                    if(isset($super[3])){
                                        $parameter = array(
                                            'parameter'     => $super[3]);
                                        $page = array_merge($page, $parameter);
                                    }

                                    $return[] = $page;
                                    break;
                                }
                            }
                        }
                        break;
                    }
                }
            }
       }
       
       $end = array_keys($return);
       $key = end($end);
       $return[$key]['active'] = TRUE;
      
       return $return;
    }
    
    public function render()
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/BreadCrumbs.latte');

        $template->items = $this->_createBreadCrumb();
        $template->render();
    }       
}
