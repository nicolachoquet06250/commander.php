<?php

require_once __DIR__.'/commanderP/autoload.php';

$program = Program::version('0.1.2')
	->option('Une description', '-p [p]', '--port [p]')
	->option('Une seconde description', '-h [h]', '--host [h]')
	->parse(...$argv);

if($program->exists('p', 'int')) {
	var_dump($program->p);
}
if($program->exists('h', 'string')) {
	var_dump($program->h);
}
