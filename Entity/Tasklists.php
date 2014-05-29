<?php

namespace Acme\TODOListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tasklists
 */
class Tasklists
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $idList;


    /**
     * Set name
     *
     * @param string $name
     * @return Tasklists
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get idList
     *
     * @return integer 
     */
    public function getIdList()
    {
        return $this->idList;
    }
}
