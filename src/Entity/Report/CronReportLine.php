<?php

namespace ICS\CronBundle\Entity\Report;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(schema="cron")
 */
class CronReportLine
{
    const TYPE_INFO = 'info';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'danger';
    const TYPE_SUCCESS = 'success';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLine;
    /**
     * @ORM\ManyToOne(targetEntity="ICS\CronBundle\Entity\Report\CronReport", inversedBy="lines", cascade={"persist","remove"})
     */
    private $cronReport;
    /**
     * @ORM\Column(type="string")
     */
    private $type;
    /**
     * @ORM\Column(type="text")
     */
    private $text;

    public function __construct(string $type, string $text)
    {
        $this->dateLine = new DateTime();
        $this->type = $type;
        $this->text = $text;
    }

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of dateLine.
     */
    public function getDateLine()
    {
        return $this->dateLine;
    }

    /**
     * Set the value of dateLine.
     *
     * @return self
     */
    public function setDateLine($dateLine)
    {
        $this->dateLine = $dateLine;

        return $this;
    }

    /**
     * Get the value of cronReport.
     */
    public function getCronReport()
    {
        return $this->cronReport;
    }

    /**
     * Set the value of cronReport.
     *
     * @return self
     */
    public function setCronReport($cronReport)
    {
        $this->cronReport = $cronReport;

        return $this;
    }

    /**
     * Get the value of type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type.
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of text.
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of text.
     *
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }
}
