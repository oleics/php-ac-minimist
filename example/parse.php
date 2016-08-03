#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Ac\Minimist;
$opts = Minimist::parse($argv);
var_dump($opts);
