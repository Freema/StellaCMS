<?php
namespace Models\Entity\Options; 
/**
 * Description of Options reposiotory
 *
 * @author Tomáš Grasl <grasl.t@centrum.cz>
 */

use Doctrine\ORM\EntityRepository;

class OptionRepository extends EntityRepository  {
   
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
    
    public function CheckIfPageOptionsIsActive()
    {
        $query = $this->findOneBy(array('option_name' => 'Page_Cache_Active'));
        
        if(empty($query))
        {
            $options = array (
                array('option_name' => 'Page_Cache_Active', 'option_value' => 1),
            ); 
            
            foreach ($options as $option)
            {
                $entity = new Option();
                $entity->setOptionName($option['option_name']);
                $entity->setOptionValue($option['option_value']);
                $this->_em->persist($entity);
            }

            $this->_em->flush();            
            
            return $this->CheckIfPageOptionsIsActive();            
        }
        else
        {
            return (bool) $query->getOptionValue();
        }
    }
    
    public function CheckOpenGraphIsActive()
    {   
        $query = $this->findOneBy(array('option_name' => 'Open_Graph_Active'));
        
        if(empty($query))
        {
            $options = array(
                array('option_name' => 'Open_Graph_Active', 'option_value' => 1),
                array('option_name' => 'Open_Graph_Cash', 'option_value' => 1),
                array('option_name' => 'Open_Graph_Title', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Type', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Url', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Image', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Site_Name', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Description', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Latitude', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Street_Address', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Locality', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Region', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Postal_Code', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Country_Name', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Email', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Phone_Number', 'option_value' => ''),
                array('option_name' => 'Open_Graph_Fax_Number', 'option_value' => ''),
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
}

