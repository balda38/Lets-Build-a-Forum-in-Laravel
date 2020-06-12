<?php

namespace Parser;

use Parser\ProgressBar;

class IndexParser
{
    private $pathToFile;

    /**
     * @param string $pathToFile Path to file for unloading data
     */
    public function __construct(string $pathToFile)
    {
        $this->pathToFile = $pathToFile;
    }

    public function parse()
    {
        $counter = 100;
        $progressBar = new ProgressBar($counter, 'New Parser');
        while ($counter > 0) {
            $counter--;
            
            $progressBar->makeStep();
        }
        $progressBar->close();
    }
}