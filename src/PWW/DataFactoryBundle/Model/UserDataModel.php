<?php

namespace PWW\DataFactoryBundle\Model;

use Doctrine\ORM\EntityManager;
use PWW\DataFactoryBundle\Connector\XMLExtractor;
use PWW\DataFactoryBundle\Connector\WebConnector;
use PWW\ContentBundle\Entity\Settings;

class UserDataModel {
    
    private $settings;
    private $repository;
    private $em;
    
    public function __construct(EntityManager $em)
    {    
        $this->settings = new Settings();
        $this->repository = $this->settings->getGroupingUserHistory() ? 'PWWDataFactoryBundle:Grouped' : 'PWWDataFactoryBundle:Processed';
        $this->em = $em;
    }
    
    public function getUserInfo($user)
    {
        $xmlExtractor = new XMLExtractor();
        
        $results = $this->em->getRepository($this->repository)->getOneXmlByUser($user);
        $userInfo = $xmlExtractor->unwrapXml($results, 'user');

        return $userInfo['user'];
    }
                
    public function getUserWatchStats($user)
    {
        $statsArray = array(
            array(
                "title" => "Today",
                "stats" => $this->em->getRepository($this->repository)->getUserStatsLastDay($user)
            ),
            array(
                "title" => "Last Week",
                "stats" => $this->em->getRepository($this->repository)->getUserStatsLastWeek($user)
            ),
            array(
                "title" => "Last Month",
                "stats" => $this->em->getRepository($this->repository)->getUserStatsLastMonth($user)
            ),
            array(
                "title" => "All Time",
                "stats" => $this->em->getRepository($this->repository)->getUserStatsAllTime($user)
            )
        );
        
        return $statsArray;
    }
    
    public function getUserPlatformStats($user)
    {
        $xmlExtractor = new XMLExtractor();
        
        $results = $this->em->getRepository($this->repository)->queryPlatformStatsByUser($user);
        $xml = $xmlExtractor->unXmlArray($results);
        
        $outputArray = array();
        
        foreach($xml as $item){
            $outputArray[] = array(
                "platform" => $item['media']['platform']['platform'],
                "platformName" => $item['media']['platform']['title'],
                "playCount" => $item['platformCount']
            );
        }
        
        return $outputArray;
    }
    
    public function getUserRecentlyWatched($user)
    {
        $webConnector = new WebConnector();
        
        $results = $this->em->getRepository($this->repository)->queryRecentlyWatchedByUser($user);
        
        $recentlyWatchedArray = array();
        foreach($results as $item) {
            if ($webConnector->getMetaDataHasParent($item['ratingkey'])) {
                $recentlyWatchedArray[] = array(
                    "parent" => $webConnector->getMetaData($webConnector->getMetaDataParentKey($item['ratingkey'])),
                    "metadata" => $webConnector->getMetaData($item['ratingkey']),
                    "children" => $webConnector->getChildrenMetaData($item['ratingkey'])
                );
            } else {
                $recentlyWatchedArray[] = array(
                    "metadata" => $webConnector->getMetaData($item['ratingkey']),
                    "children" => $webConnector->getChildrenMetaData($item['ratingkey'])
                );
            }
        }

        return $recentlyWatchedArray;
    }

}
