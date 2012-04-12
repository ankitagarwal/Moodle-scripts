<?php
/**
* @copyright  2012 Ankit Agarwal
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once '../gitcleanup/lib/lib/Github/Autoloader.php';
require_once '../gitcleanup/config.php';
require_once '../gitcleanup/locallib.php';
?>
It assumes your upstream is named as origin and github remote is named as github.
<form name='form' method='post'>
Branchname <input type='text' name='branchname' /><br />
To
<input type=checkbox name='list[]' value="19" >19
<input type=checkbox name='list[]' value="20" >20
<input type=checkbox name='list[]' value="21" >21
<input type=checkbox name='list[]' value="22" >22
<input type=checkbox name='list[]' value="master" >Master
<br />
<input type="submit" name="submit" value="submit" />
<?php
if (!empty($_POST['submit'])) {
    echo "<br /><br />Processing your request <br />";
    if ( empty($_POST['list']) || empty($_POST['branchname']) || empty($_POST['list'])) {
        echo "la la la la la :P Gone Fishing..check back later :P <br />";
        die();
    }
    $to = $_POST['list'];
    $branchname = $_POST['branchname'];

    $resp = $github->getRefApi()->getBranch(USERNAME, REPO, $branchname);
    if(DEBUG)
        print_r($resp);
    if(!isset($resp['object']['sha'])){
        echo "<br />Cannot fetch remote branch...quiting<br />";
        die();
    } else {
        echo "Success fetching remote branch<br />";
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
                git checkout -b $branch origin/".$stable. ";
                git fetch github $branchname;
                git cherry-pick $sha;
                git push github $branch;";
        echo system($cmd);
        echo exec("/var/www/scripts/Moodle-scripts/cherry-pick/cherry '$cmd'", $out);
        print_r($out);
        echo "<br /><br />Details <br />";
        echo $branch."<br />";
        $url = "https://github.com/" . USERNAME ."/moodle/compare/" . $branch . "..." .$stable;
        echo "<a href=$url>$url</a>";
    }
    echo "<br />Running the bash script now....";
    echo $cmd;
    echo exec("/var/www/scripts/Moodle-scripts/cherry-pick/cherry '$cmd'", $out);
    print_r($out);
}