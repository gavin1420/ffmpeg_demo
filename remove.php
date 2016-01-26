<?php

include_once './ffmpeg.php';

$ffmpeg = new ffmpeg();
$ffmpeg->setRemoveFileName('test');
$ffmpeg->setRemoveFileType('mp4');
$ffmpeg->removeVideo();

    header("location: ./");
?>
