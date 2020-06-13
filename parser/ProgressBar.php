<?php

namespace Parser;

/**
 * The ProgreeBar show progress of parsing process.
 */
class ProgressBar
{
    private const PROGRESS_SIZE = 50;

    private $total;
    private $step = 0;
    private $startTime;
    private $avgTime = null;

    /**
     * @param int $total Count of elements in parsing process
     * @param string $parserName Name of parser, that shows in CLI
     */
    public function __construct(int $total, string $parserName)
    {
        $this->total = $total;
        $this->startTime = time();
        print_r("Parser '$parserName' start...\n");
    }

    /**
     * Performs the following iteration of progress bar drawing.
     */
    public function makeStep()
    {
        ++$this->step;
        $this->draw();
    }

    /**
     * Getting time remaining until end of parcing process.
     *
     * @return string Time in minutes and seconds remaining until
     * end of parcing process
     */
    private function countTime()
    {
        $now = time();
        $passTime = $now - $this->startTime;
        $endTime = round($passTime * ($this->total / $this->step));
        $leftTime = $endTime - $passTime;

        if ($this->avgTime)
            $leftTime = ($this->avgTime + $leftTime) / 2;

        $this->avgTime = $leftTime;

        $leftTime = "Time left: ~ "
            .date('i', $leftTime)
            ." minutes "
            .date('s', $leftTime)
            ." seconds";

        return $leftTime;
    }

    /**
     * Draw progress bar in CLI with current parcing process parameters.
     */
    private function draw()
    {
        $percents = round((($this->step / $this->total) * 100), 2);

        $complete = round(($this->step / $this->total) * self::PROGRESS_SIZE);
        $notComplete = self::PROGRESS_SIZE - $complete;

        $completeProgress = '';
        $notCompleteProgress = '';

        while ($complete > 0) {
            --$complete;
            $completeProgress.='█';
        }

        while ($notComplete > 0) {
            --$notComplete;
            $notCompleteProgress.=' ';
        }

        $usageMemory = round((float)(memory_get_usage() / 1024 / 1024));

        print_r("\r["
            .$completeProgress.$notCompleteProgress
            ."] "
            .$percents
            ."% "
            .$this->countTime()
            ." / "
            ."Size of used memory: "
            .$usageMemory
            ."Mb");
    }

    /**
     * Getting finally parameters of parsing process in its end.
     *
     * @return string Parameters of parsing process: time spent and
     * peak of usage memory
     */
    private function getFinalParams()
    {
        $diffTime = time() - $this->startTime;
        $minutes = date('i', $diffTime);
        $seconds = date('s', $diffTime);
        $memoryUsagePeak = round((float)(memory_get_peak_usage() / 1024 / 1024));
        return "$minutes minutes "
            ."$seconds seconds have passed "
            ."/ Peak size of used memory: $memoryUsagePeak Mb";
    }

    /**
     * Show in CLI status of parsing process in its end: errors, if they are
     * or OK status with finally parameters.
     *
     * @param array $errors Errors occurred in parsing process
     */
    public function close(array $errors = [])
    {
        $count = self::PROGRESS_SIZE;
        $progress = '';

        while ($count > 0) {
            --$count;
            $progress .= '█';
        }

        $finalParams = $this->getFinalParams();

        if ($errors) {
            print_r("\r[$progress] ERR $finalParams    \n");
            print_r($errors);
        } else {
            print_r("\r[$progress] OK $finalParams       \n");
        }
    }
}