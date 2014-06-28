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
    private $title;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var status
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $due;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Acme\TODOListBundle\Entity\TaskLists
     */
    private $parent;


    /**
     * Set title
     *
     * @param string $title
     * @return Tasks
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
     * Set status
     *
     * @param string $status
     * @return Tasks
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Tasks
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set due
     *
     * @param \DateTime $due
     * @return Tasks
     */
    public function setDue($due)
    {
        $this->due = $due;

        return $this;
    }

    /**
     * Get due
     *
     * @return \DateTime 
     */
    public function getDue()
    {
        return $this->due;
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

    /**
     * Set parent
     *
     * @param \Acme\TODOListBundle\Entity\Tasklists $parent
     * @return Tasks
     */
    public function setParent(\Acme\TODOListBundle\Entity\Tasklists $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Acme\TODOListBundle\Entity\Tasklists 
     */
    public function getParent()
    {
        return $this->parent;
    }
}
