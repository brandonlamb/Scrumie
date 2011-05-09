#!/usr/bin/env php
<?php
require 'Clix/Clix.php';

$cli = new Clix(dirname(__FILE__).DIRECTORY_SEPARATOR.'task');

if(count($argv) == 0)
{
    readline_completion_function('readlineCompletionTask');
    $taskName = selectTaskName();
    $cli->showTaskHelp($taskName);
    readline_completion_function('readlineCompletionArguments');
    $arguments = preg_split('/[ ]+/', readline("\nArguments: "));
    $cli->runTask($taskName, $arguments);
}
else {
    $cli->run();
}


function selectTaskName() {
    if(!$taskName = readline('Task name [use TAB to suggest]: '))
        return selectTaskName();
    else
        return $taskName;
}

function readlineCompletionTask($input, $index)
{
    global $cli;
    return $cli->retriveTaskList();
}

function readlineCompletionArguments($input, $index) {
    global $cli;
    global $taskName;
    return $cli->retriveTaskArguments($taskName);
}
