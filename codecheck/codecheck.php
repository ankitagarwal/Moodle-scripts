<?php
/**
* @copyright  2012 Ankit Agarwal
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once '../gitcleanup/lib/lib/Github/Autoloader.php';
require_once '../gitcleanup/config.php';

if(!defined('STDIN'))
    die("cannot execute from browser");
$entry = null;
$branch = null;
$branchname = null;
unset($instances['19'], $instances['20'], $instances['21']);
// TODO make the wwwcode path below based on a setting
foreach ($instances as $version => $instance) {
    if($version == 'master') {
        $stable = 'master';
    } else {
        $stable = "MOODLE_".$version."_STABLE";
    }
    $git = 'git for-each-ref --shell --format=\'branch=%(refname) branchname=%(refname:short)\' refs/heads/ | \
            while read entry
            do
                eval "$entry";
                git log --oneline "$branch" ^origin/'.$stable.';
                git diff "$branch" ^origin/master > \'/var/www/code/\'$branchname\'.patch\';
                php '. $instances['master'].'/local/codechecker/run.php  \'/var/www/code/\'$branchname\'.patch\' > \'/var/www/code/\'$branchname\'.codecheck\'
            done';
    echo $cmd ="cd $instance; $git";
    system($cmd);
}
