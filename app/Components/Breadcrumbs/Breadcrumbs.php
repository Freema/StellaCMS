<?php
namespace Components\Breadcrumbs;

use Nette\Application\UI\Control;

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
        return array(
                'Admin' => array('label' => 'Úvodní stranka', 'link' => 'ControlPanel:default' , 'children' => 
                    array(
                    'ObsahStranek' => array('label'   => 'Příspěvky', 'link'   => 'ObsahStranek:default', 'children' => 
                        array(
                            'addArticle'    => array('label'  => 'Vytvořit příspěvek', 'link'  => 'ObsahStranek:addArticle'),
                            'editArticle'   => array('label'  => 'Upravit příspěvek', 'link'  => 'ObsahStranek:editArticle'),
                    )),
                    
                )),
        );
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
            
            foreach ($model['children'] as $key => $presenter)
            {
                if($key == $super[1])
                {
                    $return[] = array('label' => $presenter['label'], 'link'    => $presenter['link']);
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
                    break;
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
        $template->setFile(__DIR__ . '\BreadCrumbs.latte');

        $template->items = $this->_createBreadCrumb();
        $template->render();
    }       
}
