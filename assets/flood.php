<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 28-4-19
 * Time: 17:49
 */
for ($var = 0; $var < $argv[2]; $var++) {
    exec("screen -dmS $var node index.js $argv[3] $argv[4] $argv[5]");
}