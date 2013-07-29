<?php
namespace Models\Image;
use Doctrine\ORM\EntityManager;
/**
 *
 * @author Tomáš
 */
interface IImageOrder {
    
    /**
     * @param string $dir
     */
    public function __construct(EntityManager $em, $dir);

    /**
     * @param object $name
     * @return object
     */
    public function setEntity($namelo);
    
    /**
     * @param array $size
     */
    public function setResize(array $size);
}

