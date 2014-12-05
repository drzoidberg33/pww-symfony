<?php

namespace PWW\DataFactoryBundle\Connector;

use PWW\ContentBundle\Config\YamlConfig;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Constraints\DateTime;

class WebConnector {
    
    private $config;
    
    public function __construct()
    {
        $this->config = new YamlConfig();
    }
    
    private function curlXmlData($url)
    {
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/xml; charset=utf-8', 'Content-Length: 0', 'X-Plex-Client-Identifier: plexWatchWeb'));
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($process);
    }
    
    private function getPMSPath()
    {
        $protocol = 'http://';
        $port = $this->config->getConfigItem('pmsHttpPort');
        
        if ($this->config->getConfigItem('https')) {
            $protocol = 'https://';
            $port = $this->config->getConfigItem('pmsHttpsPort');
        }
        
        return $protocol .
            $this->config->getConfigItem('pmsIp') . ':' .
            $port;
    }
    
    public function needToken()
    {
        if ($this->config->getConfigItem('myPlexAuthToken') !== '') {
            return true;
        }
        
        return false;
    }
    
    private function getRecentlyAddedXml($count)
    {
        if ($this->needToken()) {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/library/recentlyAdded?X-Plex-Token=" . 
                $this->config->getConfigItem('myPlexAuthToken') .
                "&X-Plex-Container-Start=0&X-Plex-Container-Size=" .
                $count);
        } else {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/library/recentlyAdded?X-Plex-Container-Start=0&X-Plex-Container-Size=" .
                $count);
        }

        return $fileContents;        
    }
    
    private function getCurrentActivityXml()
    {
        if ($this->needToken()) {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/status/sessions?X-Plex-Token=" . 
                $this->config->getConfigItem('myPlexAuthToken'));
        } else {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/status/sessions");
        }

        return $fileContents;
    }
    
    private function getLibraryXml()
    {
        if ($this->needToken()) {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/library/sections?X-Plex-Token=" . 
                $this->config->getConfigItem('myPlexAuthToken'));
        } else {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/library/sections");
        }
        
        return $fileContents;
    }
    
    private function getLibrarySectionXml($section, $type=null)
    {
        $typeString = "";
        if ($type !== null) {
            $typeString = "&type=$type";
        }
        
        if ($this->needToken()) {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/library/sections/" .
                $section .
                "/all" .
                "?X-Plex-Container-Start=0&X-Plex-Container-Size=1&X-Plex-Token=" .
                $this->config->getConfigItem('myPlexAuthToken') .
                $typeString);
        } else {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/library/sections/" .
                $section .
                "/all" .
                "?X-Plex-Container-Start=0&X-Plex-Container-Size=1" .
                $typeString);
        }
        
        return $fileContents;
    }
     
    private function getMetaDataXml($id)
    {
        if ($this->needToken()) {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/library/metadata/$id?X-Plex-Token=" . 
                $this->config->getConfigItem('myPlexAuthToken'));
        } else {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/library/metadata/$id");
        }

        return $fileContents;
    }
    
    private function getChildrenXml($id)
    {
        if ($this->needToken()) {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/library/metadata/$id/children?X-Plex-Token=" . 
                $this->config->getConfigItem('myPlexAuthToken'));
        } else {
            $fileContents = 
                $this->curlXmlData($this->getPMSPath() . 
                "/library/metadata/$id/children");
        }
        return $fileContents;
    }
    
    public function getLibraryStats()
    {
        $crawler = new Crawler($this->getLibraryXml());
        $htmlArray = array(); 
        
        $libraryArray = $crawler->filterXPath('//Directory')->each(function (Crawler $node, $i) {
            return array(
                "type" => $node->attr('type'),
                "title" => $node->attr('title'),
                "key" => $node->attr('key')
            );
        });
        
        foreach ($libraryArray as $libraryItem) {
            switch ($libraryItem['type']) {
                case 'movie':
                    $sectionCrawler = new Crawler($this->getLibrarySectionXml($libraryItem['key']));
                    $htmlArray[$libraryItem['title']]['titleCount'] = $sectionCrawler->filterXPath('//MediaContainer')->attr('totalSize');
                    break;
                case 'show':
                    $sectionCrawler = new Crawler($this->getLibrarySectionXml($libraryItem['key'],2));
                    $htmlArray[$libraryItem['title']]['titleCount'] = $sectionCrawler->filterXPath('//MediaContainer')->attr('totalSize');
                    $sectionCrawler = new Crawler($this->getLibrarySectionXml($libraryItem['key'],4));
                    $htmlArray[$libraryItem['title']]['episodeCount'] = $sectionCrawler->filterXPath('//MediaContainer')->attr('totalSize');
                    break;
                case 'artist':
                    $sectionCrawler = new Crawler($this->getLibrarySectionXml($libraryItem['key']));
                    $htmlArray[$libraryItem['title']]['titleCount'] = $sectionCrawler->filterXPath('//MediaContainer')->attr('totalSize');
                    break;
                default:
                    break;
            }
        }
        
        return $htmlArray;
    }
    
    private function getThumbPath($node)
    {
        if ($node->filterXPath('//Media/Part')->count()) {    
            if ($node->filterXPath('//Media/Part')->attr('indexes') == 'sd') {
                return urlencode(
                    $this->getPMSPath() . 
                    "/photo/:/transcode?url=http://127.0.0.1:" .
                    $this->config->getConfigItem('pmsHttpPort') .
                    "/library/parts/" . 
                    $node->filterXPath('//Media/Part')->attr('id') . 
                    "/indexes/sd/" .
                    floor($node->attr('viewOffset')) .
                    "&width=320&height=160");
            } else {
                return urlencode(
                    $this->getPMSPath() . 
                    "/photo/:/transcode?url=http://127.0.0.1:" .
                    $this->config->getConfigItem('pmsHttpPort') .
                    $node->attr('thumb') .
                    "&width=320&height=160");
            }
        }
    }
    
    public function getCurrentPlaying()
    {
        
        $crawler = new Crawler($this->getCurrentActivityXml());
        
        $mediaContent = array();
        $mediaContent['playCount'] = $crawler->filterXPath('//MediaContainer')->attr('size');
        if( $crawler->filterXpath('//MediaContainer/Video')->count() ) {
            $mediaContent['video'] = $crawler->filterXPath('//MediaContainer/Video')->each(function (Crawler $node, $i) {
                if( $node->filterXpath('//TranscodeSession')->count() ) {
                    return array(
                        "type" => $node->attr('type'),
                        "grandparentTitle" => $node->attr('grandparentTitle'),
                        "ratingKey" => $node->attr('ratingKey'),
                        "sessionKey" => $node->attr('sessionKey'),
                        "title" => $node->attr('title'),
                        "viewOffset" => $node->attr('viewOffset'),
                        "duration" => $node->attr('duration'),
                        "progress" => (($node->attr('viewOffset') / $node->attr('duration')) * 100), 
                        "platform" => $node->filterXPath('//Player')->attr('platform'),
                        "state" => $node->filterXPath('//Player')->attr('state'),
                        "videoDecision" => $node->filterXPath('//TranscodeSession')->attr('videoDecision'),
                        "audioDecision" => $node->filterXPath('//TranscodeSession')->attr('audioDecision'),
                        "width" => $node->filterXPath('//TranscodeSession')->attr('width'),
                        "height" => $node->filterXPath('//TranscodeSession')->attr('height'),
                        "videoCodec" => $node->filterXPath('//TranscodeSession')->attr('videoCodec'),
                        "audioCodec" => $node->filterXPath('//TranscodeSession')->attr('audioCodec'),
                        "audioChannels" => $node->filterXPath('//TranscodeSession')->attr('audioChannels'),
                        "user" => $node->filterXPath('//User')->attr('title'),
                        "art" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('art') .
                            "&width=320&height=160"),
                        "indexes" => $node->filterXPath('//Media/Part')->attr('indexes'),
                        "thumb" => $this->getThumbPath($node)
                    );
                } else {
                    return array(
                        "type" => $node->attr('type'),
                        "grandparentTitle" => $node->attr('grandparentTitle'),
                        "ratingKey" => $node->attr('ratingKey'),
                        "sessionKey" => $node->attr('sessionKey'),
                        "title" => $node->attr('title'),
                        "viewOffset" => $node->attr('viewOffset'),
                        "duration" => $node->attr('duration'),
                        "progress" => (($node->attr('viewOffset') / $node->attr('duration')) * 100), 
                        "audioChannels" => $node->filterXPath('//Media')->attr('audioChannels'),
                        "audioCodec" => $node->filterXPath('//Media')->attr('audioCodec'),
                        "height" => $node->filterXPath('//Media')->attr('height'),
                        "width" => $node->filterXPath('//Media')->attr('width'),
                        "videoCodec" => $node->filterXPath('//Media')->attr('videoCodec'),
                        "platform" => $node->filterXPath('//Player')->attr('platform'),
                        "state" => $node->filterXPath('//Player')->attr('state'),
                        "videoDecision" => "Direct Play",
                        "audioDecision" => "Direct Play",
                        "user" => $node->filterXPath('//User')->attr('title'),
                        "art" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('art') .
                            "&width=320&height=160"),
                        "indexes" => $node->filterXPath('//Media/Part')->attr('indexes'),
                        "thumb" => $this->getThumbPath($node)
                    );
                }
            });
        }
        
        if( $crawler->filterXpath('//MediaContainer/Track')->count() ) {
            $mediaContent['music'] = $crawler->filterXPath('//MediaContainer/Track')->each(function (Crawler $node, $i) {
                if( $node->filterXpath('//TranscodeSession')->count() ) {
                    return array(
                        "type" => $node->attr('type'),
                        "grandparentTitle" => $node->attr('grandparentTitle'),
                        "ratingKey" => $node->attr('ratingKey'),
                        "sessionKey" => $node->attr('sessionKey'),
                        "title" => $node->attr('title'),
                        "parentTitle" => $node->attr('parentTitle'),
                        "viewOffset" => $node->attr('viewOffset'),
                        "duration" => $node->attr('duration'),
                        "progress" => (($node->attr('viewOffset') / $node->attr('duration')) * 100), 
                        "platform" => $node->filterXPath('//Player')->attr('platform'),
                        "state" => $node->filterXPath('//Player')->attr('state'),
                        "user" => $node->filterXPath('//User')->attr('title'),
                        "audioDecision" => $node->filterXPath('//TranscodeSession')->attr('audioDecision'),
                        "audioCodec" => $node->filterXPath('//TranscodeSession')->attr('audioCodec'),
                        "audioChannels" => $node->filterXPath('//TranscodeSession')->attr('audioChannels'),
                        "art" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('parentThumb') .
                            "&width=300&height=300")
                    );
                } else {
                    return array(
                        "type" => $node->attr('type'),
                        "grandparentTitle" => $node->attr('grandparentTitle'),
                        "ratingKey" => $node->attr('ratingKey'),
                        "sessionKey" => $node->attr('sessionKey'),
                        "title" => $node->attr('title'),
                        "parentTitle" => $node->attr('parentTitle'),
                        "viewOffset" => $node->attr('viewOffset'),
                        "duration" => $node->attr('duration'),
                        "progress" => (($node->attr('viewOffset') / $node->attr('duration')) * 100), 
                        "audioDecision" => 'Direct Play',
                        "audioChannels" => $node->filterXPath('//Media')->attr('audioChannels'),
                        "audioCodec" => $node->filterXPath('//Media')->attr('audioCodec'),
                        "platform" => $node->filterXPath('//Player')->attr('platform'),
                        "state" => $node->filterXPath('//Player')->attr('state'),
                        "user" => $node->filterXPath('//User')->attr('title'),
                        "art" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('parentThumb') .
                            "&width=300&height=300")
                    );
                }
            }); 
        }
        
        return $mediaContent;
    }
    
    public function getRecentlyAdded($count)
    {
        $crawler = new Crawler($this->getRecentlyAddedXml($count));
        $htmlArray = array();
        
        $mediaTypes = $crawler->filterXPath('//MediaContainer/*')->each(function (Crawler $node, $i) {
            return array(
                'thumb' => $node->attr('thumb'),
                'art' => $node->attr('art'),
                'title' => $node->attr('title'),
                'ratingKey' => $node->attr('ratingKey'),
                'addedAt' => $node->attr('addedAt')
             );
        });
        
        foreach($mediaTypes as $item) {
            
            $age = new \DateTime();
            $age->setTimestamp($item['addedAt']);
            
            $htmlArray[] = array(
                'recentArtUrl' => urlencode(
                        $this->getPMSPath() . 
                        "/photo/:/transcode?url=http://127.0.0.1:" .
                        $this->config->getConfigItem('pmsHttpPort') .
                        $item['art'] .
                        "&width=320&height=160"),
                'recentThumbUrl' => urlencode(
                        $this->getPMSPath() . 
                        "/photo/:/transcode?url=http://127.0.0.1:" .
                        $this->config->getConfigItem('pmsHttpPort') .
                        $item['thumb'] .
                        "&width=153&height=225"),
                'title' => $item['title'],
                'ratingKey' => $item['ratingKey'],
                'addedAt' => $age
            );
        }
        
        return $htmlArray;
    }
    
    public function getMetaDataParentKey($id)
    {
        $crawler = new Crawler($this->getMetaDataXml($id));
        
        if ( $crawler->filterXpath('//MediaContainer/Directory')->count() ) {
            $parentKey = $crawler->filterXPath('//MediaContainer/Directory')->attr('parentRatingKey');
        } else if ( $crawler->filterXpath('//MediaContainer/Video')->count() ) {
            $parentKey = $crawler->filterXPath('//MediaContainer/Video')->attr('parentRatingKey');
        } else {
            $parentKey = null;
        }
        
        if (is_null($parentKey)) {
            return false;
        }
        
        return $parentKey;
    }
    
    public function getMetaDataHasParent($id)
    {
        $crawler = new Crawler($this->getMetaDataXml($id));
        
        if ( $crawler->filterXpath('//MediaContainer/Directory')->count() ) {
            $parentKey = $crawler->filterXPath('//MediaContainer/Directory')->attr('parentRatingKey');
        } else if ( $crawler->filterXpath('//MediaContainer/Video')->count() ) {
            $parentKey = $crawler->filterXPath('//MediaContainer/Video')->attr('parentRatingKey');
        } else {
            $parentKey = null;
        }
        
        if (is_null($parentKey)) {
            return false;
        }
        
        return true;
        
    }
    
    public function getMetaData($id)
    {
        
        $crawler = new Crawler($this->getMetaDataXml($id));
        $metaData = array();

        if( $crawler->filterXpath('//MediaContainer')->count() ) {
            
            if ( $crawler->filterXpath('//MediaContainer/Directory')->count() ) {
                $contentType = $crawler->filterXPath('//MediaContainer/Directory')->attr('type');
            } else if ( $crawler->filterXpath('//MediaContainer/Video')->count() ) {
                $contentType = $crawler->filterXPath('//MediaContainer/Video')->attr('type');
            } else {
                $contentType = '';
            }
            
            switch ($contentType) {
                case 'movie':
                    $node = $crawler->filterXPath('//MediaContainer/Video');
                    $metaData = array(
                        "ratingKey" => $node->attr('ratingKey'),
                        "studio" => $node->attr('studio'),
                        "type" => $node->attr('type'),
                        "title" => $node->attr('title'),
                        "contentRating" => $node->attr('contentRating'),
                        "summary" => $node->attr('summary'),
                        "rating" => $node->attr('rating'),
                        "year" => $node->attr('year'),
                        "tagline" => $node->attr('tagline'),
                        "year" => $node->attr('year'),
                        "originallyAvailableAt" => $node->attr('originallyAvailableAt'),
                        "duration" => $node->attr('duration'),
                        "director" => 'Unknown',
                        "genres" => array('Unknown'),
                        "writers" => array('Unknown'),
                        "producers" => array('Unknown'),
                        "cast" => array('Unknown'),
                        "thumb" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('thumb') .
                            "&width=256&height=352"),
                        "art" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('art') .
                            "&width=1920&height=1080")
                    );
                    if ($crawler->filterXPath('//MediaContainer/Video/Director')->count()) {
                        $metaData['director'] = $crawler->filterXPath('//MediaContainer/Video/Director')->attr('tag');
                    }
                    if ($crawler->filterXPath('//MediaContainer/Video/Genre')->count()) {
                        $metaData['genres'] = $crawler->filterXPath('//Genre')->each(function (Crawler $node, $i) {
                            return $node->attr('tag');
                        });
                    }
                    if ($crawler->filterXPath('//MediaContainer/Video/Writer')->count()) {
                        $metaData['writers'] = $crawler->filterXPath('//Writer')->each(function (Crawler $node, $i) {
                            return $node->attr('tag');
                        });
                    }
                    if ($crawler->filterXPath('//MediaContainer/Video/Producer')->count()) {
                        $metaData['producers'] = $crawler->filterXPath('//Producer')->each(function (Crawler $node, $i) {
                            return $node->attr('tag');
                        });
                    }
                    if ($crawler->filterXPath('//MediaContainer/Video/Role')->count()) {
                        $metaData['cast'] = $crawler->filterXPath('//Role')->each(function (Crawler $node, $i) {
                            return $node->attr('tag');
                        });
                    }
                    break;
                case 'show':
                    $node = $crawler->filterXPath('//MediaContainer/Directory');
                    $metaData = array(
                        "ratingKey" => $node->attr('ratingKey'),
                        "studio" => $node->attr('studio'),
                        "type" => $node->attr('type'),
                        "title" => $node->attr('title'),
                        "contentRating" => $node->attr('contentRating'),
                        "summary" => $node->attr('summary'),
                        "rating" => $node->attr('rating'),
                        "year" => $node->attr('year'),
                        "year" => $node->attr('year'),
                        "originallyAvailableAt" => $node->attr('originallyAvailableAt'),
                        "duration" => $node->attr('duration'),
                        "genres" => $node->filterXPath('//Genre')->each(function (Crawler $node, $i) {
                            return $node->attr('tag');
                        }),
                        "cast" => $node->filterXPath('//Role')->each(function (Crawler $node, $i) {
                            return $node->attr('tag');
                        }),
                        "thumb" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('thumb') .
                            "&width=256&height=352"),
                        "art" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('art') .
                            "&width=1920&height=1080")
                    );
                    break;
                case 'season':
                    $node = $crawler->filterXPath('//MediaContainer/Directory');
                    $metaData = array(
                        "ratingKey" => $node->attr('ratingKey'),
                        "parentRatingKey" => $node->attr('parentRatingKey'),
                        "type" => $node->attr('type'),
                        "title" => $node->attr('title'),
                        "parentTitle" => $node->attr('parentTitle'),
                        "index" => $node->attr('index'),
                        "parentIndex" => $node->attr('parentIndex'),
                        "thumb" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('thumb') .
                            "&width=256&height=352"),
                        "art" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('art') .
                            "&width=1920&height=1080")
                    );
                    break;
                case 'episode':
                    $node = $crawler->filterXPath('//MediaContainer/Video');
                    $metaData = array(
                        "ratingKey" => $node->attr('ratingKey'),
                        "parentRatingKey" => $node->attr('parentRatingKey'),
                        "grandparentRatingKey" => $node->attr('grandparentRatingKey'),
                        "type" => $node->attr('type'),
                        "title" => $node->attr('title'),
                        "grandparentTitle" => $node->attr('grandparentTitle'),
                        "contentRating" => $node->attr('contentRating'),
                        "summary" => $node->attr('summary'),
                        "index" => $node->attr('index'),
                        "parentIndex" => $node->attr('parentIndex'),
                        "rating" => $node->attr('rating'),
                        "year" => $node->attr('year'),
                        "originallyAvailableAt" => $node->attr('originallyAvailableAt'),
                        "duration" => $node->attr('duration'),
                        "director" => 'Unknown',
                        "writers" => array('Unknown'),
                        "thumb" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('thumb') .
                            "&width=256&height=352"),
                        "art" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('art') .
                            "&width=1920&height=1080")
                    );
                    if ($crawler->filterXPath('//MediaContainer/Video/Director')->count()) {
                        $metaData['director'] = $crawler->filterXPath('//MediaContainer/Video/Director')->attr('tag');
                    }
                    if ($crawler->filterXPath('//MediaContainer/Video/Writer')->count()) {
                        $metaData['writers'] = $crawler->filterXPath('//Writer')->each(function (Crawler $node, $i) {
                            return $node->attr('tag');
                        });
                    }
                    break;
                default;
                    break;
            }
        }
        
        return $metaData;
    }
    
    public function getChildrenMetaData($id)
    {
        $crawler = new Crawler($this->getChildrenXml($id));
        $metaData = array();
        
        if ( $crawler->filterXpath('//MediaContainer')->count() ) {
            $contentType = $crawler->filterXPath('//MediaContainer')->attr('viewGroup');
        } else {
            $contentType = '';
        }
            
        switch ($contentType) {
            case 'season':
                $metaData = $crawler->filterXPath('//MediaContainer/Directory')->each(function (Crawler $node, $i) {
                    return array(
                        "ratingKey" => $node->attr('ratingKey'),
                        "parentRatingKey" => $node->attr('parentRatingKey'),
                        "title" => $node->attr('title'),
                        "addedAt" => $node->attr('addedAt'),
                        "index" => $node->attr('index'),
                        "viewCount" => $node->attr('viewCount'),
                        "thumb" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('thumb') .
                            "&width=205&height=115")
                    );
                });
                break;
            case 'episode':
                $metaData = $crawler->filterXPath('//MediaContainer/Video')->each(function (Crawler $node, $i) {
                    return array(
                        "ratingKey" => $node->attr('ratingKey'),
                        "parentRatingKey" => $node->attr('parentRatingKey'),
                        "title" => $node->attr('title'),
                        "addedAt" => $node->attr('addedAt'),
                        "index" => $node->attr('index'),
                        "viewCount" => $node->attr('viewCount'),
                        "summary" => $node->attr('summary'),
                        "thumb" => urlencode(
                            $this->getPMSPath() . 
                            "/photo/:/transcode?url=http://127.0.0.1:" .
                            $this->config->getConfigItem('pmsHttpPort') .
                            $node->attr('thumb') .
                            "&width=205&height=115")
                    );
                });
                break;
            default:
                break;
        }
        
        return $metaData;
    }
}
