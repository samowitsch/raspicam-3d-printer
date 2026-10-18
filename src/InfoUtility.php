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

        return [
            'diskUsage' => $diskUsage,
            'diskUsageItems' => $diskUsageItems,
        ];
    }
}
