<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP
// Fichier source, a modifier dans svn://zone.spip.org/spip-zone/_core_/plugins/mots/lang/
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

$GLOBALS[$GLOBALS['idx_lang']] = array(

	// B
	'bouton_associer' => 'Associer',
	'bouton_dissocier' => 'Dissocier',
	'bouton_fusionner' => 'Fusionner',

	// E
	'erreur_admin_mot_action_inconnue' => 'Que voulez-vous faire ?',
	'erreur_mot_cle_deja' => 'Impossible : c’est déjà le mot-clé sur lequel vous êtes.',
	'erreur_selection_id' => 'Selectionnez un mot-clé ou indiquez son ID dans le champ de saisie',

	// I
	'icone_administrer_mot' => 'Admin. avancée',

	// L
	'label_associer_objets_mot' => '<b>Associer</b> ce mot aux objets ayant déjà le mot',
	'label_confirm_fusion' => 'Cette opération n’est pas annulable.<br />
<strong>Le mot-clé courant #@id_mot@ sera supprimé</strong> et les liens suivants seront transférés sur le mot #@id_mot_new@ : ',
	'label_confirm_fusion_check' => 'Cochez pour confirmer la fusion du mot #@id_mot@ avec le mot #@id_mot_new@',
	'label_dissocier_objets_mot' => '<b>Dissocier</b> ce mot des objets ayant aussi le mot',
	'label_fusionner_mot' => '<b>Fusionner</b> avec le mot-clé',
	'label_mot_1_enfant' => 'Enfant :',
	'label_mot_nb_enfants' => 'Enfants :',
	'label_mot_parent' => 'Parent :',

	// P
	'placeholder_id_mot' => 'ou #ID_MOT',
	'placeholder_select' => 'Selectionner…',

	// R
	'result_associer_nb' => ' ont été associé à ce mot-clé',
	'result_associer_ras' => 'Rien à faire : tous les objets ont déjà ce mot-clé',
	'result_cancel_ok' => 'La dernière opération a été annulée.',
	'result_dissocier_nb' => ' ont été dissociés de ce mot-clé',
	'result_dissocier_ras' => 'Rien à faire : aucun objet concerné n’est associé à ce mot-clé',
	'result_fusionner_ok' => 'Vous pouvez maintenant supprimer ce mot : tous les liens ont été transférés sur l’autre @mot@.',

	// T
	'titre_formulaire_administrer_mot' => 'Administrer le mot'
);
