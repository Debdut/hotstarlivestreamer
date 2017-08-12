#/bin/bash
echo "paste the link"
read link

folder=$PWD/videos/

ffmpeg=""
php vootffmpeg.php "$link" "$folder" "$ffmpeg"
echo "write bitrate"
read quality

php vootffmpeg.php "$link" "$folder" "$ffmpeg" "$quality" 
