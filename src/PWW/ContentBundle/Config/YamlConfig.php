<?php

namespace PWW\ContentBundle\Config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Dumper;

class YamlConfig {
    
    private $configArray;
    private $configFile;
    
    public function __construct()
    {
        $this->configFile = __DIR__ . "/config/config.yml";
        $yaml = new Parser();
        
        try {
            $this->configArray = $yaml->parse(file_get_contents($this->configFile));
        } catch (ParseException $ex) {
            throw $ex->getMessage();
        }
    }
        
    public function getConfigItem($item)
    {
        try {
            return $this->configArray['main'][$item];
        } catch (Exception $ex) {
            throw $ex->getMessage();
        }
    }
    
    public function setConfigItem($item, $value)
    {
        try {
            $this->configArray['main'][$item] = $value;
            $dumper = new Dumper();
            $yaml = $dumper->dump($this->configArray);
            file_put_contents($this->configFile, $yaml);
        } catch (Exception $ex) {
            throw $ex->getMessage();
        }
    }
    
}