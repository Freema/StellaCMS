<?php
namespace Models\Omptions;

/**
 * Description of FbContainer
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Doctrine\ORM\EntityManager;
use FbTools\OpenGraphTags;
use Models\Entity\Options\OptionRepository;
use Nette\Caching\Storages\FileStorage;

class Facebook extends BaseOption {
    
    /** @var string */
    protected $storage_name = 'Options.Facebook';

    /** @var OpenGraphTags  */
    private $_openGraph;
    
    /** @var array */
    private $_openGraphtTags = array(
        'Open_Graph_Title' => 'title',
        'Open_Graph_Type' => 'type',
        'Open_Graph_Url' => 'url',
        'Open_Graph_Image' => 'image',
        'Open_Graph_Name' => 'name',
        'Open_Graph_Description' => 'description',
        'Open_Graph_Latitude' => 'latitude',
        'Open_Graph_Street_Address' => 'street-address',
        'Open_Graph_Locality' => 'locality',
        'Open_Graph_Region' => 'region',
        'Open_Graph_Postal_Code' => 'postal-code',
        'Open_Graph_Country_name' => 'contry-name',
        'Open_Graph_Email' => 'email',
        'Open_Graph_Phone_Number' => 'phone_number',
        'Open_Graph_Fax_Number' => 'fax_number',
    );

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Nette\Caching\Storages\FileStorage $storage
     */
    function __construct(EntityManager $em,  FileStorage $storage) {
        parent::__construct($em, $storage);
        $this->_openGraph = new OpenGraphTags();
    }
    
    /**
     * @return object
     */
    public function getOpenGraphTagsControl()
    {
        $value = $this->cache->load('OpenGraphTags');
        
        if($value === NULL)
        {
            if($this->getOptionRepository()->CheckOpenGraphIsActive())
            {
                $query = $this->getFacebookOptions();
                $this->OpenGraphTagsCache($query);
            }
            else
            {
                return $this->_openGraph;
            }
        }
        else
        {
            foreach($value as $tag)
            {
                if(!empty($tag['option_value']))
                {
                    if(isset($this->_openGraphtTags[$tag['option_name']]))
                    {
                        $parse = $this->_openGraphtTags[$tag['option_name']];
                        $this->_openGraph->{$parse} = $tag['option_value'];
                    }
                }
            }            
        }
        return $this->_openGraph;
    }
    
    /**
     * @return array | object
     */
    public function getFacebookOptions()
    {
        $query = $this->_em
                     ->createQuery('
                    SELECT o 
                    FROM Models\Entity\Options\Option o 
                    WHERE o.option_name LIKE ?1
                    ')
                    ->setParameter(1, OptionRepository::OG_PREFIX.'%')
                    ->getResult($this->hydrate_mode);
        
        return $query;
    }
    
    /**
     * cheack if si open graph service active
     * 
     * @return bool 
     */
    public function isOpenGraphActive()
    {
        $activation = (bool) $this  ->getOptionRepository()
                                    ->findOneBy(array('option_name' => 'Open_Graph_Active'))
                                    ->getOptionValue();
        
        return $activation;
    }
    
    /**
     * @param integer $activation
     */
    public function OpenGraphCacheUnset($activation)
    {
        if(!(bool)$activation)
        {
            if($this->cache->load('OpenGraphTags'))
            {
                $this->cache->save('OpenGraphTags', NULL);
            }
        }
    }

    /**
     * @param array $query
     */
    protected function OpenGraphTagsCache($query)
    {
        foreach($query as $tag)
        {
            if($tag['option_name'] === 'Open_Graph_Cash')
            {
                $cash = (bool) $tag['option_value'];
            }

            if(!empty($tag['option_value']))
            {
                if(isset($this->_openGraphtTags[$tag['option_name']]))
                {
                    $parse = $this->_openGraphtTags[$tag['option_name']];
                    $this->_openGraph->{$parse} = $tag['option_value'];
                }
            }
        }

        if($cash)
        {
            $this->cache->save('OpenGraphTags', $query);
        }        
    }

    /**
     * @param array $data
     */
    public function updateOGdata($data)
    {
        $openGraphOptions = array('Open_Graph_Active' => '', 'Open_Graph_Cash' => '');
        $union = \Nette\Utils\Arrays::mergeTree($this->_openGraphtTags, $openGraphOptions);
        
        foreach ($data as $key => $value)
        {
            if(\Nette\Utils\Arrays::searchKey($union, $key) !== FALSE)
            {
                /* @var $record \Models\Entity\Options\Option */
                $record = $this->getOptionRepository()->findOneBy(array('option_name' => $key));
                $record->setOptionValue($value);
            }
         }
        $this->OpenGraphCacheUnset(false);
        
        $this->_em->flush();
    }
 }