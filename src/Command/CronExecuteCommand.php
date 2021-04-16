<?php

namespace ICS\CronBundle\Command;

use DateTime;
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

    public function __construct(EntityManagerInterface $doctrine, ContainerInterface $container, KernelInterface $kernel)
    {
        parent::__construct();

        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->kernel = $kernel;
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
            $comparaisonFormat = 'dmYHI';
            $io->progressStart(count($tasks));
            $io->text('Task verification');

            foreach ($tasks as $task) {
                $io->progressAdvance();
                if ($task->getNextExecution()->format($comparaisonFormat) == $now->format($comparaisonFormat)) {
                    $taskToExecute[] = $task;
                }
            }

            $io->text('Task execution');
            $io->progressStart(count($taskToExecute));
            foreach ($taskToExecute as $task) {
                $io->text('Execute : '.$task->getName());
                $task->Initialize($this->container, $this->doctrine, $this->kernel);
                $task->execute();
                $io->text('Task ID: #'.$task->getId());
                $this->doctrine->persist($task);
            }

            $this->doctrine->flush();
        } catch (Exception $ex) {
            $io->error($ex->getMessage());
        }

        return 0;
    }
}
