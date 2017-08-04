#!/usr/bin/env php

<?php
// app.php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application("ConsoleApplication");

// ... register commands

$application->add(new ReadCsvCommand);

$application->run();
