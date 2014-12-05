<?php

namespace PWW\ContentBundle\Twig;

class PWWExtrasExtension extends \Twig_Extension 
{
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('getlogo', array($this, 'getLogo')),
            new \Twig_SimpleFilter('friendlyaudio', array($this, 'friendlyAudio')),
            new \Twig_SimpleFilter('friendlytime', array($this, 'friendlyTime')),
        );
    }
    
    public function getLogo($platform)
    {
        switch ($platform) {
            case 'Roku':
                return 'roku.png';
            case 'Apple TV':
                return 'appletv.png';
            case 'Firefox':
                return 'firefox.png';
            case 'Chromecast':
                return 'chromecast.png';
            case 'Chrome':
                return 'chrome.png';
            case 'Android':
                return 'android.png';
            case 'Nexus':
                return 'android.png';
            case 'iPad':
                return 'ios.png';
            case 'iPhone':
                return 'ios.png';
            case 'iOS':
                return 'ios.png';
            case 'Plex Home Theater':
                return 'pht.png';
            case 'Linux/RPi-XBMC':
                return 'xbmc.png';
            case 'Safari':
                return 'safari.png';
            case 'Internet Explorer':
                return 'ie.png';
            case 'Unknown Browser':
                return 'default.png';
            case 'Windows-XBMC':
                return 'xbmc.png';
            case preg_match("/TV [a-z][a-z]\d\d[a-z]/i", $platform):
                return 'samsung.png';
            default:
                return 'default.png';
        }        
    }
    
    public function getName()
    {
        return 'pww_extras_extension';
    }
    
    public function friendlyTime($seconds)
    {
        $dtF = new \DateTime("@0");
        $dtT = new \DateTime("@$seconds");
        return array(
            "days" => $dtF->diff($dtT)->format('%a'),
            "hours" => $dtF->diff($dtT)->format('%h'),
            "minutes" => $dtF->diff($dtT)->format('%i')
        ); 
    }
}
