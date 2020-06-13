<?php

use Illuminate\Foundation\Inspiring;

use Parser\ThreadsParser;
use Util\PathToFileValidator;

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

Artisan::command('parse-threads {pathToFile?}', function ($pathToFile = '') {
    if ($pathToFile) {
        try {
            if (!PathToFileValidator::validate($pathToFile, 'txt'))
                throw new Exception('Путь до файла выгрузки введен некорректно');
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

    $parser = new ThreadsParser($pathToFile);
    $parser->parse();
})->describe('Parse all forum\'s threads with count of replies to them');
