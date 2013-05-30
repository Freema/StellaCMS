<?php
namespace Models\Image;

use Doctrine\ORM\EntityManager;
use Models\Entity\Image\Image as ImageEntity;
use Nette\Image as Thumbnails;
use Nette\Object;
use Nette\Utils\Finder;
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
     * @return ImageEntity
     */
    public function getImageRepository()
    {
        return $this->_em->getRepository('Models\Entity\Image\Image');                
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
        
        $image1 = Thumbnails::fromFile($name);
        $image2 = Thumbnails::fromFile($name);
        $smallThumb = $image1->resize($small[0], $small[1], Thumbnails::SHRINK_ONLY | Thumbnails::STRETCH);
        $largeThumb =  $image2->resize($large[0], $large[1], Thumbnails::EXACT);
        
        $smallThumbName = self::smallThumb($name, $this->getSize());
        $largeThumbName = self::largeThumb($name, $this->getSize());
        
        $smallThumb->save($smallThumbName);
        $largeThumb->save($largeThumbName);
    }
    
    /**
     * @param string $name
     * @param string $size
     * @return string
     */
    public static function smallThumb($name, $size)
    {
        $thumbName = explode( '.', $name );
        if($thumbName == FALSE)
        {
            return $name.'-'.$size['small'];        
        }
        else
        {
            return $thumbName[0].'-'.$size['small'].'.'.$thumbName[1];
        }
    }
    
    /**
     * @param string $name
     * @param string $size
     * @return string
     */
    public static function largeThumb($name, $size)
    {
        $thumbName = explode( '.', $name );
        if($thumbName == FALSE)
        {
            return $name.'-'.$size['big'];        
        }
        else
        {
            return $thumbName[0].'-'.$size['big'].'.'.$thumbName[1];
        }
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
    
    public function deleteImage($id)
    {
        /* @var $image ImageEntity */
        $image = $this->getImageRepository()->getOne($id);
        
        $dir = $this->getDir();
        
        $fileName = $image->getFileName();
        $smallThumb = self::smallThumb($fileName, $this->_thumbnailsSize);
        $largeThumb = self::largeThumb($fileName, $this->_thumbnailsSize);

        if(file_exists($dir.$fileName))
        {
            if(file_exists($dir.$smallThumb))
            {
                unlink($dir.$smallThumb);
            }   

            if(file_exists($dir.$largeThumb))
            {
                unlink($dir.$largeThumb);
            }   
            unlink($dir.$fileName);
        }
        else
        {
            throw new \Stella\ModelException('Obrazek se nepodařilo vymazat', 'Chyba při mazani img');
            $this->_em->remove($image);
        }
        $this->_em->remove($image);
        return $this->_em->flush();        
    }
}
