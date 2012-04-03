<?php
// Your github username
define('USERNAME', 'ankitagarwal');

// Name of the github repo
define('REPO', 'moodle');

// List of fields to display in the result
$elements = array('title', 'resolution', 'status', 'assignee', 'component', 'updated');

// Number of branches to show by default 0 for all (it can take long time to build the list for all your branches)
$limit = 10;

// Edit the regex below if you use any other format than MDL-xxxx in your git branch name
$regex = '<MDL-([0-9]+)?>i';

// Uncomment the line below if your script is timing out (you probabily should reduce the value of $limit instead)
// set_time_limit(0);