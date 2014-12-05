<?php

namespace PWW\DataFactoryBundle\Model;

use Doctrine\ORM\EntityManager;
use PWW\DataFactoryBundle\Connector\XMLExtractor;
use PWW\DataFactoryBundle\Connector\WebConnector;
use PWW\ContentBundle\Entity\Settings;

class ChartsDataModel {
    
    private $settings;
    private $repository;
    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->settings = new Settings();
        $this->repository = $this->settings->getGroupingCharts() ? 'PWWDataFactoryBundle:Grouped' : 'PWWDataFactoryBundle:Processed';
        $this->em = $em;
    }
    
    public function getChartsTop10s()
    {
        $xmlExtractor = new XMLExtractor();
        $webConnector = new WebConnector();
        
        $results = $this->em->getRepository($this->repository)->queryGrouped();
        $resultsShows = $this->em->getRepository($this->repository)->queryGroupedShows();
        $xml = $xmlExtractor->unXmlArray($results);
        $xmlShows = $xmlExtractor->unXmlArray($resultsShows);
        
        $outputArray = array();
        $i = 0;
        
        foreach($xml as $item) {
            $outputArray['All'][] = array(
                "section" => 'All',
                "ratingKey" => $item['ratingkey'],
                "origTitle" => $item['origTitle'],
                "origTitleEp" => $item['origTitleEp'],
                "playCount" => $item['playCount'],
                "episode" => $item['episode'],
                "season" => $item['season'],
                "year" => $item['year'],
                "type" => $item['media']['type'],
                "parent" => $webConnector->getMetaData($webConnector->getMetaDataParentKey($item['ratingkey'])),
                "metadata" => $webConnector->getMetaData($item['ratingkey'])
            );
            $i++;
            if ($i >= 10) { $i = 0; break; }
        }
        
        foreach($xml as $item) {
            if ($item['media']['type'] == 'movie') {
                $outputArray['Films'][] = array(
                    "section" => 'Movies',
                    "ratingKey" => $item['ratingkey'],
                    "origTitle" => $item['origTitle'],
                    "playCount" => $item['playCount'],
                    "year" => $item['year'],
                    "type" => $item['media']['type'],
                    "metadata" => $webConnector->getMetaData($item['ratingkey'])
                );
                $i++;
            }
            if ($i >= 10) { $i = 0; break; }
        }
        
        foreach($xml as $item) {
            if ($item['media']['type'] == 'episode') {
                $outputArray['Episodes'][] = array(
                    "section" => 'Episodes',
                    "ratingKey" => $item['ratingkey'],
                    "origTitle" => $item['origTitle'],
                    "origTitleEp" => $item['origTitleEp'],
                    "playCount" => $item['playCount'],
                    "episode" => $item['episode'],
                    "season" => $item['season'],
                    "year" => $item['year'],
                    "type" => $item['media']['type'],
                    "parent" => $webConnector->getMetaData($webConnector->getMetaDataParentKey($item['ratingkey'])),
                    "metadata" => $webConnector->getMetaData($item['ratingkey'])
                );
                $i++;
            }
            if ($i >= 10) { $i = 0; break; }
        }
        
        foreach($xmlShows as $item) {
            if ($item['media']['type'] == 'episode') {
                $outputArray['Shows'][] = array(
                    "section" => 'Shows',
                    "ratingKey" => $item['ratingkey'],
                    "origTitle" => $item['origTitle'],
                    "origTitleEp" => $item['origTitleEp'],
                    "playCount" => $item['playCount'],
                    "episode" => $item['episode'],
                    "season" => $item['season'],
                    "year" => $item['year'],
                    "type" => $item['media']['type'],
                    "parent" => $webConnector->getMetaData($webConnector->getMetaDataParentKey($item['ratingkey'])),
                    "metadata" => $webConnector->getMetaData($item['ratingkey'])
                );
                $i++;
            }
            if ($i >= 10) { $i = 0; break; }
        }
        
        return $outputArray;
    }
    
    public function getChartsTop10All()
    {
        $xmlExtractor = new XMLExtractor();
        $webConnector = new WebConnector();
        
        $results = $this->em->getRepository($this->repository)->queryGrouped();
        $xml = $xmlExtractor->unXmlArray($results);
        
        $outputArray = array();
        $i = 0;
        
        foreach($xml as $item) {
            $outputArray['All'] = array(
                "ratingKey" => $item['ratingkey'],
                "origTitle" => $item['origTitle'],
                "origTitleEp" => $item['origTitleEp'],
                "playCount" => $item['playCount'],
                "episode" => $item['episode'],
                "season" => $item['season'],
                "year" => $item['year'],
                "type" => $item['media']['type'],
                "parent" => $webConnector->getMetaData($webConnector->getMetaDataParentKey($item['ratingkey'])),
                "metadata" => $webConnector->getMetaData($item['ratingkey'])
            );
            $i++;
            if ($i >= 10) { $i = 0; break; }
        }
        
        foreach($xml as $item) {
            if ($item['media']['type'] == 'movie') {
                $outputArray['Films'] = array(
                    "ratingKey" => $item['ratingkey'],
                    "origTitle" => $item['origTitle'],
                    "playCount" => $item['playCount'],
                    "year" => $item['year'],
                    "type" => $item['media']['type'],
                    "metadata" => $webConnector->getMetaData($item['ratingkey'])
                );
                $i++;
            }
            if ($i >= 10) { $i = 0; break; }
        }
        
        
        return $outputArray;
    }
    
    public function getChartsTop10Films()
    {
        $xmlExtractor = new XMLExtractor();
        $webConnector = new WebConnector();
        
        $results = $this->em->getRepository($this->repository)->queryGrouped();
        $xml = $xmlExtractor->unXmlArray($results);
        
        $outputArray = array();
        
        $i = 0;
        
        foreach($xml as $item) {
            if ($item['media']['type'] == 'movie') {
                $outputArray[] = array(
                    "ratingKey" => $item['ratingkey'],
                    "origTitle" => $item['origTitle'],
                    "playCount" => $item['playCount'],
                    "year" => $item['year'],
                    "type" => $item['media']['type'],
                    "metadata" => $webConnector->getMetaData($item['ratingkey'])
                );
                $i++;
            }
            if ($i >= 10) { break; }
        }
        
        return $outputArray;
    }
}
