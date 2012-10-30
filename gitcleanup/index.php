<?php
/**
* @copyright  2012 Ankit Agarwal
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once 'lib/lib/Github/Autoloader.php';
require_once 'config.php';
require_once 'locallib.php';
require_once 'style.css';

// Fetch all branches
$branches =  $github->getRepoApi()->getRepoBranches(USERNAME, REPO, array('format' => 'json'));
$header = "<form name='inputform' method='post' action=process.php><table id=data wdith = 100%>
            <tr>
                <th>SL No</th>
                <th>Select</th>
                <th>Branch Name</th>
                <th>Last commit details</th>
                <th>Tracker Url</th>";
foreach ($elements as $element) {
    $header .= "<th>$element</th>";
}
echo $header. "</tr>";

$i = 0;
foreach($branches as $branch) {
    $i++;
    $mdl = preg_match($regex, $branch['name'], $res);
    if(!empty($mdl)) {
        $trackerurl = "<a href=http://tracker.moodle.org/browse/".$res[0].">Tracker URL </a>";
        $trackerdata = build_tracker_dataobject ($res[0], $elements);
    } else {
        $trackerurl = "";
    }
    if($i%2 == 0) {
        $class = "class=alt";
    } else {
        $class = "";
    }
    $branch['commit']['sha'] = substr($branch['commit']['sha'], 0, 14);
    $row = "<tr $class>
            <td>$i</td>
            <td><input type=checkbox name='list[]' value=".$branch['name']." /></td>
            <td><a href= http://github.com/". USERNAME ."/". REPO. "/commits/".$branch['name'].">".$branch['name']."</td>
            <td><a href = ".$branch['commit']['url']."> Last commit URL </a><br />".$branch['commit']['sha']."</td>
            <td>$trackerurl</td>";
    foreach($elements as $element) {
        if(isset($trackerdata->$element)) {
            $row .= "<td>". $trackerdata->$element ."</td>";
        } else {
            $row .= "<td></td>";
        }
    }
    $row .= "</tr>";
    echo $row;
    if($i == $limit && $limit != 0)
        break;
}
echo "</table>";
echo '
<select name="option">
<option value="1" selected="selected">Delete just the local branches</option>
<option value="2" >Delete just the remote branches</option>
<option value="3" >Delete both local and remote branches</option>
</select><br />
<input type="button" name="CheckAll" value="Check All"
onClick="checkAll(document.form.list[])">
<input type="button" name="UnCheckAll" value="Uncheck All"
onClick="uncheckAll(document.form.list)">
<input type="submit" name="submit" value="submit">
<br>
</form>';
echo '<SCRIPT LANGUAGE="JavaScript">
function checkAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = true ;
}

function uncheckAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = false ;
}
</script>';
