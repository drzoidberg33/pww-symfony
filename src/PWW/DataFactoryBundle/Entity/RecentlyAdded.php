<?php

namespace PWW\DataFactoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecentlyAdded
 */
class RecentlyAdded
{
    /**
     * @var string
     */
    private $debug;

    /**
     * @var integer
     */
    private $file;

    /**
     * @var integer
     */
    private $twitter;

    /**
     * @var integer
     */
    private $growl;

    /**
     * @var integer
     */
    private $prowl;

    /**
     * @var integer
     */
    private $gntp;

    /**
     * @var integer
     */
    private $email;

    /**
     * @var integer
     */
    private $pushover;

    /**
     * @var integer
     */
    private $boxcar;

    /**
     * @var integer
     */
    private $boxcarV2;

    /**
     * @var integer
     */
    private $pushbullet;

    /**
     * @var integer
     */
    private $time;

    /**
     * @var string
     */
    private $itemId;


    /**
     * Set debug
     *
     * @param string $debug
     * @return RecentlyAdded
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * Get debug
     *
     * @return string 
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Set file
     *
     * @param integer $file
     * @return RecentlyAdded
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return integer 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set twitter
     *
     * @param integer $twitter
     * @return RecentlyAdded
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return integer 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set growl
     *
     * @param integer $growl
     * @return RecentlyAdded
     */
    public function setGrowl($growl)
    {
        $this->growl = $growl;

        return $this;
    }

    /**
     * Get growl
     *
     * @return integer 
     */
    public function getGrowl()
    {
        return $this->growl;
    }

    /**
     * Set prowl
     *
     * @param integer $prowl
     * @return RecentlyAdded
     */
    public function setProwl($prowl)
    {
        $this->prowl = $prowl;

        return $this;
    }

    /**
     * Get prowl
     *
     * @return integer 
     */
    public function getProwl()
    {
        return $this->prowl;
    }

    /**
     * Set gntp
     *
     * @param integer $gntp
     * @return RecentlyAdded
     */
    public function setGntp($gntp)
    {
        $this->gntp = $gntp;

        return $this;
    }

    /**
     * Get gntp
     *
     * @return integer 
     */
    public function getGntp()
    {
        return $this->gntp;
    }

    /**
     * Set email
     *
     * @param integer $email
     * @return RecentlyAdded
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return integer 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set pushover
     *
     * @param integer $pushover
     * @return RecentlyAdded
     */
    public function setPushover($pushover)
    {
        $this->pushover = $pushover;

        return $this;
    }

    /**
     * Get pushover
     *
     * @return integer 
     */
    public function getPushover()
    {
        return $this->pushover;
    }

    /**
     * Set boxcar
     *
     * @param integer $boxcar
     * @return RecentlyAdded
     */
    public function setBoxcar($boxcar)
    {
        $this->boxcar = $boxcar;

        return $this;
    }

    /**
     * Get boxcar
     *
     * @return integer 
     */
    public function getBoxcar()
    {
        return $this->boxcar;
    }

    /**
     * Set boxcarV2
     *
     * @param integer $boxcarV2
     * @return RecentlyAdded
     */
    public function setBoxcarV2($boxcarV2)
    {
        $this->boxcarV2 = $boxcarV2;

        return $this;
    }

    /**
     * Get boxcarV2
     *
     * @return integer 
     */
    public function getBoxcarV2()
    {
        return $this->boxcarV2;
    }

    /**
     * Set pushbullet
     *
     * @param integer $pushbullet
     * @return RecentlyAdded
     */
    public function setPushbullet($pushbullet)
    {
        $this->pushbullet = $pushbullet;

        return $this;
    }

    /**
     * Get pushbullet
     *
     * @return integer 
     */
    public function getPushbullet()
    {
        return $this->pushbullet;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return RecentlyAdded
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Get itemId
     *
     * @return string 
     */
    public function getItemId()
    {
        return $this->itemId;
    }
}
