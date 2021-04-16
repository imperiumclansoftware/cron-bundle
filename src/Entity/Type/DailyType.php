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
class DailyType extends AbstractCronType
{
    protected $clearName = 'Daily';

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
        $now->setTimezone($this->timezone);
        $now->setTime($this->hour, $this->minute);

        if ($this->verifTime($now)) {
            return $now;
        } else {
            $now->add(new DateInterval('P1D'));
        }

        return $now;
    }
}
