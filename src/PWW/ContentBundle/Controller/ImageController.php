<?php

namespace PWW\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use PWW\ContentBundle\Entity\Settings;

class ImageController extends Controller {
    
    public function imgAction(Request $request) {
        if ($request->getMethod() == 'GET') {
            $settings = new Settings();
            
            if (!is_null($settings->getMyPlexUsername())) {
                $imgUrl = $request->get('img').'&X-Plex-Token=' . $settings->getMyPlexAuthToken();   
            } else {
                $imgUrl = $request->get('img');
            }
            
            $response = new Response();
            $response->headers->set('Cache-Control', 'private'); 
            $response->headers->set('Content-type', 'image/jpg');
            
            $response->sendHeaders();
            $response->setContent(readfile($imgUrl));
            
            return $response;
        }
        return false;
    }
    
}
