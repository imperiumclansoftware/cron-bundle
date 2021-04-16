<?php

namespace ICS\CronBundle\Command;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ICS\CronBundle\Entity\AbstractCronTask;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class CronExecuteCommand extends Command
{
    protected static $defaultName = 'Cron:Execute';

    private $doctrine;
    private $container;
    private $kernel;
    private $timezone;

    public function __construct(EntityManagerInterface $doctrine, ContainerInterface $container, KernelInterface $kernel)
    {
        parent::__construct();

        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->kernel = $kernel;

        $this->config = $this->container->getParameter('cron');
        $this->timezone = new DateTimeZone($this->config['timezone']);
    }

    protected function configure()
    {
        $this->setName('Cron:Execute')
                ->setDescription('Execute configured cron tasks.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);


        $io->title('Cron Task Execution');


        $taskToExecute = [];

        try {
            $io->text('Loading task');
            $tasks = $this->doctrine->getRepository(AbstractCronTask::class)->findAll();

            $now = new DateTime();
            $now->setTimezone($this->timezone);
            $comparaisonFormat = 'dmYHi';
            $io->text('Task verification');

            $io->progressStart(count($tasks));


            foreach ($tasks as $task) {
                $io->progressAdvance();
                // $io->text($task->getName().':'.$task->getNextExecution()->format($comparaisonFormat).'='.$now->format($comparaisonFormat));
                if ($task->getNextExecution()->format($comparaisonFormat) == $now->format($comparaisonFormat)) {
                    $taskToExecute[] = $task;
                }
            }
            $io->progressFinish();
            // if(count($taskToExecute) > 0)
            // {

                $io->text('Task execution');
                $io->definitionList("Task to execute",$taskToExecute);

                $io->progressStart(count($taskToExecute));
                foreach ($taskToExecute as $task) {

                    $task->Initialize($this->container, $this->doctrine, $this->kernel);

                    $task->execute();
                    $io->progressAdvance();

                    $this->doctrine->persist($task);
                }
                $io->progressFinish();
                $this->doctrine->flush();
            // }
        } catch (Exception $ex) {
            $io->error($ex->getMessage());
        }

        return 0;
    }
}
