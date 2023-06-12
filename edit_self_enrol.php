<?php
error_reporting(E_ALL) ;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot.'/enrol/meta/lib.php');
require_once($CFG->dirroot.'/mod/url/lib.php');
require_once($CFG->dirroot.'/course/lib.php');

require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('base');
$PAGE->set_url('/local/creation_cours/edit_self_enrol.php');

//echo $OUTPUT->header();


global $DB;

$coursId = required_param('coursId', PARAM_TEXT);

$sql = "SELECT c.id as cid, e.id as eid FROM mdl_course c, mdl_enrol e WHERE e.courseid=c.id and e.enrol='self' and c.idnumber='$coursId'";
echo $sql;
try {
	$keys = $DB->get_records_sql($sql);
	print_r($keys);

	foreach ($keys as $id => $record) {
		$idcours = $record->cid;
		$idenrol = $record->eid;
	}
} catch (Exception $e) {
	echo '<center><br/><span style="padding:10px; color: white;background-color:red">',  $e->getMessage(), "</span><br/><br/>\n";
        echo '<span style="margin:20px;"><a class="btn btn-primary" href="'.$CFG->wwwroot.'/local/creation_cours/creation_cours.php">Cr&eacute;er un nouveau cours</a></span>';
        echo '<span style="margin:20px;"><a class="btn btn-primary" href="'.$CFG->wwwroot.'/my/index.php">Retrouver tous mes cours</a></span></center>';
        exit;
}

// echo $CFG->wwwroot."/enrol/editinstance.php?courseid=".$idcours."&id=".$idenrol."&type=self";
header("Location: ".$CFG->wwwroot."/enrol/editinstance.php?courseid=".$idcours."&id=".$idenrol."&type=self");
exit();


?>

