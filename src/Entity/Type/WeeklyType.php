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
class WeeklyType extends AbstractCronType
{
    const DAY_MONDAY = 1;
    const DAY_THURSDAY = 2;
    const DAY_WEDNESDAY = 3;
    const DAY_THUESDAY = 4;
    const DAY_FRIDAY = 5;
    const DAY_SATURDAY = 6;
    const DAY_SUNDAY = 7;

    /**
     * WeekDays to execute task.
     *
     * @ORM\Column(type="json")
     */
    private $weekDays;

    protected $clearName = 'Weekly';

    public function __construct(string $timezone = null)
    {
        parent::__contruct($timezone);

        $this->weekDays = [];
    }

    public function getNextExecution(): DateTime
    {
        if (null == $this->timezone) {
            $this->timezone = new DateTimeZone('Europe/Paris');
        }

        $now = new DateTime();
        $now->setTimezone($this->timezone);
        $now->setTime($this->hour, $this->minute);

        // Vérification des 7 prochains jours
        $i = 0;
        while (!in_array((int) $now->format('N'), $this->weekDays) && $i <= 7) {
            $now = $now->add(new DateInterval('P1D'));
            ++$i;
        }

        // Vérification par rapport à l'heure
        if ($this->verifTime($now)) {
            return $now;
        } else {
            $now->add(new DateInterval('P7D'));
        }

        return $now;
    }

    public function addWeekDay($day): void
    {
        if (!in_array((int) $day, $this->weekDays)) {
            $this->weekDays[(int) $day] = (int) $day;
        }
    }

    public function removeWeekDay($day): void
    {
        if (in_array((int) $day, $this->weekDays)) {
            unset($this->weekDays[(int) $day]);
        }
    }

    /**
     * Get the value of weekDays.
     */
    public function getWeekDays()
    {
        return $this->weekDays;
    }
}
