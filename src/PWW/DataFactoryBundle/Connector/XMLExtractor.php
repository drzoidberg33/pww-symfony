<?php

namespace PWW\DataFactoryBundle\Connector;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class XMLExtractor {
    
    public function __construct()
    {
        
    }
    
    private function normalizeObject($object)
    {
        $normalizer = new GetSetMethodNormalizer();
        
        if (is_object($object)) {
            return $normalizer->normalize($object);
        }
        
        return $object;
    }
    
    public function extractPlatformXml($xml) {
        $crawler = new Crawler($xml);
        $platformArray = array();
        
        if( $crawler->filterXpath('//opt/player')->count() ) {
            $node = $crawler->filterXpath('//opt/player');
            $platformArray = array(
                'platform' =>  $node->attr('platform'),
                'title' =>  $node->attr('title')
            );
        }
        
        return $platformArray;
    }
    
    public function extractUserInfoXml($xml) {
        $crawler = new Crawler($xml);
        $userInfoArray = array();
        
        if( $crawler->filterXpath('//opt/user')->count() ) {
            $node = $crawler->filterXpath('//opt/user');
            $userInfoArray = array(
                'id' =>  $node->attr('id'),
                'thumb' =>  $node->attr('thumb'),
                'title' =>  $node->attr('title')
            );
        }
        
        return $userInfoArray;
    }
    
    private function extractXml($xml)
    {
        $crawler = new Crawler($xml);
        $attr_array = array();
        if( $crawler->filterXpath('//opt/media')->count() ) {
            $node = $crawler->filterXpath('//opt/media');
            $attr_array['mediaInfo'] = array(
                'audioChannels' =>  $node->attr('audiochannels'),
                'audioCodec' =>  $node->attr('audiocodec'),
                'bitrate' =>  $node->attr('bitrate'),
                'container' =>  $node->attr('container'),
                'height' =>  $node->attr('height'),
                'videoCodec' =>  $node->attr('videocodec'),
                'videoFramerate' =>  $node->attr('videoframerate'),
                'videoResolution' =>  $node->attr('videoresolution'),
                'width' =>  $node->attr('width')
            );
        } 
        if( $crawler->filterXpath('//opt/transcodesession')->count() ) {
            $node = $crawler->filterXpath('//opt/transcodesession');
            $attr_array['transcodeSession'] = array(
                'audioChannels' =>  $node->attr('audiochannels'),
                'audioCodec' =>  $node->attr('audiocodec'),
                'audioDecision' =>  $node->attr('audiodecision'),
                'container' =>  $node->attr('container'),
                'height' =>  $node->attr('height'),
                'protocol' =>  $node->attr('protocol'),
                'videoCodec' =>  $node->attr('videocodec'),
                'videoDecision' =>  $node->attr('videodecision'),
                'width' =>  $node->attr('width')
            );
        }
        
        if( $crawler->filterXpath('//opt/user')->count() ) {
            $node = $crawler->filterXpath('//opt/user');
            $attr_array['userInfo'] = array(
                'id' =>  $node->attr('id'),
                'thumb' =>  $node->attr('thumb'),
                'title' =>  $node->attr('title')
            );
        } 
        
        if( $crawler->filterXpath('//opt/player')->count() ) {
            $node = $crawler->filterXpath('//opt/player');
            $attr_array['platform'] = array(
                'platform' =>  $node->attr('platform'),
                'title' =>  $node->attr('title')
            );
        } 
        $attr_array['type'] = $crawler->filterXpath('//opt')->attr('type');
        
        return $attr_array;
    }
    
    public function unXml($object)
    {
        if (is_object($object)) {
            $normalized = $this->normalizeObject($object);
        } else {
            $normalized = $object;
        }
        
        $outputArray = array();
        
        foreach($normalized as $key => $value) {
            if ($key !== 'xml') {
                $outputArray[$key] = $value;
            } else {
                $outputArray['media'] = $this->extractXml($value);
            }
        }
        
        return $outputArray;
    }
    
    public function unXmlArray($objectArray)
    {
        $outputArray = array();
        
        foreach ($objectArray as $object) {   
            $outputArray[] = $this->unXml($object);
        }
        return $outputArray;
    }
    
    public function unwrapXml($data, $type = 'all')
    {
        
        $normalized = $this->normalizeObject($data);
        $outputArray = array();
        
        switch ($type) {
            case 'all':
                foreach($normalized as $key => $value) {
                    if ($key !== 'xml') {
                        $outputArray[$key] = $value;
                    } else {
                        $outputArray['media'] = $this->extractXml($value);
                    }
                }
                break;
            case 'platform':
                foreach($normalized as $key => $value) {
                    if ($key !== 'xml') {
                        $outputArray[$key] = $value;
                    } else {
                        $outputArray['platform'] = $this->extractPlatformXml($value);
                    }
                }
                break;
            case 'user':
                foreach($normalized as $key => $value) {
                    if ($key !== 'xml') {
                        $outputArray[$key] = $value;
                    } else {
                        $outputArray['user'] = $this->extractUserInfoXml($value);
                    }
                }
                break;
            default:
                break; 
        }
        
        return $outputArray;
    }
}
