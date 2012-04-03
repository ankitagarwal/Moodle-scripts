<?php
/**
 * @copyright  2012 Ankit Agarwal
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once 'config.php';
require_once 'lib/lib/Github/Autoloader.php';
require_once 'locallib.php';
// Getting the git class ready
Github_Autoloader::register();
$github = new Github_Client();
$user = $github->getUserApi()->show('ornicar');
$repo = $github->getRepoApi()->show(USERNAME, REPO);

// Fetch all branches
$branches =  $github->getRepoApi()->getRepoBranches(USERNAME, REPO);
$header = "<table border = 1 wdith = 100%>
            <tr>
                <th>SL No</th>
                <th>Checkbox</th>
                <th>Branch Name</th>
                <th colspan=2>Last commit details</th>
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
        $trackerurl = "<a href =http://tracker.moodle.org/browse/".$res[0].">Tracker URL </a>";
        $trackerdata = build_tracker_dataobject ($res[0], $elements);
    } else {
        $trackerurl = "";
    }


    $row = "<tr>
            <td>$i</td>
            <td><input type=checkbox /></td>
            <td>".$branch['name']."</td>
            <td>".$branch['commit']['sha']."</td>
            <td><a href = ".$branch['commit']['url']."> Last commit URL </a></td>
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
    if($i === $limit && $limit !== 0)
        break;
}
echo "</table>";
