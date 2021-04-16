<?php

namespace ICS\CronBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(schema="cron")
 */
class CronGroup
{
    /**
     * Group identifier.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * GroupName.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name = 'New Group';

    /**
     * Group description.
     *
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    /**
     * @ORM\OneToMany(targetEntity="AbstractCronTask", mappedBy="group", cascade={"persist","remove"})
     */
    private $tasks;

    /**
     * Get groupName.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set groupName.
     *
     * @param string $name GroupName.
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get group description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set group description.
     *
     * @param string $description Group description.
     *
     * @return self
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of tasks.
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Get group identifier.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
