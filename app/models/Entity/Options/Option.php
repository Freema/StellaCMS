<?php
namespace Models\Entity; 
/**
 * Description of Options reposiotory
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Models\Entity\Options\OptionRepository")
 * @ORM\Table(name="options")
 */

class Option
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $option_name;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="text") 
     */
    protected $option_value;
   
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get option name
     *
     * @return string 
     */
    public function getOptionName()
    {
        return $this->option_name;
    }

    /**
     * Set option name
     *
     * @param string $option_name
     * @return \Models\Entity\Options\Option
     */
    public function setOptionName($option_name)
    {
        $this->option_name = $option_name;
    
        return $this;
    }

    /**
     * Get option value
     * 
     * @return string
     */
    public function getOptionValue()
    {
        return $this->option_value;
    }

    /**
     * Set option value
     * 
     * @param string $option_value
     * @return \Models\Entity\Options\Option
     */
    public function setOptionValue($option_value)
    {
        $this->option_value = $option_value;
        return $this;
    }
}

