<?php
// Your github username
define('USERNAME', 'ankitagarwal');

// Name of the github repo
define('REPO', 'moodle');

// List of fields to display in the result
$elements = array('title', 'resolution', 'status', 'assignee', 'component', 'updated');

// Number of branches to show by default, set it to 0 to show all (it can take long time to build the list for all your branches)
$limit = 0;

// Edit the regex below if you use any other format than MDL-xxxx in your git branch name for specifing the MDL number
$regex = '<MDL-([0-9]+)?>i';

// Edit the regex below if you use any other format than mxx in your git branch name for specifing the Model version (ex m22 -22 stable)
$regex2 = '<m(aster|[0-9]{2})>i';

// Debug?
define('DEBUG', true);

// Number of errors , to trigger the termination of script execution
define('ERROR', 5);

// FUTURE USE Github password, I will migrate script to oAuth, but untill I do you need to specifiy the pass
define('SEC', 'xxxxxx');

// Name of the remote ref
define('GITHUB', 'github');

//Name of the upstream ref
define('ORIGIN', 'origin');
// Branch name format (used by cherry-pick script only) (xxxx will be replaced by mdl number and yy by stable version)
define('BRANCHFORMAT', 'MDL-xxxx-myy');

// Run codechecker before doing cherry-pick?
define('RUN_CODECHECKER', true);

// Paths to Moodle instances
$instances = array("19" => "/var/www/stable/19/moodle",
        "20" => "/var/www/stable/20/moodle",
        "21" => "/var/www/stable/21/moodle",
        "22" => "/var/www/stable/22/moodle",
        "23" => "/var/www/stable/23/moodle",
        "master" => "/var/www/stable/master/moodle",
);
// Uncomment the line below if your script is timing out (you probabily should reduce the value of $limit instead)
// set_time_limit(0);

// Dont edit below unless you know what you are doing
// Getting the git class ready
Github_Autoloader::register();
$github = new Github_Client();
define('METHOD', $github::AUTH_HTTP_PASSWORD);
