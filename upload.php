<?php
    
include_once './ffmpeg.php';

$ffmpeg = new ffmpeg();
$ffmpeg->setNewName("test");
$ffmpeg->setVideoTimeForFrontCover($_POST['vidoeTime']);
//$ffmpeg->run();

if($ffmpeg->run())
{
    header("location: ./");
}

?>