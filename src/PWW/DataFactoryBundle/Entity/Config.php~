<?php

namespace PWW\DataFactoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Config
 * @ExclusionPolicy("all")
 */
class Config
{
    /**
     * @var string
     * @Expose
     */
    private $version;

    /**
     * @var string
     */
    private $json;

    /**
     * @var string
     */
    private $jsonPretty;

    /**
     * @var string
     */
    private $hashRef;

    /**
     * @var integer
     * @Expose
     */
    private $id;


    /**
     * Set version
     *
     * @param string $version
     * @return Config
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set json
     *
     * @param string $json
     * @return Config
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * Get json
     *
     * @return string 
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * Set jsonPretty
     *
     * @param string $jsonPretty
     * @return Config
     */
    public function setJsonPretty($jsonPretty)
    {
        $this->jsonPretty = $jsonPretty;

        return $this;
    }

    /**
     * Get jsonPretty
     *
     * @return string 
     */
    public function getJsonPretty()
    {
        return $this->jsonPretty;
    }

    /**
     * Set hashRef
     *
     * @param string $hashRef
     * @return Config
     */
    public function setHashRef($hashRef)
    {
        $this->hashRef = $hashRef;

        return $this;
    }

    /**
     * Get hashRef
     *
     * @return string 
     */
    public function getHashRef()
    {
        return $this->hashRef;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
