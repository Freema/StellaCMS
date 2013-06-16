<?php
namespace Models\Image;
use Doctrine\ORM\EntityManager;
/**
 *
 * @author Tomáš
 */
interface IImageOrder {
    
    public function __construct(EntityManager $em, $dir);
    
    public function setEntity($namelo);
    
}

