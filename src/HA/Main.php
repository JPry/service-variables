<?php

namespace HA;

use HA\Command\ConstructCommand;
use Symfony\Component\Console\Application;

class Main extends Application
{
    public function __construct()
    {
        parent::__construct('ha-services', '0.1');
    }

    /**
     * @return array
     */
    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            array(
                new ConstructCommand(),
            )
        );
    }
}
