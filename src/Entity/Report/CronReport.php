<?php

namespace ICS\CronBundle\Entity\Report;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ICS\CronBundle\Entity\AbstractCronTask;

/**
 * @ORM\Entity
 * @ORM\Table(schema="cron")
 */
class CronReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStart;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\ManyToOne(targetEntity="ICS\CronBundle\Entity\AbstractCronTask",inversedBy="reports", cascade={"persist","remove"})
     */
    private $cronTask;
    /**
     * @ORM\OneToMany(targetEntity="ICS\CronBundle\Entity\Report\CronReportLine",mappedBy="cronReport", cascade={"persist","remove"})
     * @ORM\OrderBy({"dateLine" = "ASC"})
     */
    private $lines;

    public function __construct()
    {
        $this->dateStart = new DateTime();
        $this->lines = new ArrayCollection();
    }

    public function addLine(CronReportLine $line): CronReport
    {
        if (!$this->lines->contains($line)) {
            $this->lines->add($line);
            $line->setCronReport($this);
        }

        return $this;
    }

    public function close(): void
    {
        $this->dateEnd = new DateTime();
    }

    /**
     * Get the value of id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of dateStart.
     */
    public function getDateStart(): DateTime
    {
        return $this->dateStart;
    }

    /**
     * Set the value of dateStart.
     *
     * @return self
     */
    public function setDateStart(DateTime $dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get the value of dateEnd.
     */
    public function getDateEnd(): DateTime
    {
        return $this->dateEnd;
    }

    /**
     * Set the value of dateEnd.
     *
     * @return self
     */
    public function setDateEnd(DateTime $dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get the value of cronTask.
     */
    public function getCronTask(): AbstractCronTask
    {
        return $this->cronTask;
    }

    /**
     * Set the value of cronTask.
     *
     * @return self
     */
    public function setCronTask(AbstractCronTask $cronTask)
    {
        $this->cronTask = $cronTask;

        return $this;
    }

    public function getDuration()
    {
        return $this->dateStart->diff($this->dateEnd);
    }

    /**
     * Get the value of lines.
     */
    public function getLines()
    {
        return $this->lines;
    }

    public function getGlobalResult()
    {
        $result = CronReportLine::TYPE_SUCCESS;

        foreach ($this->lines as $line) {
            if (CronReportLine::TYPE_ERROR == $line->getType()) {
                $result = CronReportLine::TYPE_ERROR;
            } elseif (CronReportLine::TYPE_ERROR != $result && CronReportLine::TYPE_WARNING == $line->getType()) {
                $result = CronReportLine::TYPE_WARNING;
            }
        }

        return $result;
    }
}
