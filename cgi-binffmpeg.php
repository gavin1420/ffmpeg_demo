#!/usr/bin/php
//Content-Type: text/plain

<?php

$boundary = fgets(STDIN);
$end_boundary = rtrim($boundary) . "--\r\n";

$headers = array();
while ($s = fgets(STDIN)) {
    if ($s == "\r\n") // head end line.
        break;
    if (preg_match('/(^[\w\-]+): (.*)/', $s, $m))
        $headers[$m[1]] = trim($m[2]);
}

$filename = 'test.mp4';

// Get filename of upload file.
if (isset($headers['Content-Disposition'])) {
    $disposition = $headers['Content-Disposition'];
    echo 'Content-Disposition: ', $disposition, "\n";

    if (preg_match('/filename="(.*)\.[\w]+"$/', $disposition, $m))
        $filename = $m[1] . '.mp4';
}

// You may save this filename.
echo $filename, "\n";

$out_stream = popen('ffmpeg -i - -vcodec libx264 -b:v 500000 -s 640x360 
    -acodec libvo_aacenc -b:a 128000 -ar 48000 -y /tmp/' . $filename, 'w');
#$out_stream = popen('ffmpeg -i - -vcodec mpeg4 -vb 1000k -r 25 -s 640x360 \
#    -acodec mp2 -ab 96k -ar 22050 -y /tmp/' . $filename, 'w');

if ($out_stream === false) {
    echo "Could not invoke ffmpeg.\n";
    exit(1);
}

$data_len = 0;
$out_buffer = false;
while ($s = fgets(STDIN)) {
    #if (strpos($s, $boundary) === 0)
    if ($s == $end_boundary) {
        #echo "BREAK\n";
        break;
    }
    fwrite($out_stream, $out_buffer);
    $data_len += strlen($out_buffer);
    $out_buffer = $s;
}

fwrite($out_stream, substr($out_buffer, 0, -2)); // strip end mark (\r\n).
pclose($out_stream);

$data_len += strlen($out_buffer) - 2;

// Output read bytes to browser.
echo $data_len;

?>