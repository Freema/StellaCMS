<?php
namespace Models\Image;

use Doctrine\ORM\EntityManager;
use Models\Entity\Image\Image as ImageEntity;
use Nette\Image as Thumbnails;
use Nette\Utils\Finder;
/**
 * Description of Image
 *
 * @author Tomáš Grasl
 */
class Image extends ImageOrder implements IImageOrder {
    
    /**
     * @var string
     */
    protected $_dir;
    
    /** @var EntityManager */    
    protected $_em;
    
    /** @var array */
    protected $_thumbnailsSize;
    
    /** @var string */
    protected $_entity;
    
    /** @var array */
    protected $filter;
    
    /** @var array */
    protected $sort;
    
    /** @var integer */
    protected $page;
    
    /** @var integer */
    protected $maxResults;
    
    /** @var integer */
    protected $firstResult;

    /**
     * @param string $dir
     */
    public function __construct(EntityManager $em , $dir) 
    {
        $this->_em = $em;        
        $this->_dir = $dir;
        $this->setEntity('Models\Entity\Image\Image');        
    }
    
    /**
     * @param object $name
     * @return object
     */
    public function setEntity($name) {
        return $this->_entity = $name;
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
     * @param array $sort
     */
    public function setSort(array $sort)
    {
        $this->sort = $sort;
    }
    
    /**
     * @param array $filter
     */
    public function setFilter(array $filter)
    {
        $this->filter = $filter;
    }
    
    /**
     * @param integer $page
     */
    public function setPage($page)
    {
        $this->page = (int) $page;
    }
    
    /**
     * @param integer $result
     */
    public function setMaxResults($result)
    {
        $this->maxResults = (int) $result;
    }
    
    /**
     * @param integer $result
     */
    public function setFirstResult($result)
    {
        $this->firstResult = (int) $result;
    }

    /**
     * @return ImageEntity
     */
    public function getImageRepository()
    {
        return $this->_em->getRepository('Models\Entity\Image\Image');                
    }
    
    /**
     * @return integer
     */
    public function imageItemsCount()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('count(i.id)');
        $query->from('Models\Entity\Image\Image', 'i');
        
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @return array
     */
    public function loadImageTab()
    {
        $query = $this->_em->createQueryBuilder();
        $query->select('i.id, c.title AS category, i.file, i.name, i.ext, i.description, i.public, i.uploadedAt, i.imageOrder');
        $query->from('Models\Entity\Image\Image', 'i');
        $query->leftJoin('i.category', 'c');
        
        if(!empty($this->sort))
        {
            $sort_typs = array('ASC', 'DESC');
            if(isset($this->sort['name']))
            {
                if(in_array($this->sort['name'], $sort_typs))
                {
                    $query->addOrderBy('i.name', $this->sort['name']);
                }
            }
            
            if(isset($this->sort['order']))
            {
                if(in_array($this->sort['order'], $sort_typs))
                {
                    $query->addOrderBy('i.imageOrder', $this->sort['order']);
                }
            }
            
            if(isset($this->sort['categorii']))
            {
                if(in_array($this->sort['categorii'], $sort_typs))
                {
                    $query->addOrderBy('category', $this->sort['categorii']);
                }
            }
            
            if(isset($this->sort['public']))
            {
                if(in_array($this->sort['public'], $sort_typs))
                {
                    $query->addOrderBy('i.public', $this->sort['public']);
                }
            }
            
            if(isset($this->sort['uploadet_at']))
            {
                if(in_array($this->sort['uploadet_at'], $sort_typs))
                {
                    $query->addOrderBy('i.uploadedAt', $this->sort['uploadet_at']);
                }
            }
            
            if(isset($this->sort['id']))
            {
                if(in_array($this->sort['id'], $sort_typs))
                {
                    $query->addOrderBy('i.id', $this->sort['id']);
                }
            }
        }
        
        $query->setMaxResults($this->maxResults);
        $query->setFirstResult($this->firstResult);
        
        return $query->getQuery()->getResult();
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

            if($this->fileExist($file->getFilename()))
            {
                $image_size = getimagesize($this->_dir.$file->getFilename());
            }
            else
            {
                $image_size = NULL;   
            }
            
            $return[] = array(
              'name'        =>   $file->getBasename($ext),
              'baseName'    =>   $file->getBasename(),
              'ext'         =>   $ext, 
              'file_size'   =>   $file->getSize(),  
              'date'        =>   date('d.m.Y', $file->getCTime()),
              'image_size'  =>   $image_size,  
            );
        }
        
        return $return;
    }

    public function deleteImage($id)
    {
        /* @var $image ImageEntity */
        $image = $this->getImageRepository()->getOne($id);
        
        $this->updateTheOrderAfterDeleting( $image->getId(), 
                                            $image->getCategory(),
                                            $image->getImageOrder());
        
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
            $this->_em->remove($image);
            $this->_em->flush();
            throw new \Stella\ModelException('Obrazek se nepodařilo kopletně vymazat!', 'Chyba při mazani img');
        }
        $this->_em->remove($image);
        return $this->_em->flush();        
    }
    
    public function lastImageOrder($category = NULL)
    {
        $order = $this->lastOrder($category);

        foreach ($order as $item)
        {
            $return = $item;
        }
       
        return $return;
    }
    
    public function updateImageOrderAfterCategoryChange(ImageEntity $image)
    {
        $this->updateTheOrderAfterDeleting( $image->getId(), 
                                            $image->getCategory(),
                                            $image->getImageOrder());        
    }
    
    public function updateImageOrder(ImageEntity $defaults, $newOrder)
    {
        $this->updateTheOrderAfterImageUpdate(
                $defaults->getId(), 
                $defaults->getCategory(),
                $defaults->getImageOrder(),
                $newOrder);          
        
        $defaults->setImageOrder($newOrder);
        $this->_em->flush($defaults);
    }
}
