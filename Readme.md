# Readme

## Description

This is my small raspicam project for my 3d printer. It has some basic features like taking sequence of 
photos with the attached camera and generating a timelap of it.

## Installation

tbd

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

### crontab for scripts

Crontab scripts are running every minute:

```bash
* * * * * /var/www/html/scripts/cam.sh
* * * * * /var/www/html/scripts/timelap.sh
```
see [https://crontab.guru](https://crontab.guru) for more crontab syntax

### Conding

```bash
vendor/bin/php-cs-fixer -vvv fix --dry-run
vendor/bin/php-cs-fixer fix
```
