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
foreach($branches as $branch) {
    if($error == ERROR) {
        echo "Too Many errors....terminating<br />";
        die();
    }
    echo "Deleting $branch <br />";
    flush();
    switch($option) {
        case 3:
        case 1:
            echo "Deleting Local $branch <br />";
                $last = false;
                $ver = preg_match($regex2, $branch, $res);
                if($ver) {
                    if(in_array($res[1], array(19, 20, 21, 22))) {
                        $version = $res[1];
                        $ver_stable = "MOODLE_".$version."_STABLE";
                    } else {
                        $version = $res[0];
                        $ver_stable = $version;
                    }
                   $last =  system("cd ".$instances[$version]."; git checkout $ver_stable; git branch -D ".$branch, $ret);
                }
                if(empty($last)) {
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
                $resp = $github->getRefApi()->deleteBranch(USERNAME, REPO, $branch);
                if(DEBUG)
                    print_r($resp);
                $resp = $github->getRefApi()->getBranch(USERNAME, REPO, $branch);
                if(DEBUG)
                    print_r($resp);
                if(isset($resp['url'])){
                   $error++;
                   echo "Remote Delete unsuccessful. Error flag count $error <br />";
                } else {
                    echo "Success <br />";
                }
                break;
        default: break;
    }
}
$github->deAuthenticate();
echo "All tasks completed <br />";