<?php
namespace Components\Breadcrumbs;

use Nette\Application\UI\Control;

/**
 * Description of Breadcrumbs
 *
 * @author Tomáš
 */
class Breadcrumbs extends Control{

    public function create($var)
    {
        $parts = explode(':', $var);
        
        dump($parts);
    }
    
}
