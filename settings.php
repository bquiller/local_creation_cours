<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {                       // Administrateurs seulement.
    $settings = new admin_settingpage(
        'local_creation_cours',
        get_string('pluginname', 'local_creation_cours')
    );

    // Votre case à cocher « Formulaire actif ».
    $settings->add(new admin_setting_configcheckbox(
        'local_creation_cours/enableform',
        'Activer le formulaire',
        'Permet d\'activer/déscativer le forumlaire de création de cours',
        1
   ));

    // Purger le cache du formulaire

	if (optional_param('purgecache', 0, PARAM_BOOL) && confirm_sesskey()) {
	    require_capability('moodle/site:config', context_system::instance());

	    apcu_clear_cache ();

	    $msg = 'Purge lancée avec succès ! (le prochain chargement du formulaire sera long)';
	    \core\notification::add($msg, \core\output\notification::NOTIFY_SUCCESS);

	    // Toujours rediriger pour éviter le repost.
    	redirect(new moodle_url('/admin/settings.php', ['section' => 'local_creation_cours']));
}


	$btnurl = new moodle_url($PAGE->url,
	    ['purgecache' => 1, 'sesskey' => sesskey()]);
	$buttonhtml = html_writer::link(
	    $btnurl,
	    'Purger le cache du formulaire',
	    ['class' => 'btn btn-secondary']
	);

	$settings->add(new admin_setting_heading(
	    'local_creation_cours/testbuttonheading',
	    '',
	    $buttonhtml          // Affiche le « bouton »
	));


    $ADMIN->add('localplugins', $settings); // ← INDISPENSABLE
}
