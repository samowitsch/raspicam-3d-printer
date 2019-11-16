# Readme

## sudoers file for php shell_exec

```
sudo visudo
```

and add at appropriate position 

```
www-data ALL=NOPASSWD: ALL
```

## crontab for scripts

Crontab scripts are running every minute:

```
* * * * * /var/www/html/scripts/cam.sh
* * * * * /var/www/html/scripts/timelap.sh
```
see [https://crontab.guru](https://crontab.guru) for more crontab syntax