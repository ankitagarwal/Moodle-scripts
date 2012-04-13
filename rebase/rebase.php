<?php
/**
* @copyright  2012 Ankit Agarwal
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once '../gitcleanup/lib/lib/Github/Autoloader.php';
require_once '../gitcleanup/config.php';

if(!defined('STDIN'))
    die("cannot execute from browser");
echo "What is the branch name? \n(Example mdl-23451-master)\n";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$branchname = trim($line);

echo "Which versions needs to be rebased? \n (Example 20 21 master 22) \n Press Enter for all\n";
$line = fgets($handle);
$to = trim($line);
if (empty($to)) {
    $to = array ('20', '21', '22', 'master');
} else {
    explode(' ', $to);
}
if (empty($branchname)) {
    echo "la la la la la :P Gone Fishing..check back later :P \n";
    die();
}
$mdl = preg_match($regex, $branchname, $res);
if(empty($mdl) || empty($res[1]))
    die("are you sure the regex in config.php is correctly set?");
$res[1] = str_ireplace('MDL', '', $res[1]);

foreach($to as $version) {
    if($version == 'master') {
        $branch = str_ireplace(array('xxxx', 'myy'), array($res[1], $version), BRANCHFORMAT);
        $stable = 'master';
    } else {
        $branch = str_ireplace(array('xxxx', 'yy'), array($res[1], $version), BRANCHFORMAT);
        $stable = "MOODLE_".$version."_STABLE";
    }
    $cmd = "cd ".$instances[$version]. ";
    git reset --hard;
    git checkout $stable;
    git checkout $branch;
    git rebase origin/".$stable.";
    git push -f ".GITHUB." $branch;";
    echo system($cmd);
    echo "\n\nDetails \n";
    echo $branch."\n";
    echo $url = "https://github.com/" . USERNAME ."/moodle/compare/" . $branch . "..." .$stable."\n";
}