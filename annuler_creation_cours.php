<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot.'/enrol/meta/lib.php');
require_once($CFG->dirroot.'/mod/url/lib.php');
require_once($CFG->dirroot.'/course/lib.php');

require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('base');

echo $OUTPUT->header();

$datejour = date('d/m/Y');
$djour = explode("/", date('d/m/Y')); 
$auj = $djour[2].$djour[1].$djour[0]; 

$uid = $USER->username;
$nom = fullname($USER, true);

//moodleform
require_once($CFG->dirroot.'/local/creation_cours/form_annulation_cours.php');

?>
<script type="text/javascript">
function setTextField(ddl, id) {
	document.getElementById(id).value = ddl.options[ddl.selectedIndex].text;
}
</script>

<?php
//Instantiate simplehtml_form 
$mform = new annul_html_form();
 
//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
	echo '<script type="text/javascript">window.location.href = "/local/creation_cours/creation_cours.php";</script>' ;
} else if ($fromform = $mform->get_data()) {
  //In this case you process validated data. $mform->get_data() returns data posted in form.
  $formdata = $mform->get_data();
  $course = $formdata->course;
  $tcourse = $formdata->tcourse;

  $subject = "Demande de suppression du cours ".$tcourse." (".$course.") par ".$uid;
  $message = "<html><head></head><body>Demande de suppression du cours ".$tcourse." (".$course.") par ".$uid."<br/><a href='".$CFG->wwwroot."/course/delete.php?id=".$course."'>Cliquez ici</a>.</body></html>";


  foreach ($CFG->adm_dest_mail as &$email) {
 	
  $emailuser = new stdClass();
  $emailuser->email = $email;
  $emailuser->id = -99;

  ob_start();
  $success = email_to_user($emailuser, $USER, $subject, $message);
  $smtplog = ob_get_contents();
  ob_end_clean();
  
  }
  
  unset($email); 
  
  
  echo "<span style=\"font-size:16px\">Votre demande d'annulation a &eacute;t&eacute; prise en compte.<br/><br/> Pour &eacute;viter des cons&eacute;quences f&acirc;cheuses, celle-ci doit &ecirc;tre effectu&eacute;e manuellement.</span>";
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.
 
  //Set default data (if any)
  $mform->set_data($mform);
  //displays the form
  $mform->display();
}
?>
