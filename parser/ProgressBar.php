<?php

namespace Parser;

class ProgressBar
{
    private const PROGRESS_SIZE = 50;

    private $total;
    private $parserName;
    private $step = 0;
    private $startTime;
    private $avgTime = null;

    public function __construct(int $total, string $parserName)
    {
        $this->total = $total;
        $this->parserName = $parserName;
        $this->startTime = time();
        print_r("Parser '".$parserName."' start...\n");
    }

    public function makeStep()
    {
        ++$this->step;
        $this->draw();
    }

    private function countTime()
    {
        $now = time();
        $passTime = $now - $this->startTime;
        $endTime = round($passTime * ($this->total / $this->step));
        $leftTime = $endTime - $passTime;

        if ($this->avgTime) {
            $leftTime = ($this->avgTime + $leftTime) / 2;
        }

        $this->avgTime = $leftTime;

        $string = "Time left: ~ "
            .date('i', $leftTime)
            ." minutes "
            .date('s', $leftTime)
            ." seconds";
        return $string;
    }

    private function draw()
    {
        $rawPercents = ($this->step / $this->total) * 100;
        $percents = round($rawPercents, 2);

        $rawComplete = ($this->step / $this->total) * self::PROGRESS_SIZE;
        $complete = round($rawComplete);
        $notComplete = self::PROGRESS_SIZE - $complete;

        $completeStr = '';
        $notCompleteStr = '';

        while ($complete > 0) {
            --$complete;
            $completeStr.='█';
        }

        while ($notComplete > 0) {
            --$notComplete;
            $notCompleteStr.=' ';
        }

        $usageMemory = round((float)(memory_get_usage() / 1024 / 1024));

        print_r("\r["
            .$completeStr.$notCompleteStr
            ."] "
            .$percents
            ."% "
            .$this->countTime()
            ." / "
            ."Size of used memory: "
            .$usageMemory
            ."Mb");
    }

    private function getFinalTime()
    {
        $diffTime = time() - $this->startTime;
        $minutes = date('i', $diffTime);
        $seconds = date('s', $diffTime);
        $memoryUsagePeak = round((float)(memory_get_peak_usage() / 1024 / 1024));
        return $minutes." minutes ". $seconds . " seconds have passed / Peak size of used memory: " . $memoryUsagePeak . "Mb";
    }

    public function close(array $errors = [])
    {
        $count = self::PROGRESS_SIZE;
        $str = '';
        while ($count > 0) {
            --$count;
            $str .= '█';
        }
        $finalTime = $this->getFinalTime();
        if ($errors) {
            print_r("\r[".$str."] ERR ".$finalTime."    \n");
            print_r($errors);
        } else {
            print_r("\r[".$str."] OK ".$finalTime."       \n");
        }
    }
}