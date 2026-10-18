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
