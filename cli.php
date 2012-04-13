<?php
/**
* @copyright  2012 Ankit Agarwal
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/


if(!defined('STDIN'))
    die("cannot execute from browser");
while(1) {
    echo "Which script you want to Run? \n 1 -> upgrade Moodle instances (Note: it doesnt use config.php)\n 2 -> Rebase \n 3 -> Cherry-pick \n 4 -> Git cleanup\n";
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    $option = trim($line);
    if(!in_array($option, array(1, 2, 3, 4))) {
        echo "Invalid option Please go have some coffee , before trying again\n";
        continue;
    }
    switch($option) {
        case 1: system ("cd upgrade;
                         bash upgrade");
                break;

        case 2: system ("cd rebase;
                         php rebase.php");
                break;

        case 3: system ("cd cherry-pick;
                         php chery.php");
                break;

        case 4: echo "Cleanup script should be executed from browser \n";
                break;
        default: echo "Wow...I can play...nothing to do..thanks\n";
    }
    echo "Enter y/Y if you want to run another script\n";
    $line = fgets($handle);
    $option = trim($line);
    if(!($option == 'y' || $option == 'Y')) {
        echo "Bye....\n";
        die();
    }
}