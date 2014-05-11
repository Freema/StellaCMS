<?php
namespace Models;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

/**
 * Router factory.
 */
class PageRouter {
    
    /**
     * @return IRouter
     */
    public function createRouter() {
        $router = new RouteList();
        
        $router[] = new Route('admin/<presenter>/<action>', array(
            'module' => 'admin',
            'presenter' => 'Login',
            'action' => 'default'
        ));
        
        $router[] = new Route('<presenter>/<action>[/<id>]', array(
            'module' => 'Front',
            'presenter' => 'Homepage',
            'action' => 'default'
        ));
        
        return $router;
    }
    
}
