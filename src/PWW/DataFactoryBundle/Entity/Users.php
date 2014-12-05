<?php

namespace PWW\DataFactoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 */
class Users
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $friendlyname;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set friendlyname
     *
     * @param string $friendlyname
     * @return Users
     */
    public function setFriendlyname($friendlyname)
    {
        $this->friendlyname = $friendlyname;

        return $this;
    }

    /**
     * Get friendlyname
     *
     * @return string 
     */
    public function getFriendlyname()
    {
        return $this->friendlyname;
    }
}
