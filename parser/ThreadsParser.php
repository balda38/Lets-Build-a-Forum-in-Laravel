<?php

namespace Parser;

use Parser\ProgressBar;

use App\Thread;

/**
 * The ThreadsParser provides tool for unloading data about
 * forum's threads and count of replies to them into file.
 */
class ThreadsParser
{
    /**
     * @var ProgressBar
     */
    private $progressBar;
    private $pathToFile;

    /**
     * @param string $pathToFile Path to file for unloading data.
     */
    public function __construct(string $pathToFile = __DIR__.'/threads.txt')
    {
        if (!$pathToFile)
            $this->pathToFile = __DIR__.'/threads.txt';
        else
            $this->pathToFile = $pathToFile;
    }

    /**
     * Parse data about forum's threads and count of replies to them
     * into file.
     */
    public function parse()
    {
        $threadsCount = Thread::all()->count();
        $this->progressBar = new ProgressBar($threadsCount, 'Threads Parser');

        $threads = Thread::chunk(50, function ($threads) {
            foreach ($threads as $thread) {
                $this->putRowIntoFile($thread->title, $thread->replies_count);
                $this->progressBar->makeStep();
            }
        });

        $this->progressBar->close();
    }

    /**
     * Put row with data about thread into file.
     * 
     * @param string $threadTitle Title of thread
     * @param string $threadRepliesCount Replies count of thread
     */
    private function putRowIntoFile(string $threadTitle, string $threadRepliesCount)
    {
        file_put_contents($this->pathToFile, "$threadTitle - $threadRepliesCount\r\n", FILE_APPEND);
    }
}