<?php
namespace Models\Entity\Url; 
/**
 * Description of TagRepository
 *
 * @author Tomáš
 */

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="url")
*/
class Url extends \Nette\Object
{
    /**
     *
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $url;

    /**
     * @ORM\Column(type="integer", length=5)
     */
    protected $priority;

    public function __construct($title, $url)
    {
            $this->title = (string) $title;
            $this->url = (string) $url;

            $this->priority = 5;
    }

    public function getTitle()
    {
            return $this->title;
    }

    public function setTitle($title)
    {
            $this->title = (string) $title;
            return $this;
    }

    public function getUrl()
    {
            return $this->url;
    }

    public function setUrl($url)
    {
            $this->url = (string) $url;
            return $this;
    }

    public function getPriority()
    {
            return $this->priority;
    }

    public function setPriority($priority)
    {
            $this->priority = (int) $priority;
            return $this;
    }

    public function __toString()
    {
            return (string) $this->title;
    }
}