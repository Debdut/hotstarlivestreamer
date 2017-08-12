@ echo off
call set /p link=paste the link:
call set folder="%~dp0\videos\\"
call set ffmpeg="%~dp0\tools\ffmpeg\\"
call "%~dp0\tools\php5.4\php.exe" vootffmpeg.php "%%link%%" "%%folder%%" "%%ffmpeg%%"
call set /p quality=write bitrate:
call "%~dp0\tools\php5.4\php.exe" vootffmpeg.php "%%link%%" "%%folder%%" "%%ffmpeg%%" "%%quality%%"
:end1
pause
:end

