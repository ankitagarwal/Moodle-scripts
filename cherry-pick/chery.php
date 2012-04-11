<?php
/**
* @copyright  2012 Ankit Agarwal
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once '../gitcleanup/lib/lib/Github/Autoloader.php';
require_once '../gitcleanup/config.php';
?>
It assumes your upstream is named as origin and github remote is named as github
<form name='form' method='post'>
Branchname <input type='text' name='branchname' /><br />
From <select name="from">
<option value="19">19</option>
<option value="20" >20</option>
<option value="21" >21</option>
<option value="22" >22</option>
<option value="master" selected="selected">master</option>
</select><br />
To
<input type=checkbox name='list[]' value="19" >19
<input type=checkbox name='list[]' value="20" >20
<input type=checkbox name='list[]' value="21" >21
<input type=checkbox name='list[]' value="master" >Master
<br />
<input type="submit" name="submit" value="submit" />
<?php
if (!empty($_POST['submit'])) {
    echo "<br /><br />Processing your request <br />";
    if (empty($_POST['from']) || empty($_POST['list']) || empty($_POST['branchname']) || empty($_POST['list']) || !is_array($_POST['from'], array(19, 20, 21, 22, 'master'))) {
        echo "la la la la la :P Gone Fishing..check back later :P <br />";
        die();
    }
    $from = $_POST['from'];
    $to = $_POST['list'];
    $branchname = $_POST['branchname'];
    foreach($to as $version) {
        $branch = str_replace(array('xxxx', 'yy'), array($mdl, $version), BRANCHFORMAT)
        $stable = "MOODLE_".$version."_STABLE";
        $command = "git checkout -b $branch origin $stable; git fetch github $from;git cherry-pick $commit";
    }

}