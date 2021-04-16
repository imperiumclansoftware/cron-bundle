<?php

namespace ICS\CronBundle\Entity\Type;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(schema="cron")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\HasLifecycleCallbacks()(
 */
abstract class AbstractCronType
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
     * Timezone for Calcul.
     *
     * @var DateTimeZone
     */
    protected $timezone;

    /**
     * Hour to execute task.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $hour = '01';
    /**
     * Minute to execute task.
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $minute = '00';

    protected $clearName = 'Cron type';

    protected function __contruct(string $timezone = null)
    {
        if (null == $timezone) {
            $this->timezone = new DateTimeZone('Europe/Paris');
        } else {
            $this->timezone = new DateTimeZone($timezone);
        }
    }

    public function getNextExecution(): DateTime
    {
        return new DateTime();
    }

    protected function verifTime(DateTime $testDate)
    {
        $now = new DateTime();
        $now->setTimezone($this->timezone);

        if ($now < $testDate) {
            return true;
        }

        return false;
    }

    /**
     * Get the value of hour.
     */
    public function getHour(): string
    {
        return $this->hour;
    }

    /**
     * Set the value of hour.
     *
     * @return self
     */
    public function setHour(string $hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get the value of minute.
     */
    public function getMinute(): string
    {
        return $this->minute;
    }

    /**
     * Set the value of minute.
     *
     * @return self
     */
    public function setMinute(string $minute)
    {
        $this->minute = $minute;

        return $this;
    }

    /**
     * Get the value of clearName.
     */
    public function getClearName()
    {
        return $this->clearName;
    }

    /**
     * Get timezone for Calcul.
     *
     * @return DateTimeZone
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set timezone for Calcul.
     *
     * @param DateTimeZone $timezone Timezone for Calcul.
     *
     * @return self
     */
    public function setTimezone(DateTimeZone $timezone = null)
    {
        if (null == $timezone) {
            $this->timezone = new DateTimeZone('Europe/Paris');
        } else {
            $this->timezone = $timezone;
        }

        return $this;
    }
}
