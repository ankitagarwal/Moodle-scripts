<?php
/**
* @copyright  2012 Ankit Agarwal
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once '../gitcleanup/lib/lib/Github/Autoloader.php';
require_once '../gitcleanup/config.php';
require_once '../gitcleanup/locallib.php';

if(!defined('STDIN'))
    die("cannot execute from browser");
echo "What is the branch name? \n";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$branchname = trim($line);

echo "Branches to port to? \n (Example 20 21 master 22) \n";
$line = fgets($handle);
$to = explode(' ', trim($line));
/*
?>
It assumes your upstream is named as origin and github remote is named as github.
<form name='form' method='post'>
Branchname <input type='text' name='branchname' />\n
To
<input type=checkbox name='list[]' value="19" >19
<input type=checkbox name='list[]' value="20" >20
<input type=checkbox name='list[]' value="21" >21
<input type=checkbox name='list[]' value="22" >22
<input type=checkbox name='list[]' value="master" >Master
\n
<input type="submit" name="submit" value="submit" />
<?php
/*if (!empty($_POST['submit'])) {
    echo "\n\nProcessing your request \n";
    if ( empty($_POST['list']) || empty($_POST['branchname']) || empty($_POST['list'])) {
        echo "la la la la la :P Gone Fishing..check back later :P \n";
        die();
    }
    $to = $_POST['list'];
    $branchname = $_POST['branchname'];*/
    if ( empty($to) || empty($branchname)) {
        echo "la la la la la :P Gone Fishing..check back later :P \n";
        die();
    }

    $resp = $github->getRefApi()->getBranch(USERNAME, REPO, $branchname);
    if(DEBUG)
        print_r($resp);
    if(!isset($resp['object']['sha'])){
        echo "\nCannot fetch remote branch...quiting\n";
        die();
    } else {
        echo "Success fetching remote branch\n";
        $sha = $resp['object']['sha'];
    }
    $mdl = preg_match($regex, $branchname, $res);
    if(empty($mdl) || empty($res[1]))
        die("are you sure the regex in config.php is correctly set?");
    $res[1] = str_ireplace('MDL', '', $res[1]);

    foreach($to as $version) {
        $branch = str_replace(array('xxxx', 'yy'), array($res[1], $version), BRANCHFORMAT);
        $stable = "MOODLE_".$version."_STABLE";
        $cmd = "cd ".$instances[$version]. ";
                git reset --hard;
                git checkout -b $branch ".ORIGIN."/".$stable. ";
                git fetch ".GITHUB." $branchname;
                git cherry-pick $sha;
                git push ".GITHUB." $branch;";
        echo system($cmd);
        echo "\n\nDetails \n";
        echo $branch."\n";
        echo $url = "https://github.com/" . USERNAME ."/moodle/compare/" . $branch . "..." .$stable."\n";
        //echo "<a href=$url>$url</a>";
    }
//}