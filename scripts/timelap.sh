#!/bin/bash

#DATE=$(date +%d.%m.%Y)
DATE=$(date +%Y%m%d)
HOUR=$(date +%H%M%S)
TEXT="$DATE-$HOUR"

ENABLED="/var/www/html/TIMELAP.txt"
RENDERING="/var/www/html/TIMELAP-RENDERING.txt"

if [ -f "$ENABLED" ]
then

mv $ENABLED $RENDERING

cd /var/www/html/timelap

echo ">> scan directory:"
for file in [[:digit:]]*.mp4; do
echo $file
done

newfile=$(printf %04d $((10#${file%.mp4}+1))).mp4
picfile=$(printf %04d $((10#${file%.mp4}+1))).jpg
echo ">> using filenames:"
echo ">> video: $newfile, thumbnail: $picfile"


echo ">> Timelap file found!"

echo ">> make video from pictures"
cd /var/www/html
ffmpeg -f concat -i $RENDERING -vsync vfr -pix_fmt yuv420p -vf scale=480:-1 timelap/$newfile

duration=$(ffprobe -i timelap/$newfile -show_format -v quiet | sed -n 's/duration=//p' | awk '{print int($1)}')
thumbTime=$(echo $duration / 1.5 | bc)

echo ">> make thumbnaill from video of sec. $duration"

ffmpeg -ss $thumbTime -i timelap/$newfile -t 1 -s 480x360 -f image2 timelap-thumbs/$picfile

echo ">> Delete Timelap file!"
rm $RENDERING

fi
