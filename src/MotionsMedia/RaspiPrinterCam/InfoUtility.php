<?php

namespace MotionsMedia\RaspiPrinterCam;


class InfoUtility
{

    public static function getCpuInfo()
    {
        return [
            'temp' => shell_exec('sudo vcgencmd measure_temp'),
            'clock' => shell_exec('sudo vcgencmd measure_clock arm'),

        ];
    }

    public static function getDiskUsage()
    {
        $diskUsage = '';
        $diskUsageItems = [];

        $file = $_SERVER['DOCUMENT_ROOT'] . '/usage.txt';

        if (file_exists($file)) {
            $diskUsage = file_get_contents($file);
            $diskUsageItems = array_filter(explode(chr(10), $diskUsage));
        } else {
            file_put_contents($file, '');
        }

        return [
            'diskUsage' => $diskUsage,
            'diskUsageItems' => $diskUsageItems,
        ];
    }
}
