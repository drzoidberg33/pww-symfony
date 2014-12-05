<?php

namespace PWW\DataFactoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grouped
 * @ORM\Entity(repositoryClass="PWW\DataFactoryBundle\Entity\HelperRepository")
 */
class Grouped
{
    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $platform;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $origTitle;

    /**
     * @var string
     */
    private $origTitleEp;

    /**
     * @var integer
     */
    private $episode;

    /**
     * @var integer
     */
    private $season;

    /**
     * @var string
     */
    private $year;

    /**
     * @var string
     */
    private $rating;

    /**
     * @var string
     */
    private $genre;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var integer
     */
    private $notified;

    /**
     * @var integer
     */
    private $pausedCounter;

    /**
     * @var string
     */
    private $xml;

    /**
     * @var string
     */
    private $ipAddress;

    /**
     * @var integer
     */
    private $ratingkey;

    /**
     * @var integer
     */
    private $parentratingkey;

    /**
     * @var integer
     */
    private $grandparentratingkey;

    /**
     * @var integer
     */
    private $time;

    /**
     * @var integer
     */
    private $stopped;

    /**
     * @var integer
     */
    private $paused;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set sessionId
     *
     * @param string $sessionId
     * @return Grouped
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Grouped
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set platform
     *
     * @param string $platform
     * @return Grouped
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get platform
     *
     * @return string 
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Grouped
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set origTitle
     *
     * @param string $origTitle
     * @return Grouped
     */
    public function setOrigTitle($origTitle)
    {
        $this->origTitle = $origTitle;

        return $this;
    }

    /**
     * Get origTitle
     *
     * @return string 
     */
    public function getOrigTitle()
    {
        return $this->origTitle;
    }

    /**
     * Set origTitleEp
     *
     * @param string $origTitleEp
     * @return Grouped
     */
    public function setOrigTitleEp($origTitleEp)
    {
        $this->origTitleEp = $origTitleEp;

        return $this;
    }

    /**
     * Get origTitleEp
     *
     * @return string 
     */
    public function getOrigTitleEp()
    {
        return $this->origTitleEp;
    }

    /**
     * Set episode
     *
     * @param integer $episode
     * @return Grouped
     */
    public function setEpisode($episode)
    {
        $this->episode = $episode;

        return $this;
    }

    /**
     * Get episode
     *
     * @return integer 
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * Set season
     *
     * @param integer $season
     * @return Grouped
     */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return integer 
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return Grouped
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set rating
     *
     * @param string $rating
     * @return Grouped
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set genre
     *
     * @param string $genre
     * @return Grouped
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string 
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Grouped
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set notified
     *
     * @param integer $notified
     * @return Grouped
     */
    public function setNotified($notified)
    {
        $this->notified = $notified;

        return $this;
    }

    /**
     * Get notified
     *
     * @return integer 
     */
    public function getNotified()
    {
        return $this->notified;
    }

    /**
     * Set pausedCounter
     *
     * @param integer $pausedCounter
     * @return Grouped
     */
    public function setPausedCounter($pausedCounter)
    {
        $this->pausedCounter = $pausedCounter;

        return $this;
    }

    /**
     * Get pausedCounter
     *
     * @return integer 
     */
    public function getPausedCounter()
    {
        return $this->pausedCounter;
    }

    /**
     * Set xml
     *
     * @param string $xml
     * @return Grouped
     */
    public function setXml($xml)
    {
        $this->xml = $xml;

        return $this;
    }

    /**
     * Get xml
     *
     * @return string 
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return Grouped
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set ratingkey
     *
     * @param integer $ratingkey
     * @return Grouped
     */
    public function setRatingkey($ratingkey)
    {
        $this->ratingkey = $ratingkey;

        return $this;
    }

    /**
     * Get ratingkey
     *
     * @return integer 
     */
    public function getRatingkey()
    {
        return $this->ratingkey;
    }

    /**
     * Set parentratingkey
     *
     * @param integer $parentratingkey
     * @return Grouped
     */
    public function setParentratingkey($parentratingkey)
    {
        $this->parentratingkey = $parentratingkey;

        return $this;
    }

    /**
     * Get parentratingkey
     *
     * @return integer 
     */
    public function getParentratingkey()
    {
        return $this->parentratingkey;
    }

    /**
     * Set grandparentratingkey
     *
     * @param integer $grandparentratingkey
     * @return Grouped
     */
    public function setGrandparentratingkey($grandparentratingkey)
    {
        $this->grandparentratingkey = $grandparentratingkey;

        return $this;
    }

    /**
     * Get grandparentratingkey
     *
     * @return integer 
     */
    public function getGrandparentratingkey()
    {
        return $this->grandparentratingkey;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Grouped
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
     * Set stopped
     *
     * @param integer $stopped
     * @return Grouped
     */
    public function setStopped($stopped)
    {
        $this->stopped = $stopped;

        return $this;
    }

    /**
     * Get stopped
     *
     * @return integer 
     */
    public function getStopped()
    {
        return $this->stopped;
    }

    /**
     * Set paused
     *
     * @param integer $paused
     * @return Grouped
     */
    public function setPaused($paused)
    {
        $this->paused = $paused;

        return $this;
    }

    /**
     * Get paused
     *
     * @return integer 
     */
    public function getPaused()
    {
        return $this->paused;
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
