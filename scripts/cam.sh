#!/bin/bash

while true; do


#DATE=$(date +%d.%m.%Y)
DATE=$(date +%Y%m%d)
HOUR=$(date +%H%M%S)
TEXT="$DATE-$HOUR"

ENABLED="/var/www/html/ENABLED"

rpicam-still --brightness 0.1 --contrast 1.1 -e jpg auto --awb auto --width 1024 --height 768 -t 500 -q 85 -v -o /var/www/html/cam.jpg
convert /var/www/html/cam.jpg -pointsize 30 -fill yellow -annotate +20+40 "$TEXT" /var/www/html/cam.jpg
convert /var/www/html/cam.jpg -resize 240 -quality 60% /var/www/html/cam-thumb.jpg

if [ -f "$ENABLED" ]
then

cp /var/www/html/cam.jpg "/var/www/html/archive/cam-$TEXT.jpg"
cp /var/www/html/cam-thumb.jpg "/var/www/html/archive-thumbs/cam-$TEXT.jpg"

cd /var/www/html
df -h | grep '/dev/root' > usage.txt
du -h ./archive/ >> usage.txt
du -h ./archive-thumbs/ >> usage.txt
du -h ./timelap/ >> usage.txt
du -h ./timelap-thumbs/ >> usage.txt

rpicam-still --list-cameras >> usage.txt

fi

# processing time takes round about 4 secs so we will wait 26 secs to get 30 secs.
sleep 26

done
