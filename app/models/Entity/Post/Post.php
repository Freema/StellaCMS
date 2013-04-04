<?php
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="PostRepository")
 * @ORM\Table(name="post")
 */
class Post {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

}