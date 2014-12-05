<?php

namespace PWW\ContentBundle\Entity;

use PWW\ContentBundle\Config\YamlConfig;
use PWW\ContentBundle\Config\MyPlexToken;

/**
 * Settings
 */
class Settings
{   
    private $yamlConnector;
    
    /**
     * @var string
     */
    private $dateFormat;

    /**
     * @var string
     */
    private $timeFormat;
    
    /**
     * @var string
     */
    private $updaterPath;

    /**
     * @var string
     */
    private $pmsIpAddress;
    
    /**
     * @var string
     */
    private $pmsWebPort;
    
    /**
     * @var string
     */
    private $pmsSecureWebPort;
    
    /**
     * @var bool
     */
    private $useHttps;
    
    /**
     * @var string
     */
    private $plexWatchDb;
    
    /**
     * @var string
     */
    private $myPlexUsername;
    
    /**
     * @var string
     */
    private $myPlexPassword;
    
    /**
     * @var string
     */
    private $myPlexAuthToken;
    
    /**
     * @var bool
     */
    private $groupingGlobalHistory;
    
    /**
     * @var bool
     */
    private $groupingUserHistory;
    
    /**
     * @var bool
     */
    private $groupingCharts;
    
    public function __construct() {
        $this->yamlConnector = new YamlConfig();
    }
    
    public function getDateFormat() {
        return $this->yamlConnector->getConfigItem('dateFormat');
    }

    public function getTimeFormat() {
        return $this->yamlConnector->getConfigItem('timeFormat');
    }

    public function getUpdaterPath() {
        return $this->yamlConnector->getConfigItem('updaterPath');
    }

    public function getPmsIpAddress() {
        return $this->yamlConnector->getConfigItem('pmsIp');
    }

    public function getPmsWebPort() {
        return $this->yamlConnector->getConfigItem('pmsHttpPort');
    }

    public function getPmsSecureWebPort() {
        return $this->yamlConnector->getConfigItem('pmsHttpsPort');
    }

    public function getUseHttps() {
        return $this->yamlConnector->getConfigItem('https');
    }

    public function getPlexWatchDb() {
        return $this->yamlConnector->getConfigItem('plexWatchDb');
    }

    public function getMyPlexUsername() {
        return $this->yamlConnector->getConfigItem('myPlexUser');
    }

    public function getMyPlexPassword() {
        return base64_decode($this->yamlConnector->getConfigItem('myPlexPass'));
    }
    
    public function getMyPlexAuthToken() {
        return $this->yamlConnector->getConfigItem('myPlexAuthToken');
    }

    public function getGroupingGlobalHistory() {
        return $this->yamlConnector->getConfigItem('globalHistoryGrouping');
    }

    public function getGroupingUserHistory() {
        return $this->yamlConnector->getConfigItem('userHistoryGrouping');
    }

    public function getGroupingCharts() {
        return $this->yamlConnector->getConfigItem('chartsGrouping');
    }

    public function setDateFormat($dateFormat) {
        $this->yamlConnector->setConfigItem('dateFormat', $dateFormat);
    }

    public function setTimeFormat($timeFormat) {
        $this->yamlConnector->setConfigItem('timeFormat', $timeFormat);
    }

    public function setUpdaterPath($updaterPath) {
        $this->yamlConnector->setConfigItem('updaterPath', $updaterPath);
    }

    public function setPmsIpAddress($pmsIpAddress) {
        $this->yamlConnector->setConfigItem('pmsIp', $pmsIpAddress);
    }

    public function setPmsWebPort($pmsWebPort) {
        $this->yamlConnector->setConfigItem('pmsHttpPort', $pmsWebPort);
    }

    public function setPmsSecureWebPort($pmsSecureWebPort) {
        $this->yamlConnector->setConfigItem('pmsHttpsPort', $pmsSecureWebPort);
    }

    public function setUseHttps($useHttps) {
        $this->yamlConnector->setConfigItem('https', $useHttps);
    }

    public function setPlexWatchDb($plexWatchDb) {
        $this->yamlConnector->setConfigItem('plexWatchDb', $plexWatchDb);
    }

    public function setMyPlexUsername($myPlexUsername) {
        $this->yamlConnector->setConfigItem('myPlexUser', $myPlexUsername);
    }

    public function setMyPlexPassword($myPlexPassword) {
        if (!is_null($myPlexPassword)) {
            $this->yamlConnector->setConfigItem('myPlexPass', base64_encode($myPlexPassword));
        }
    }

    public function setGroupingGlobalHistory($groupingGlobalHistory) {
        $this->yamlConnector->setConfigItem('globalHistoryGrouping', $groupingGlobalHistory);
    }

    public function setGroupingUserHistory($groupingUserHistory) {
        $this->yamlConnector->setConfigItem('userHistoryGrouping', $groupingUserHistory);
    }

    public function setGroupingCharts($groupingCharts) {
        $this->yamlConnector->setConfigItem('chartsGrouping', $groupingCharts);
    }
    
    public function setMyPlexAuthToken() {
        $tokenGetter = new MyPlexToken();
        $token = $tokenGetter->getToken($this->getMyPlexUsername(), $this->getMyPlexPassword());
        if ($token['code'] == 201) {
            $this->yamlConnector->setConfigItem('myPlexAuthToken', $token['token']);
        } else {
            return $token['message'] . ' (' . $token['error'] . ')';
        }
    }
}
