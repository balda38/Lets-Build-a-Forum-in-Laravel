<?php

use Illuminate\Foundation\Inspiring;

use Parser\IndexParser;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('parse-threads', function () {
    $pathToFile = readline('Путь до файла выгрузки: ');
    $parser = new IndexParser($pathToFile);
    $parser->parse();
});
