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

foreach($branches as $branch) {
    echo "Deleting $branch <br />";
    flush();
    $resp = $github->getRefApi()->deleteBranch(USERNAME, REPO, $branch);
    echo $resp;
}