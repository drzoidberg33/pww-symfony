<?php

namespace PWW\DataFactoryBundle\Model;

use Doctrine\ORM\EntityManager;
use PWW\DataFactoryBundle\Connector\XMLExtractor;
use PWW\DataFactoryBundle\Connector\WebConnector;
use PWW\ContentBundle\Entity\Settings;

class GlobalDataModel {
    
    private $settings;
    private $repository;
    private $em;
    
    public function __construct(EntityManager $em)
    {   
        $this->settings = new Settings();
        $this->repository = $this->settings->getGroupingGlobalHistory() ? 'PWWDataFactoryBundle:Grouped' : 'PWWDataFactoryBundle:Processed';
        $this->em = $em;
        
        $this->webConnector = new WebConnector();
    }
    
    public function getUniqueUserCount()
    {
        return $this->em->getRepository($this->repository)->getUniqueUserCount('user');
    }
    
    public function getLibrarySectionCount()
    {  
        return $this->webConnector->getLibraryStats();
    }
    
    public function getRecentlyAdded($count)
    { 
        return $this->webConnector->getRecentlyAdded($count);
    }
    
    public function getCurrentActivity()
    {
        return $this->webConnector->getCurrentPlaying();
    }
    
    public function getItemMetadata($id)
    {
        if ($this->webConnector->getMetaDataHasParent($id)) {
            $output['parent'] = $this->webConnector->getMetaData($this->webConnector->getMetaDataParentKey($id));
        }
        $output['metadata'] = $this->webConnector->getMetaData($id);
        $output['children'] = $this->webConnector->getChildrenMetaData($id);
        
        return $output;
    }
    
    public function getInfoMostWatchedEpisodes($grandparentRatingKey)
    {
        $xmlExtractor = new XMLExtractor();
        
        $results = $this->em->getRepository($this->repository)->queryMostWatchedEpisodes($grandparentRatingKey);
        $xml = $xmlExtractor->unXmlArray($results);
        
        $mostWatchedArray = array();
        foreach($xml as $item) {
            $mostWatchedArray[] = array(
                "parent" => $this->webConnector->getMetaData($this->webConnector->getMetaDataParentKey($item['ratingkey'])),
                "metadata" => $this->webConnector->getMetaData($item['ratingkey']),
                "playCount" => $item['playCount']
            );
        }
        
        return $mostWatchedArray;
    }
    
}
