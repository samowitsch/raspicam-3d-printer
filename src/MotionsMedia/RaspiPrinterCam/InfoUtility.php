<?php

namespace MotionsMedia\RaspiPrinterCam;


class InfoUtility
{

    public static function getCpuInfo(): array
    {
        return [
            'temp' => shell_exec('sudo vcgencmd measure_temp'),
            'clock' => shell_exec('sudo vcgencmd measure_clock arm'),

        ];
    }

    public static function getDiskUsage(): string
    {
        $diskUsage = '';

        $file = $_SERVER['DOCUMENT_ROOT'] . '/usage.txt';

        if (file_exists($file)) {
            $diskUsage = file_get_contents($file);
        } else {
            file_put_contents($file, '');
        }

        return $diskUsage;
    }


    public static function getCameraInfo(): string
    {
        return shell_exec('rpicam-still --list-cameras');
    }
}
