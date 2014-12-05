<?php

namespace PWW\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RefreshController extends Controller {
    
    public function __construct() {
         
    }
    
    public function statsAction() {
        /* @var $globalDataService \PWW\DataFactoryBundle\Model\GlobalDataModel */
        $globalDataService = $this->get('pww.datafactorybundle.model.global_data_model');
        
        return $this->render('PWWContentBundle:index:librarystats.html.twig', 
            array(
                'xml' => $globalDataService->getLibrarySectionCount(),
                'users' => $globalDataService->getUniqueUserCount())
            );
    }
    
    public function activityAction() {
        /* @var $globalDataService \PWW\DataFactoryBundle\Model\GlobalDataModel */
        $globalDataService = $this->get('pww.datafactorybundle.model.global_data_model');
        
        return $this->render('PWWContentBundle:index:activity.html.twig', 
            array(
                'xml' => $globalDataService->getCurrentActivity())
            );
    }
    
    public function recentAction(Request $request) {
        /* @var $globalDataService \PWW\DataFactoryBundle\Model\GlobalDataModel */
        $globalDataService = $this->get('pww.datafactorybundle.model.global_data_model');
        
        $width = $request->query->get('width');
        
        return $this->render('PWWContentBundle:index:recentlyadded.html.twig', 
            array(
                'xml' => $globalDataService->getRecentlyAdded($this->getContainerSize($width)))
            );
    }
    
    private function getContainerSize($width)
    {
        $tmp = $width / 186;
        if ($tmp > 0) {
            $containerSize = floor($tmp);
        } else {
            $containerSize = 5;
        }
        
        return $containerSize;
    }
}
