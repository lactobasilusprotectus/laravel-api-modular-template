<?php

namespace App\Application\Console\CustomCommands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeModel extends ModelMakeCommand
{
    protected function getArguments(): array
    {
        $argument = parent::getArguments();

        $argument[] = [
            ['domain', InputArgument::REQUIRED, 'The name of domain'],
        ];

        return $argument;
    }
}
