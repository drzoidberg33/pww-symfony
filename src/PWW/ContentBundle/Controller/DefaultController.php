<?php

namespace PWW\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use PWW\ContentBundle\Entity\Settings;
use PWW\ContentBundle\Form\Type\SettingsType;

class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('PWWContentBundle:Default:index.html.twig', array('page' => 'index'));
    }
     
    public function settingsAction(Request $request)
    {
        
        $settings = new Settings();
        $form = $this->createForm(new SettingsType(), $settings);
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            if (!is_null($form->get('myPlexPassword')->getData())) {
                $tokenValidator = $settings->setMyPlexAuthToken();
                if (is_null($tokenValidator)) {
                    return $this->render('PWWContentBundle:Default:settings.html.twig',
                        array(
                            'form' => $form->createView(),
                            'message' => 'Settings Saved',
                            'page' => 'settings')
                        );
                } else {
                    return $this->render('PWWContentBundle:Default:settings.html.twig',
                        array(
                            'form' => $form->createView(),
                            'message' => $tokenValidator,
                            'page' => 'settings')
                        );
                }
            }
            return $this->render('PWWContentBundle:Default:settings.html.twig',
                array(
                    'form' => $form->createView(),
                    'message' => 'Settings Saved',
                    'page' => 'settings')
                );
        }        
        return $this->render('PWWContentBundle:Default:settings.html.twig',
            array(
                'form' => $form->createView(),
                'page' => 'settings')
            );
    }
    
    public function chartsAction()
    {
        /* @var $chartsDataService \PWW\DataFactoryBundle\Model\ChartsDataModel */
        $chartsDataService = $this->get('pww.datafactorybundle.model.charts_data_model');
        
        return $this->render('PWWContentBundle:Default:charts.html.twig',
            array(
                'page' => 'charts',
                'top10' => $chartsDataService->getChartsTop10s())
            );
    }
    
    public function historyAction()       
    {
        return $this->render('PWWContentBundle:Default:history.html.twig',
            array(
                'page' => 'history')
            );
    }
    
    public function statsAction()       
    {
        return $this->render('PWWContentBundle:Default:stats.html.twig',
            array(
                'page' => 'stats')
            );
    }
    
    public function usersAction()       
    {
        return $this->render('PWWContentBundle:Default:users.html.twig', 
            array(
                'page' => 'users')
            );
    }
    
    public function infoAction($entity)       
    {
        /* @var $globalDataService \PWW\DataFactoryBundle\Model\GlobalDataModel */
        $globalDataService = $this->get('pww.datafactorybundle.model.global_data_model');
        
        return $this->render('PWWContentBundle:Default:info.html.twig', 
            array(
                'entity' => $entity,
                'xml' => $globalDataService->getItemMetadata($entity),
                'mostWatched' => $globalDataService->getInfoMostWatchedEpisodes($entity))
            );
    }
    
    public function userAction($entity)       
    {
        /* @var $userDataService \PWW\DataFactoryBundle\Model\UserDataModel */
        $userDataService = $this->get('pww.datafactorybundle.model.user_data_model');
        
        return $this->render('PWWContentBundle:Default:user.html.twig', 
            array(
                'userInfo' => $userDataService->getUserInfo($entity),
                'stats' => $userDataService->getUserWatchStats($entity),
                'platformStats' => $userDataService->getUserPlatformStats($entity),
                'recentlyWatched' => $userDataService->getUserRecentlyWatched($entity)
            )
        );
    }
    
    
}
