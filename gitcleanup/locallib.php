<?php
/**
 * @copyright  2012 Ankit Agarwal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once 'lib/lib/Github/Autoloader.php';

/* fetch the xml feed using curl
 *
 * @param string $url feed url
 * @return string feed contents
 */
function get_file_remote ($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $xml = curl_exec($ch);
    curl_close($ch);
    return $xml;
}


/* Process feed
 *
* @param XML element $xml_tree xml content to process
* @param array $elements list of fields whose value needs to be found out
* @return object value of requested fields
*/
function process_xml_feed ($xml_tree, $elements) {
    if (empty($xml_tree) || empty($elements))
        return false;
    $return = new stdClass();
    foreach($elements as $element) {
        if (!empty($xml_tree->$element)) {
            $return->$element = $xml_tree->$element;
        } else {
            $return->$element = "";
        }
    }
    return $return;
}

/* Generate xml tree from xml content
 *
* @param string $xml xml content to process
* @return mixed XML tree if succcess else false
*/
function generate_xml_tree($xml) {
    libxml_use_internal_errors(true);
    try {
        $xml_tree = new SimpleXMLElement($xml);
        return $xml_tree;
    } catch (Exception $e) {
        $error = 'Something went wrong';
        //Un comment the line below to trigger an error
        //trigger_error($error);
        return false;
    }
}
/* Process feed
 *
* @param string  $mdl MDL-xxxxx
* @param array $elements list of fields whose value needs to be found out
* @return mixed value of requested fields or false
*/
function build_tracker_dataobject ($mdl, $elements) {
    $url = "http://tracker.moodle.org/sr/jira.issueviews:searchrequest-xml/temp/SearchRequest.xml?jqlQuery=id=".$mdl."&tempMax=1000";
    $xml = get_file_remote ($url);
    if (empty($xml))
        return false;

    $xml_tree = generate_xml_tree($xml);
    if (empty($xml_tree))
        return false;
    $xml_tree = $xml_tree->channel->item;
    $return = process_xml_feed ($xml_tree, $elements);
    return $return;
}
/**
 * Managing references
*
* @link      http://develop.github.com/p/users.html
* @author    Ankit Agarwal
* @license   GPL 3 or later
*/
class Github_Api_Ref extends Github_Api
{

    /**
     * Delete a branch of a repository
     * http://develop.github.com/p/repo.html
     *
     * @param   string  $username         the username
     * @param   string  $repo             the name of the repo
     * @return  array                     list of the repo branches
     */
    public function deleteBranch($username, $repo, $branch)
    {
        $response = $this->delete('repos/'.urlencode($username).'/'.urlencode($repo).'/git/refs/heads/'.urlencode($branch),array() ,array('format' =>'json'));
        return $response;
    }

    /**
     * Get a branch of a repository
     * http://develop.github.com/p/repo.html
     *
     * @param   string  $username         the username
     * @param   string  $repo             the name of the repo
     * @return  array                     list of the repo branches
     */
    public function getBranch($username, $repo, $branch)
    {
        $response = $this->get('repos/'.urlencode($username).'/'.urlencode($repo).'/git/refs/heads/'.urlencode($branch),array(),array('format' =>'json'));

        return $response;
    }

}
