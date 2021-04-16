<?php

namespace ICS\CronBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ICS\CronBundle\Entity\AbstractCronTask;
use ICS\CronBundle\Entity\Report\CronReport;
use ICS\CronBundle\Entity\Type\AbstractCronType;
use ICS\CronBundle\Entity\Type\DailyType;
use ICS\CronBundle\Entity\Type\MonthlyType;
use ICS\CronBundle\Entity\Type\WeeklyType;
use ICS\CronBundle\Entity\Type\YearlyType;
use ICS\CronBundle\Entity\Widgets\ReportListWidget;
use ICS\CronBundle\Entity\Widgets\TaskListWidget;
use ICS\CronBundle\Form\Type\DailyType as TypeDailyType;
use ICS\CronBundle\Form\Type\MonthlyType as TypeMonthlyType;
use ICS\CronBundle\Form\Type\WeeklyType as TypeWeeklyType;
use ICS\CronBundle\Form\Type\YearlyType as TypeYearlyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Extension\CoreExtension;

class CronController extends AbstractController
{
    private $config;
    private $timezone;

    /**
     * @Route("/", name="ics-cron-homepage")
     */
    public function index()
    {
        $tasks = $this->getDoctrine()->getRepository(AbstractCronTask::class)->findAll();
        return $this->render('@Cron/index.html.twig', [
        ]);
    }

    /**
     * @Route("/reports", name="ics-cron-reports")
     */
    public function reports()
    {
        $reports = $this->getDoctrine()->getRepository(CronReport::class)->findAll();

        return $this->render('@Cron/reports/reports.html.twig', [
            'reports' => $reports,
        ]);
    }

    /**
     * @Route("/report/{id}", name="ics-cron-report")
     */
    public function reportShow($id)
    {
        $report = $this->getDoctrine()->getRepository(CronReport::class)->find($id);

        return $this->render('@Cron/reports/reportShow.html.twig', [
            'report' => $report,
        ]);
    }

    /**
     * @Route("/task/add", name="ics-cron-add")
     */
    public function addTask(Request $request, ContainerInterface $container, EntityManagerInterface $doctrine, KernelInterface $kernel, Environment $twig)
    {
        $em = $this->getDoctrine()->getManager();
        $taskType = $request->get('taskType', null);
        $formType = $request->get('taskFormType', null);

        if ($crontask = $request->get('cron_task')) {
            $taskType = $crontask['taskType'];
            $formType = $crontask['taskFormType'];
        }

        if (null != $taskType && null != $formType) {
            $task = new $taskType($container, $doctrine, $kernel);
            $cronType = new $formType();
            $task->setCronType($cronType);

            $form = $this->createForm($task->getFormType(), $task, [
                'cronTypeClass' => $formType,
                'cronTask' => $taskType,
                'cronTypeForm' => $this->getFormFromType($formType),
            ]);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid() && $form->get('final')) {
                $task = $form->getData();
                $em->persist($task);
                $em->flush();

                return $this->redirectToRoute('ics-cron-homepage');
            }

            return $this->render('@Cron/task/add.html.twig', [
                'form' => $form->createView(),
                'cronType' => $cronType,
            ]);
        } else {
            $taskList = [];
            $taskTypeList = [];
            $classList = $em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();

            foreach ($classList as $class) {
                if (is_subclass_of($class, AbstractCronType::class)) {
                    $taskTypeList[] = $class;
                }
                if (is_subclass_of($class, AbstractCronTask::class)) {
                    $taskList[] = $class;
                }
            }

            return $this->render('@Cron/task/selectType.html.twig', [
                'taskList' => $taskList,
                'taskTypeList' => $taskTypeList,
            ]);
        }
    }

    /**
     * @Route("/task/get/list", name="ics-cron-task-list")
     */
    public function getTaskList(Request $request)
    {
        $widgetId = $request->get('widgetId', null);

        if (null == $widgetId) {
            return $this->createNotFoundException('This widget did not exist');
        }

        $tasks = $this->getDoctrine()->getRepository(AbstractCronTask::class)->findAll();
        $widget = $this->getDoctrine()->getRepository(TaskListWidget::class)->find($widgetId);

        return $this->render('@Cron/widgets/taskListResult.html.twig', [
            'tasks' => $tasks,
            'widget' => $widget,
        ]);
    }

    /**
     * @Route("/report/get/list", name="ics-cron-report-list")
     */
    public function getReportList(Request $request)
    {
        $widgetId = $request->get('widgetId', null);

        if (null == $widgetId) {
            return $this->createNotFoundException('This widget did not exist');
        }

        $reports = $this->getDoctrine()->getRepository(CronReport::class)->findAll();
        $widget = $this->getDoctrine()->getRepository(ReportListWidget::class)->find($widgetId);

        return $this->render('@Cron/widgets/reportListResult.html.twig', [
            'reports' => $reports,
            'widget' => $widget,
        ]);
    }

    /**
     * @Route("/task/show/{id}", name="ics-cron-task-show")
     */
    public function showTask($id)
    {
        $task = $this->getDoctrine()->getRepository(AbstractCronTask::class)->find($id);

        return $this->render('@Cron/task/show.html.twig', [
            'task' => $task,
        ]);
    }

    private function getFormFromType($type)
    {
        switch ($type) {
            case DailyType::class: return TypeDailyType::class;
            case WeeklyType::class: return TypeWeeklyType::class;
            case MonthlyType::class: return TypeMonthlyType::class;
            case YearlyType::class: return TypeYearlyType::class;
        }
    }

    public function loadConfig(Environment $twig)
    {
        try {
            $this->config = $this->getParameter('cron');
            $this->timezone = $this->config['timezone'];
        } catch (Exception $ex) {
            $this->timezone = 'Europe/Paris';
        }

        $twigExtension = $twig->getExtension(CoreExtension::class);
        $twigExtension->setTimezone($this->timezone);
    }

    //TODO: Manage remove Task
    //TODO: Manage Immediate execution
}
