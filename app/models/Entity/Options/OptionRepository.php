<?php
namespace Models\Entity\Options; 
/**
 * Description of Options reposiotory
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Doctrine\ORM\EntityRepository;

class OptionRepository extends EntityRepository  {
   
    const OG_PREFIX = 'Open_Graph';
    const PAGE_PREFIX = 'Page_Options';

    /**
    * @param integer $id
    * @return mixed
    */
    public function getOne($id)
    {
        return $this->findOneById($id);
    }
    
    /**
     * @return array
     */
    public function getOptios()
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }
    
    /**
     * @return bool
     */
    public function CheckOpenGraphIsActive()
    {   
        $query = $this->findOneBy(array('option_name' => 'Open_Graph_Active'));
        
        if(empty($query))
        {
            $options = array(
                array('option_name' => self::OG_PREFIX . '_Active', 'option_value' => 1),
                array('option_name' => self::OG_PREFIX . '_Cash', 'option_value' => 1),
                array('option_name' => self::OG_PREFIX . '_Title', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Type', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Url', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Image', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Site_Name', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Description', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Latitude', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Street_Address', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Locality', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Region', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Postal_Code', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Country_Name', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Email', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Phone_Number', 'option_value' => ''),
                array('option_name' => self::OG_PREFIX . '_Fax_Number', 'option_value' => ''),
                );

            foreach ($options as $option)
            {
                $entity = new Option();
                $entity->setOptionName($option['option_name']);
                $entity->setOptionValue($option['option_value']);
                $this->_em->persist($entity);
            }

            $this->_em->flush();            
            
            return $this->CheckOpenGraphIsActive();
        }
        else
        {
            return (bool) $query->getOptionValue();
        }
    }
    
    /**
     * @return bool
     */
    public function CheckPageOptionsIsActive()
    {   
        $query = $this->findOneBy(array('option_name' => self::PAGE_PREFIX . '_Cash'));
        
        if(empty($query))
        {
            $options = array(
                array('option_name' => self::PAGE_PREFIX . '_Cash', 'option_value' => 1),
                array('option_name' => self::PAGE_PREFIX . '_Page_Name', 'option_value' => 'Blog'),
                array('option_name' => self::PAGE_PREFIX . '_Page_Description', 'option_value' => ''),
                array('option_name' => self::PAGE_PREFIX . '_Admin_Email', 'option_value' => ''),
                );

            foreach ($options as $option)
            {
                $entity = new Option();
                $entity->setOptionName($option['option_name']);
                $entity->setOptionValue($option['option_value']);
                $this->_em->persist($entity);
            }

            $this->_em->flush();            
            
            return $this->CheckPageOptionsIsActive();
        }
        else
        {
            return (bool) $query->getOptionValue();
        }
    }
}

