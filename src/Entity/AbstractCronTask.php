<?php

namespace ICS\CronBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use ICS\CronBundle\Form\Type\CronTaskType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @ORM\Entity
 * @ORM\Table(schema="cron")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\HasLifecycleCallbacks()(
 */
abstract class AbstractCronTask
{
    /**
     * Undocumented variable.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="ICS\CronBundle\Entity\Type\AbstractCronType",cascade = {"persist","remove"})
     */
    protected $cronType;
    /**
     * @ORM\Column(type="string")
     */
    protected $name = 'New Cron Task';
    /**
     * @ORM\Column(type="string")
     */
    protected $technicalName = 'Cron Task';
    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $description = '';
    /**
     * @ORM\Column(type="text")
     */
    protected $technicalDescription = 'Cron Task Description';
    /**
     * @ORM\ManyToOne(targetEntity="ICS\CronBundle\Entity\CronGroup", inversedBy="tasks", cascade={"persist","remove"})
     */
    protected $group;
    /**
     * @ORM\OneToMany(targetEntity="ICS\CronBundle\Entity\Report\CronReport",mappedBy="cronTask", cascade={"persist","remove"})
     */
    protected $reports;

    private $doctrine;
    private $container;
    private $kernel;

    /**
     * { inherited }.
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $doctrine, KernelInterface $kernel)
    {
        $this->Initialize($container, $doctrine, $kernel);
        $this->reports = new ArrayCollection();
    }

    public function Initialize(ContainerInterface $container, EntityManagerInterface $doctrine, KernelInterface $kernel)
    {
        $this->container = $container;
        $this->kernel = $kernel;
        $this->doctrine = $doctrine;
    }

    /**
     * Undocumented function.
     */
    public function getNextExecution(): DateTime
    {
        return $this->getCronType()->getNextExecution();
    }

    /**
     * Undocumented function.
     */
    public function execute(): void
    {
    }

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of technicalName.
     */
    public function getTechnicalName()
    {
        return $this->technicalName;
    }

    /**
     * Get the value of description.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description.
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the value of name.
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of technicalDescription.
     */
    public function getTechnicalDescription()
    {
        return $this->technicalDescription;
    }

    /**
     * Get the value of group.
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set the value of group.
     *
     * @return self
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get the value of doctrine.
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * Get the value of container.
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get the value of kernel.
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * Get the value of cronType.
     */
    public function getCronType()
    {
        return $this->cronType;
    }

    /**
     * Set the value of cronType.
     *
     * @return self
     */
    public function setCronType($cronType)
    {
        $this->cronType = $cronType;

        return $this;
    }

    /**
     * Get the value of report.
     */
    public function getReports()
    {
        return $this->reports;
    }

    public function getFormType(): string
    {
        return CronTaskType::class;
    }

    public function getLastReport()
    {
        $result = null;
        $lastDate = null;
        foreach ($this->getReports() as $report) {
            if (null == $lastDate || $lastDate < $report->getDateEnd()) {
                $lastDate = $report->getDateEnd();
                $result = $report;
            }
        }

        return $result;
    }

    public function __toString()
    {
        return $this->name.' ('.get_class($this).')';
    }
}
