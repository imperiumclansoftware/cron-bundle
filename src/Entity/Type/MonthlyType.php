<?php

namespace ICS\CronBundle\Entity\Type;

use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(schema="cron")
 */
class MonthlyType extends AbstractCronType
{
    /**
     * MonthDays to execute task.
     *
     * @ORM\Column(type="json")
     */
    protected $monthDays;

    protected $clearName = 'Monthly';

    public function __construct($timezone = null)
    {
        parent::__contruct($timezone);

        $this->monthDays = [];
    }

    public function getNextExecution(): DateTime
    {
        if (null == $this->timezone) {
            $this->timezone = new DateTimeZone('Europe/Paris');
        }
        $now = new DateTime();
        $now->setTimezone($this->timezone);
        $now->setTime($this->hour, $this->minute);

        foreach ($this->monthDays as $day) {
            $now->setDate((int) $now->format('Y'), (int) $now->format('m'), $day);

            if ($this->verifTime($now)) {
                return $now;
            }
        }

        $now->add(new DateInterval('P1M'));

        return $now;
    }

    /**
     * Get the value of monthDays.
     */
    public function getMonthDays()
    {
        return $this->monthDays;
    }

    public function addMonthDays($day)
    {
        if (!in_array($day, $this->monthDays)) {
            $this->monthDays[] = $day;
        }

        return $this;
    }

    /**
     * Set monthDays to execute task.
     *
     * @return self
     */
    public function setMonthDays($monthDays)
    {
        $this->monthDays = $monthDays;

        return $this;
    }
}
