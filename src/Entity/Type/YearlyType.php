<?php

namespace ICS\CronBundle\Entity\Type;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(schema="cron")
 */
class YearlyType extends MonthlyType
{
    const MONTH_JANUARY = 1;
    const MONTH_FEBUARY = 2;
    const MONTH_MARCH = 3;
    const MONTH_APRIL = 4;
    const MONTH_MAY = 5;
    const MONTH_JUNE = 6;
    const MONTH_JULY = 7;
    const MONTH_AUGUST = 8;
    const MONTH_SEPTEMBER = 9;
    const MONTH_OCTOBER = 10;
    const MONTH_NOVEMBER = 11;
    const MONTH_DECEMBER = 12;

    /**
     * MonthDays to execute task.
     *
     * @ORM\Column(type="json")
     */
    protected $months;

    protected $clearName = 'Yearly';

    public function __construct(string $timezone = null)
    {
        parent::__contruct($timezone);
    }

    public function getNextExecution(): DateTime
    {
        if (null == $this->timezone) {
            $this->timezone = new DateTimeZone('Europe/Paris');
        }
        $now = new DateTime();

        return $this->getPotentialDate((int) $now->format('Y'));
    }

    private function getPotentialDate(int $year): DateTime
    {
        //TODO: Verification du système
        $potentialDate = [];
        foreach ($this->getMonthDays() as $day) {
            foreach ($this->months as $month) {
                $dt = $year.'/'.$month.'/'.$day.' '.$this->hour.':'.$this->minute;
                $potentialDate[] = DateTime::createFromFormat('Y/m/d H:i', $dt);
            }
        }

        foreach ($potentialDate as $finalDate) {
            if (is_a($finalDate,DateTime::class) && $this->verifTime($finalDate)) {
                return $finalDate;
            }
        }

        $potentialDate = $this->getPotentialDate($year + 1);

        return $potentialDate;
    }

    /**
     * Get monthDays to execute task.
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * Set monthDays to execute task.
     *
     * @return self
     */
    public function setMonths($months)
    {
        $this->months = $months;

        return $this;
    }
}
