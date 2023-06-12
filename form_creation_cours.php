<?php


require_once("$CFG->libdir/formslib.php");
// Decommenter pour forcer le rechargement du formulaire
//apcu_clear_cache ();

class simplehtml_form extends moodleform {
	//Add elements to form
	public function definition() {
		global $CFG;
		global $USER;
		
		$hform = $this->_form; // Don't forget the underscore! 

		// Bouton d'aide
		$hform->addElement('html',"<a id='oaide' class='btn btn-secondary unimes' data-action='modal'>Comment utiliser le formulaire de cr&eacute;ation de cours ?</a><br/><br/>");

        	$hform->addElement('html', "
			<div id='popaide' class='modal moodle-has-zindex hide' data-region='modal-container' aria-hidden='false' role='dialog' tabindex='-1' style='z-index: 1052;'>
			<div class='modal-dialog modal-lg' role='document' data-region='modal' aria-labelledby='0-modal-title' tabindex='0'>
			<div class='modal-content'>
			    <div class='modal-header'><h2 class='unimes'>Comment utiliser le formulaire de cr&eacute;ation de cours ?</h2>
				<a class='close' data-action='hide' aria-label='Fermer' data-dismiss='modal' aria-hidden='true' id='faide'>
		                    <i class='fa fa-times-circle-o' aria-hidden='true'></i>
                		</a>
			    </div>
			    <div class='modal-body'>
				Ce formulaire vous permet de demander la cr&eacute;ation d'un cours.
				<br/>
				Que souhaitez-vous faire ?
				<br/><br/>
				<table border='1'>
				  <tr class='unimes'>
				    <th>Vous souhaitez cr&eacute;er un espace de cours vide</th>
				    <th>Vous souhaitez r&eacute;cup&eacute;rer votre cours de l'an dernier</th>
				  </tr>
				  <tr>
				    <td>
					<b>1.</b> Dans la premi&egrave;re partie du formulaire : 'Cr&eacute;ation d'un cours vide', remplir les quatre listes d&eacute;roulantes pour identifier le cours que vous souhaitez cr&eacute;er.<br/>
					<b>2.</b> Choisir le mod&egrave;le de cours.<br/>
					<b>3.</b> Cliquez sur [Enregistrer] en bas de la page.<br/>
					<b>4.</b> &Agrave; la page suivante vous pouvez choisir entre :<ul style='margin-left:1em;'>
						<li style='line-height: normal;'>[Cr&eacute;er la cl&eacute; d'inscription] &agrave; votre cours,</li>
						<li style='line-height: normal;'>[Acc&eacute;der &agrave; votre cours],</li>
						<li style='line-height: normal;'>[Cr&eacute;er ou supprimer un cours].</li>
					</ul>
				    </td>
				    <td>
					<b>1.</b> Dans la premi&egrave;re partie du formulaire : 'Cr&eacute;ation d'un cours vide', remplir les quatre liste d&eacute;roulante pour identifier le cours que vous souhaitez cr&eacute;er.<br/>
					<b>2.</b> S&eacute;lectionner le cours dont vous souhaitez r&eacute;cup&eacute;rer le contenu dans la liste d&eacute;roulante de la partie 2 du formulaire : 'R&eacute;cup&eacute;ration d'un cours de l'an dernier '.<br/>
					<b>3.</b> Choisir le mod&egrave;le de cours.<br/>
					<b>4.</b> Cliquez sur [Enregistrer] en bas de la page.<br/>
					<b>5.</b> A la page suivante vous pouvez choisir entre :<ul style='margin-left:1em;'>
                                                <li style='line-height: normal;'>[Cr&eacute;er la cl&eacute; d'inscription] &agrave; votre cours (attention, si vous aviez une cl&eacute; d'inscription, merci de l'inscrire &agrave; nouveau sur e-campus),</li>
                                                <li style='line-height: normal;'>[Acc&eacute;der &agrave; votre cours],</li>
                                                <li style='line-height: normal;'>[Cr&eacute;er ou supprimer un cours].</li>
					</ul>
				    </td>
				  </tr>
				</table>
			    </div>
			  </div>
			</div>
		      </div>
		");

		// Bouton de suppression de cours
		$hform->addElement('html',"<a style='float: left; position: absolute; top: 2em; right: 2em;' class='btn btn-primary' href='annuler_creation_cours.php' >
			Suppression d'un cours cr&eacute;&eacute; par erreur</a>");

		// Formulaire de création proprement dit
		$mform = $this->_form; // Don't forget the underscore! 
		$mform->setRequiredNote('* = champs obligatoires');
		$mform->setJsWarnings('Erreur de saisie ','Veuillez corriger');

		// Première partie : cr&eacute;ation d'un cours
		
		$mform->addElement('header', 'destination', 'Cr&eacute;ation d\'un cours vide');
		$mform->addElement('html', 'S&eacute;lectionnez votre cours en utilisant obligatoirement les 4 listes d&eacute;roulantes.<br/><br/>');

		// On stocke les cours deja crees
		defined('MOODLE_INTERNAL') || die();
                global $DB;
		$sql = "SELECT c.idnumber, concat(u.firstname,' ', u.lastname) enseignant, r.component FROM mdl_user u, mdl_role_assignments r, mdl_context cx, mdl_course c  WHERE c.idnumber is not null AND u.id = r.userid  AND r.contextid = cx.id  AND cx.instanceid = c.id AND r.roleid < 5 AND r.component = 'enrol_flatfile' UNION SELECT c.idnumber, concat(u.firstname,' ', u.lastname) enseignant, r.component FROM mdl_user u, mdl_role_assignments r, mdl_context cx, mdl_course c  WHERE c.idnumber is not null and c.idnumber <> '' AND u.id = r.userid  AND r.contextid = cx.id  AND cx.instanceid = c.id AND r.roleid < 5 AND c.idnumber not in (SELECT c.idnumber FROM mdl_role_assignments r, mdl_context cx, mdl_course c WHERE c.idnumber is not null AND r.contextid = cx.id  AND cx.instanceid = c.id AND r.roleid < 5 AND r.component = 'enrol_flatfile')";

                $courses = $DB->get_records_sql($sql, $params, 0, $limit);
		$courscrees = array();
                foreach ($courses as $course) {
                      // echo $course->idnumber . ' --> ' . $course->enseignant;
                        $courscrees[$course->idnumber] = $course->enseignant;
                }

		if(!apcu_exists('niveaux1') || !apcu_exists('niveaux2') || !apcu_exists('niveaux3') || !apcu_exists('niveaux4') ){
			// $connect = ocilogon($CFG->si_user,$CFG->si_pass,$CFG->si_url_base);
			$connect = oci_connect($CFG->si_user,$CFG->si_pass,$CFG->si_url_base, 'AL32UTF8');
		}
		
		// Le niveau 1 
		if(!apcu_exists('niveaux1')){
			$req = "select * from mdl_niveau1";
			$stmt = ociparse($connect,$req);
			ociexecute($stmt,OCI_DEFAULT);
			$niveaux1 = array();
			while (($row = oci_fetch_array($stmt, OCI_BOTH)) != false) {
				$niveaux1[] = $row;
			}
			apcu_store('niveaux1', $niveaux1);
		}
		$niveaux1_cache = apcu_fetch('niveaux1');
			
		$select_niveau1 = $mform->createElement( 'select', 'niveau1', 'Niveau 1 :', null, array('onchange' => 'setTextField(this,\'tniveau1\');'));
		$select_niveau1->addOption( 'Domaines / DU / UE  d\'ouverture', '', array( 'disabled' => 'disabled', 'selected'=>'true' ) );
		
		foreach ($niveaux1_cache as $row) {
			$select_niveau1->addOption($row[1],$row[0]);
		}
		$mform->addElement($select_niveau1);
		$mform->addRule('niveau1', 'Vous devez saisir une ligne dans "Domaines / DU / UE d\'ouverture"', 'required', '', 'client');
		$mform->addElement('hidden', 'tniveau1', '',array('id'=>'tniveau1'));
		$mform->setType('tniveau1', PARAM_NOTAGS); 

		// Le niveau 2
		if(!apcu_exists('niveaux2')){
			$req = "select * from mdl_niveau2";
			$stmt = ociparse($connect,$req);
			ociexecute($stmt,OCI_DEFAULT);
			$niveaux2 = array();
			while (($row = oci_fetch_array($stmt, OCI_BOTH)) != false) {
				$niveaux2[] = $row;
			}
			apcu_store('niveaux2', $niveaux2);
		}
		$niveaux2_cache = apcu_fetch('niveaux2');
				
		$select_niveau2 = $mform->createElement( 'select', 'niveau2', 'Niveau 2 :', null, array('onchange' => 'setTextField(this,\'tniveau2\');'));
		$select_niveau2->addOption( 'Dipl&ocirc;me / mention', '', array( 'disabled' => 'disabled', 'selected'=>'true' ) );
		
		foreach ($niveaux2_cache as $row) {
			$select_niveau2->addOption($row[1],$row[0],array(' class'=>$row[2]));
		}
		$mform->addElement($select_niveau2);
		$mform->addRule('niveau2', 'Vous devez saisir une ligne dans "Diplome / mention"', 'required', '', 'client');
		$mform->addElement('hidden', 'tniveau2', '',array('id'=>'tniveau2'));
		$mform->setType('tniveau2', PARAM_NOTAGS); 
		
		// Le niveau 3
		if(!apcu_exists('niveaux3')){
			$req = "select * from mdl_niveau3 where code in (select distinct id || '' from mdl_niveau4) or CODE like 'UEO%'";
			$stmt = ociparse($connect,$req);
			ociexecute($stmt,OCI_DEFAULT);
			$niveaux3 = array();
			while (($row = oci_fetch_array($stmt, OCI_BOTH)) != false) {
				$niveaux3[] = $row;
			}
			apcu_store('niveaux3', $niveaux3);
		}
		$niveaux3_cache = apcu_fetch('niveaux3');
		
		$select_niveau3 = $mform->createElement( 'select', 'niveau3', 'Niveau 3 :', null, array('onchange' => 'setTextField(this,\'tniveau3\');'));
		$select_niveau3->addOption( 'Semestre / Parcours', '', array( 'disabled' => 'disabled', 'selected'=>'true' ) );
		foreach ($niveaux3_cache as $row) {
			$select_niveau3->addOption($row[1],$row[0],array(' class'=>$row[2]));
		}
		$mform->addElement($select_niveau3);
		$mform->addRule('niveau3', 'Vous devez saisir une ligne dans "Semestre / Parcours"', 'required', '', 'client');
		$mform->addElement('hidden', 'tniveau3', '',array('id'=>'tniveau3'));
		$mform->setType('tniveau3', PARAM_NOTAGS); 
		
		// Le niveau 4
		if(!apcu_exists('niveaux4')){
			$req = "select * from mdl_niveau4";
			$stmt = ociparse($connect,$req);
			ociexecute($stmt,OCI_DEFAULT);
			$niveaux4 = array();
			while (($row = oci_fetch_array($stmt, OCI_BOTH)) != false) {
				$niveaux4[] = $row;
			}
			apcu_store('niveaux4', $niveaux4);
		}
		$niveaux4_cache = apcu_fetch('niveaux4');

		$select_niveau4 = $mform->createElement( 'select', 'niveau4', 'Niveau 4 :', null, array('onchange' => 'setTextField(this,\'tniveau4\');'));
		$select_niveau4->addOption( 'Cours', '', array( 'disabled' => 'disabled', 'selected'=>'true' ) );
		foreach ($niveaux4_cache as $row) {
			if (in_array($row[0],array_keys($courscrees)))
				$select_niveau4->addOption($row[1] . ' par ' . $courscrees[$row[0]],$row[0],array('disabled' => 'disabled', ' class'=>$row[2]));
			else $select_niveau4->addOption($row[1],$row[0],array(' class'=>$row[2]));
		}
		
		$mform->addElement($select_niveau4);
		$mform->addRule('niveau4', 'Vous devez saisir une ligne dans "Cours"', 'required', '', 'client');
		$mform->addElement('hidden', 'tniveau4', '',array('id'=>'tniveau4'));
		$mform->setType('tniveau4', PARAM_NOTAGS); 
		
		// Seconde partie : Restauration de cours 
		$mform->addElement('header', 'source', 'R&eacute;cup&eacute;ration d\'un cours de l\'an dernier');
		$mform->closeHeaderBefore('source');
		$mform->setExpanded('source');
		$mform->addElement('html', 'Choisir dans la liste d&eacute;roulante ci-dessous le cours de l\'ancienne plateforme dont vous souhaitez r&eacute;cup&eacute;rer le contenu.<br/><br/>');

		// guillaume update postgres

		/*
		$db = mysqli_connect($CFG->old_mysql, $CFG->old_user, $CFG->old_passwd) or die("Cannot connect to database engine!");
		mysqli_select_db($db, $CFG->old_database) or die("Cannot connect to database $CFG->dbname !");
		mysqli_query ($db, "set names utf8");
		*/

		$oldmoodle_conn_string = "host=$CFG->old_database_server port=5432 dbname=$CFG->old_database user=$CFG->dbuser password=$CFG->dbpass options='--client_encoding=UTF8'";
		$db = pg_connect($oldmoodle_conn_string) or die("Cannot connect to database engine!");

		$sql = "SELECT distinct c.id courseid, c.fullname coursename, c.shortname shortname FROM mdl_user u, mdl_role_assignments r, mdl_context cx, mdl_course c WHERE u.id = 
r.userid AND r.contextid = cx.id AND cx.instanceid = c.id AND r.roleid in (2,3) AND cx.contextlevel =50 ";

		if ($USER->username != 'admin') $sql .= "AND u.username = '".$USER->username."'";
		$result = pg_query($db, $sql) ;

		if (!$result) echo "Aucun cours disponible";
		else {
			
			$select_oldcourse = $mform->createElement( 'select', 'oldcourse', 'Ancien cours :', null, array('onchange' => 'if (this.selectedIndex) disableOptionsNoBackup(); else enableOptionsNoBackup();'));
			// $select_oldcourse->addOption( 'Ancien cours', '', array( 'disabled' => 'disabled', 'selected'=>'true' ) );
			$select_oldcourse->addOption( 'Ancien cours', '', array('selected'=>'true' ) );
			
			while ($row = pg_fetch_assoc($result)) $select_oldcourse->addOption($row["coursename"] . '(' .$row["shortname"] .')',$row["courseid"]);
				
			$mform->addElement($select_oldcourse);
			// $mform->addRule('oldcourse', 'Vous devez saisir une ligne dans "Ancien cours"', 'required', '', 'client');
		} 
		pg_close($db);


                // Dernière partie : Modèle de cours
                $mform->addElement('header', 'model', 'Les mod&egrave;les de cours');
                $mform->closeHeaderBefore('model');
                $mform->setExpanded('model');
                $mform->addElement('html', 'Choisir ci-dessous le mod&egrave;le de cours que vous souhaitez utiliser.<br/><br/>');

	        $mform->addGroup(array(
		  $mform->createElement('radio', 'template', '', 
		    '<figure><figcaption><a class="btn btn-secondary" data-toggle="modal" data-target="#stthem">Strandard - Th&eacute;matique</a></figcaption><img src="/pluginfile.php/77098/mod_label/intro/standard_them.jpg" width="250px" alt="Strandard th&eacute;matique"/></figure>', 'standard_them'),
		  $mform->createElement('radio', 'template', '', 
		    '<figure><figcaption><a class="btn btn-secondary" data-toggle="modal" data-target="#sttuiles">Standard - Tuiles</a></figcaption><img src="/pluginfile.php/77098/mod_label/intro/standard_tuiles.jpg" width="250px" alt="Standard tuiles"/></figure>', 'standard_tuiles'),
/*
		  $mform->createElement('radio', 'template', '', 
		    '<figure><figcaption><a class="btn btn-secondary" data-toggle="modal" data-target="#richthem">Pr&eacute;sentiel enrichi - Th&eacute;matique</a></figcaption><img src="/pluginfile.php/75/block_html/content/presenrichi_them.jpg" width="250px" alt="Pr&eacute;sentiel enrichi th&eacute;matique"/></figure>', 'presenrichi_them'),
		  $mform->createElement('radio', 'template', '', 
		    '<figure><figcaption><a class="btn btn-secondary" data-toggle="modal" data-target="#richtuiles">Pr&eacute;sentiel enrichi - Tuiles</a></figcaption><img src="/pluginfile.php/75/block_html/content/presenrichi_tuiles.jpg" width="250px" alt="Pr&eacute;sentiel enrichi tuiles"/></figure>', 'presenrichi_tuiles'),
		  $mform->createElement('radio', 'template', '', 
		    '<figure><figcaption><a class="btn btn-secondary" data-toggle="modal" data-target="#hybthem">Hybride - Th&eacute;matique</a></figcaption><img src="/pluginfile.php/75/block_html/content/hyb_them.jpg" width="250px" alt="Hybride th&eacute;matique"/></figure>', 'hyb_them'),
		  $mform->createElement('radio', 'template', '', 
		    '<figure><figcaption><a class="btn btn-secondary" data-toggle="modal" data-target="#hybtuiles">Hybride - Tuiles</a></figcaption><img src="/pluginfile.php/75/block_html/content/hyb_tuiles.jpg" width="250px" alt="Hybride tuiles"/></figure>', 'hyb_tuiles'),
 */
        	), 'templates', 'Mod&egrave;le de cours', array(' '), false);
		$mform->addRule('templates', 'Vous devez choisir un mod&egrave;le de cours', 'required', '', 'client');

        	$hform->addElement('html', "
			<div id='hybthem' class='modal moodle-has-zindex hide' data-region='modal-container' aria-hidden='false' role='dialog' tabindex='-1' style=''>
                        <div class='modal-dialog modal-lg' role='document' data-region='modal' aria-labelledby='0-modal-title' tabindex='0'>
                        <div class='modal-content'>
                            <div class='modal-header'><h2>Hybride - Th&eacute;matique</h2>
                                <a class='close' data-action='hide' aria-label='Fermer' data-dismiss='modal' aria-hidden='true' >
                                    <i class='fa fa-times-circle-o' aria-hidden='true'></i>
                                </a>
                            </div>
			    <img data-toggle='magnify' src='/pluginfile.php/75/block_html/content/hyb_them.jpg' width='90%' alt='Hybride - Th&eacute;matique'/>
                        </div>
                        </div>
			</div>
			<div id='hybtuiles' class='modal moodle-has-zindex hide' role='dialog' tabindex='-1' style=''>
                        <div class='modal-dialog modal-lg' role='document' data-region='modal' aria-labelledby='0-modal-title' tabindex='0'>
                        <div class='modal-content'>
                            <div class='modal-header'><h2>Hybride - Tuiles</h2>
                                <a class='close' data-action='hide' aria-label='Fermer' data-dismiss='modal' aria-hidden='true' >
                                    <i class='fa fa-times-circle-o' aria-hidden='true'></i>
                                </a>
                            </div>
			    <img data-toggle='magnify' src='/pluginfile.php/75/block_html/content/hyb_tuiles.jpg' width='90%' alt='Hybride - Tuiles'/>
			</div>
			</div>
			</div>
			<div id='richthem' class='modal moodle-has-zindex hide' role='dialog' tabindex='-1' style=''>
                        <div class='modal-dialog modal-lg' role='document' data-region='modal' aria-labelledby='0-modal-title' tabindex='0'>
                        <div class='modal-content'>
                            <div class='modal-header'><h2>Pr&eacute;sentiel enrichi - Th&eacute;matique</h2>
                                <a class='close' data-action='hide' aria-label='Fermer' data-dismiss='modal' aria-hidden='true' >
                                    <i class='fa fa-times-circle-o' aria-hidden='true'></i>
                                </a>
                            </div>
			    <img data-toggle='magnify' src='/pluginfile.php/75/block_html/content/presenrichi_them.jpg' width='90%' alt='Pr&eacute;sentiel enrichi - Th&eacute;matique'/>
			</div>
			</div>
			</div>
			<div id='richtuiles' class='modal moodle-has-zindex hide' role='dialog' tabindex='-1' style=''>
                        <div class='modal-dialog modal-lg' role='document' data-region='modal' aria-labelledby='0-modal-title' tabindex='0'>
                        <div class='modal-content'>
                            <div class='modal-header'><h2>Pr&eacute;sentiel enrichi - Tuiles</h2>
                                <a class='close' data-action='hide' aria-label='Fermer' data-dismiss='modal' aria-hidden='true' >
                                    <i class='fa fa-times-circle-o' aria-hidden='true'></i>
                                </a>
                            </div>
			    <img data-toggle='magnify' src='/pluginfile.php/75/block_html/content/presenrichi_tuiles.jpg' width='90%' alt='Pr&eacute;sentiel enrichi - Tuiles'/>
			</div>
			</div>
			</div>
			<div id='stthem' class='modal moodle-has-zindex hide' role='dialog' tabindex='-1' style=''>
                        <div class='modal-dialog modal-lg' role='document' data-region='modal' aria-labelledby='0-modal-title' tabindex='0'>
                        <div class='modal-content'>
                            <div class='modal-header'><h2>Standard - Th&eacute;matique</h2>
                                <a class='close' data-action='hide' aria-label='Fermer' data-dismiss='modal' aria-hidden='true' >
                                    <i class='fa fa-times-circle-o' aria-hidden='true'></i>
                                </a>
                            </div>
			    <img data-toggle='magnify' src='/pluginfile.php/77098/mod_label/intro/standard_them.jpg' width='90%' alt='Standard - Th&eacute;matique'/>
			</div>
			</div>
			</div>
			<div id='sttuiles' class='modal moodle-has-zindex hide' role='dialog' tabindex='-1' style=''>
                        <div class='modal-dialog modal-lg' role='document' data-region='modal' aria-labelledby='0-modal-title' tabindex='0'>
                        <div class='modal-content'>
                            <div class='modal-header'><h2>Standard - Tuiles</h2>
                                <a class='close' data-action='hide' aria-label='Fermer' data-dismiss='modal' aria-hidden='true' >
                                    <i class='fa fa-times-circle-o' aria-hidden='true'></i>
                                </a>
                            </div>
			    <img data-toggle='magnify' src='/pluginfile.php/77098/mod_label/intro/standard_tuiles.jpg' width='90%' alt='Standard - Tuiles'/>
			</div>
			</div>
			</div>");


		$this->add_action_buttons();

	}
	//Custom validation should be added here
	function validation($data, $files) {
		return array();
	}
}

?>
