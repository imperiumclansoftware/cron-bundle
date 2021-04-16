<?php

namespace ICS\CronBundle\Entity\Widgets;

use Doctrine\ORM\Mapping as ORM;
use ICS\DashboardBundle\Entity\Widget;
use Twig\Environment;

/**
 * @ORM\Table(schema="dashboard")
 * @ORM\Entity
 */
class TaskListWidget extends Widget
{
    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
        $this->setWidth(6);
        $this->setHeight(6);
        $this->setBgColor("#009f6f");
    }

    public function getJs()
    {
        if (null == $this->twig) {
            return '';
        }

        return $this->twig->render('@Cron/widgets/taskList.js.twig', ['widget' => $this]);
    }

    public function getUI()
    {
        if (null == $this->twig) {
            return '';
        }

        return $this->twig->render('@Cron/widgets/taskList.html.twig', ['widget' => $this]);
    }
}
