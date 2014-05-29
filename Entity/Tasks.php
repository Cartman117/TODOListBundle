<?php

namespace Acme\TODOListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tasks
 */
class Tasks
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $detail;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var integer
     */
    private $idTask;

    /**
     * @var \Acme\TODOListBundle\Entity\Tasklists
     */
    private $idList;


    /**
     * Set name
     *
     * @param string $name
     * @return Tasks
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
     * Set detail
     *
     * @param string $detail
     * @return Tasks
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string 
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Tasks
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Get idTask
     *
     * @return integer 
     */
    public function getIdTask()
    {
        return $this->idTask;
    }

    /**
     * Set idList
     *
     * @param \Acme\TODOListBundle\Entity\Tasklists $idList
     * @return Tasks
     */
    public function setIdList(\Acme\TODOListBundle\Entity\Tasklists $idList = null)
    {
        $this->idList = $idList;

        return $this;
    }

    /**
     * Get idList
     *
     * @return \Acme\TODOListBundle\Entity\Tasklists 
     */
    public function getIdList()
    {
        return $this->idList;
    }
}
