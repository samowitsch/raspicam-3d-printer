# Readme

## Description

This is my small raspicam project for my 3d printer. It has some basic features like taking sequence of 
photos with the attached camera and generating a timelap of it.

## Installation

### Install raspbian lite image (Trixie at time of writing)

### Install additional packages

> sudo apt update  
> sudo apt upgrade  
> sudo apt install apache2 -y  
> sudo apt install php libapache2-mod-php php-mbstring php-curl  
> sudo apt install git  
> sudo apt install ffmpeg  
> sudo apt install imagemagick  
> sudo apt install bc

### Apache2 PHP stuff

To access in PHP ```shell_exec('rpicam-still --list-cameras')``` rpicam-still some group permissions has to be set:

> sudo usermod -aG video www-data  
> sudo systemctl restart apache2

### Install composer

See https://getcomposer.org/download/ how to install composer. 

## Usage

tbd

## Screenshots

tbd

## Misc notes

### sudoers file for php shell_exec

```bash
sudo visudo
```

and add at appropriate position 

```
www-data ALL=NOPASSWD: ALL
```

### Crontab for scripts

Crontab scripts are running every minute:

```bash
#* * * * * /var/www/html/scripts/cam.sh
@reboot /var/www/html/scripts/cam.sh &
* * * * * /var/www/html/scripts/timelap.sh
```
see [https://crontab.guru](https://crontab.guru) for more crontab syntax

### Coding

```bash
vendor/bin/php-cs-fixer -vvv fix --dry-run
vendor/bin/php-cs-fixer fix
```

### Misc

Show running script file: ```ps aux | grep "cam.sh"``` or ```ps aux | grep "timelap.sh"```
Kill running process: e.g. ```kill 1478``` the 1478 you get from ```ps aux``` from above

## Screenshots

### Home/Dashboard

![Home/Dashboard](docs/images/home-dashboard.small.png)

Simple dashboard to view the current image. The right side iframe with MJPEG-Streamer of second camery is currently disabled. Start/stop image recording. Every 30 secs a image will be recorded.

### Image Archive

![Home/Dashboard](docs/images/image-archive.small.png)

The archive of the recorded images.

### Timelap Maker

![Home/Dashboard](docs/images/timelap-maker.small.png)

Select start and end image of image archive to render a mp4 video with ffmpeg.

### Timelap Archive

![Home/Dashboard](docs/images/timelap-archive.small.png)

Overview of the ffmpeg rendered timelaps.
