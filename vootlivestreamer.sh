#/bin/bash
echo "paste the link"
read link

folder=$PWD/videos/
livestreamer=""
ffmpeg=""


php voot.php "$link" "$folder" "$livestreamer"
echo "write bitrate"
read quality

php voot.php "$link" "$folder" "$livestreamer" "$quality"

