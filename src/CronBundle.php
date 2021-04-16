<?php

namespace ICS\CronBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CronBundle extends Bundle
{
    public function build(ContainerBuilder $builder)
    {
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
