<?php
/**
 * @copyright  2012 Ankit Agarwal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$option = $_POST['option'];
$branches = $_POST['list'];
if(!in_array($option, array('1', '2', '3')) || !is_array($branches))
    die("la la la la la :P");

require_once 'lib/lib/Github/Autoloader.php';
require_once 'config.php';
require_once 'locallib.php';



$resp = $github->authenticate(USERNAME, SEC, METHOD);
if(!empty($resp)) {
    echo "Cannot Authenticate...terminating<br />";
}
$error = 0;
echo "<b> Please note that due to some cache issues with developer API,
        the branches are removed from cache only after a push (any push)</b><br />";
foreach($branches as $branch) {
    if($error == ERROR) {
        echo "Too Many errors....terminating<br />";
        die();
    }
    $ver = preg_match($regex2, $branch, $res);
    if($ver) {
        if(in_array($res[1], array(19, 20, 21, 22))) {
            $version = $res[1];
            $ver_stable = "MOODLE_".$version."_STABLE";
        } else {
            $version = $res[0];
            $ver_stable = $version;
        }
    } else {
        echo "<b>Cannot determine the version of $branch</b> <br />";
        continue;
    }
    echo "<b>Deleting $branch</b> <br />";
    flush();
    switch($option) {
        case 3:
        case 1:
            echo "Deleting Local $branch <br />";
                $last = false;
                $last =  system("cd ".$instances[$version].";
                                git reset --hard;
                                git checkout $ver_stable;
                                git branch -D ".$branch, $ret);
                if(empty($last) || preg_match('/needs merge/is', $last)) {
                    $error++;
                    echo "Local Delete unsuccessful. Error flag count $error <br />";
                } else {
                    echo "Success <br />";
                }
                if($option != 3) {
                    break;
                }
        case 2:
                echo "Deleting Remote $branch <br />";
               /* // Try doing deleteing stuff from shell
                echo "cd ".$instances[$version].";
                                git reset --hard;
                                git checkout $ver_stable;
                                git push ". GITHUB ." :$branch";

                $last = system("cd ".$instances[$version].";
                                git reset --hard;
                                git checkout $ver_stable;
                                git push ". GITHUB ." :refs/heads/".GITHUB."/".$branch);
                if(empty($last) || preg_match('/needs merge/is', $last)) {
                    $error++;
                    echo "Remote Delete unsuccessful. Error flag count $error <br />";
                } else {
                echo "Success <br />";
                }*/
                // Use Github api to delete remote branch
                $resp = $github->getRefApi()->deleteBranch(USERNAME, REPO, $branch);
                if(DEBUG)
                    print_r($resp);
                $resp = $github->getRefApi()->getBranch(USERNAME, REPO, $branch);
                if(DEBUG)
                    print_r($resp);
                if(!is_array($resp) && preg_match('/HTTP 404/s', $resp)){
                    echo "Success <br />";
                } else {
                    $error++;
                    echo "<br />Remote Delete unsuccessful. Error flag count $error <br />";

                }
                break;
        default: break;
    }
}
$github->deAuthenticate();
echo "All tasks completed <br />";