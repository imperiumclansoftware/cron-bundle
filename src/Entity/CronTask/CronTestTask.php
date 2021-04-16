<?php

namespace ICS\CronBundle\Entity\CronTask;

use Doctrine\ORM\Mapping as ORM;
use ICS\CronBundle\Entity\AbstractCronTask;
use ICS\CronBundle\Entity\Report\CronReport;
use ICS\CronBundle\Entity\Report\CronReportLine;

/**
 * @ORM\Entity
 * @ORM\Table(schema="cron")
 */
class CronTestTask extends AbstractCronTask
{
    // Override technical properties
    protected $technicalName = 'Cron Test Task';
    protected $technicalDescription = 'Task for test bundle cron';

    /**
     * Undocumented variable.
     *
     * @var [type]
     * @ORM\Column(type="string")
     */
    private $environement = 'prod';

    /**
     * Excecution of cache clear.
     */
    public function execute(): void
    {
        $report = new CronReport();

        $report->addLine(new CronReportLine(CronReportLine::TYPE_INFO, 'Start test.'));
        \sleep(3);
        $report->addLine(new CronReportLine(CronReportLine::TYPE_SUCCESS, 'Execution Ok.'));
        \sleep(3);
        $report->addLine(new CronReportLine(CronReportLine::TYPE_INFO, 'test ended.'));

        $report->close();
        $report->setCronTask($this);
        $this->reports->add($report);
    }
}
