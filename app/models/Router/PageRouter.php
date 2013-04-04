<?php

use Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;

/**
 * Router factory.
 */
class PageRouter
{
    /**
     * @return Nette\Application\IRouter
     */
    public function createRouter()
    {
        $router = new RouteList();
        
        $router[] = new Route('admin/<presenter>/<action>', array(
            'module' => 'admin',
            'presenter' => 'Login',
            'action' => 'default'
        ));
        
        $router[] = new Route('<presenter>/<action>[/<id>]', array(
            'module' => 'front',
            'presenter' => 'Homepage',
            'action' => 'default'
        ));
        
        return $router;
    }
    
}
