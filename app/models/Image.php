<?php
namespace Models\Image;

use Doctrine\ORM\EntityManager;
use Nette\Object;
use Nette\Utils\Finder;
use Nette\Image AS Thumbnails;
/**
 * Description of Image
 *
 * @author Tomáš Grasl
 */
class Image extends Object {
    
    /**
     * @var string
     */
    private $_dir;
    
    /** @var EntityManager */    
    private $_em;
    
    /** @var array */
    private $_thumbnailsSize;
    
    /**
     * @param string $dir
     */
    public function __construct(EntityManager $em ,$dir) {
        $this->_em = $em;        
        $this->_dir = $dir;
    }
    
    /**
     * @return string
     */
    public function getDir()
    {
        return $this->_dir;
    }
    
    /**
     * @return array
     */
    public function getSize()
    {
        return $this->_thumbnailsSize;
    }

    /**
     * @param array $size
     */
    public function setResize(array $size)
    {
        $this->_thumbnailsSize = $size;
    }

    /**
     * @return array
     */
    public function loadImageTab()
    {
        $query = $this->_em->createQuery('SELECT i.id, c.title AS category, i.file, i.name, i.ext, i.description, i.public, i.uploadedAt, i.imageOrder
                                          FROM Models\Entity\Image\Image i
                                          LEFT JOIN i.category c');
        
        return $query->getResult();
    }
    
    /**
     * create thumbs image
     * 
     * @param string $name
     */
    public function createThumbs($name)
    {
        $name = $this->getDir().$name;

        $small = explode('x', $this->_thumbnailsSize['small']);
        $large = explode('x', $this->_thumbnailsSize['big']);
        
        $image = Thumbnails::fromFile($name);
        $smallThumb = $image->resize($small[0], $small[1], Thumbnails::SHRINK_ONLY | Thumbnails::STRETCH);
        $largeThumb =  $image->resize($large[0], $large[1], Thumbnails::SHRINK_ONLY | Thumbnails::STRETCH);
        
        $smallThumb->save($name.'-'.$this->_thumbnailsSize['small']);
        $largeThumb->save($name.'-').$this->_thumbnailsSize['big'];
    }
    
    public static function smallThumb($name)
    {
        
    }
    
    public static function largeThumb($name)
    {
        
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function fileExist($name)
    {
        $filename= $this->_dir.$name;
        
        if(file_exists($filename))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * @param string $arg
     * @return array
     */
    public function findImages($arg)
    {
        $return = array();

        foreach (Finder::find($arg)->in($this->_dir) as $file)
        {
            $ext = '.'.pathinfo($file->getFilename(), PATHINFO_EXTENSION);
            
            $return[] = array(
              'name'        =>   $file->getBasename($ext),
              'baseName'    =>   $file->getBasename(),
              'ext'         =>   $ext, 
              'size'        =>   $file->getSize(),  
              'date'        =>   date('d.m.Y', $file->getCTime()),   
            );
        }
        
        return $return;
    }
}
