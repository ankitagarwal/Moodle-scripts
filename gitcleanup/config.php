<?php
// Your github username
define('USERNAME', 'ankitagarwal');

// Name of the github repo
define('REPO', 'moodle');

// List of fields to display in the result
$elements = array('title', 'resolution', 'status', 'assignee', 'component', 'updated');

// Number of branches to show by default, set it to 0 to show all (it can take long time to build the list for all your branches)
$limit = 10;

// Edit the regex below if you use any other format than MDL-xxxx in your git branch name for specifing the MDL number
$regex = '<MDL-([0-9]+)?>i';

// Debug?
define('DEBUG', true);

// Paths to Moodle instances
$intances = array("19" => "/www/repos/istable19/",
        "20" => "/www/repos/istable20/",
        "21" => "/www/repos/istable21/",
        "22" => "/www/repos/istable22/",
        "master" => "/www/repos/imaster/",
);
// Uncomment the line below if your script is timing out (you probabily should reduce the value of $limit instead)
// set_time_limit(0);

// Dont edit below unless you know what you are doing
// Getting the git class ready
Github_Autoloader::register();
$github = new Github_Client();
$method = $github::AUTH_HTTP_PASSWORD;