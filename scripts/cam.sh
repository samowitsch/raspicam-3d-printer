#!/bin/bash

#DATE=$(date +%d.%m.%Y)
DATE=$(date +%Y%m%d)
HOUR=$(date +%H%M%S)
TEXT="$DATE-$HOUR"

ENABLED="/var/www/html/ENABLED"

if [ -f "$ENABLED" ]
then

raspistill -w 1024 -h 768 -t 500 -q 60 -v -o /var/www/html/cam.jpg
convert /var/www/html/cam.jpg -pointsize 30 -fill yellow -annotate +20+40 "$TEXT" /var/www/html/cam.jpg
convert /var/www/html/cam.jpg -resize 240 -quality 60% /var/www/html/cam-thumb.jpg

cp /var/www/html/cam.jpg "/var/www/html/archive/cam-$TEXT.jpg"
cp /var/www/html/cam-thumb.jpg "/var/www/html/archive-thumbs/cam-$TEXT.jpg"

cd /var/www/html
df -h | grep '/dev/root' > usage.txt
du -h ./archive/ >> usage.txt
du -h ./archive-thumbs/ >> usage.txt
du -h ./timelap/ >> usage.txt
du -h ./timelap-thumbs/ >> usage.txt

fi
