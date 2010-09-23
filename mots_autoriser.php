<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2009                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

function mots_autoriser(){}


function autoriser_mots_bouton_dist($faire, $type, $id, $qui, $opt){
	return 	($GLOBALS['meta']['articles_mots'] != 'non' OR sql_countsel('spip_groupes_mots'));
}
function autoriser_mot_creer_bouton_dist($faire, $type, $id, $qui, $opt){
	return 	($GLOBALS['meta']['articles_mots'] != 'non' OR sql_countsel('spip_groupes_mots'));
}


// Autoriser a creer un groupe de mots
// http://doc.spip.org/@autoriser_groupemots_creer_dist
function autoriser_groupemots_creer_dist($faire, $type, $id, $qui, $opt) {
	return
		$qui['statut'] == '0minirezo'
		AND !$qui['restreint'];
}

// Autoriser a modifier un groupe de mots $id
// y compris en ajoutant/modifiant les mots lui appartenant
// http://doc.spip.org/@autoriser_groupemots_modifier_dist
function autoriser_groupemots_modifier_dist($faire, $type, $id, $qui, $opt) {
	return
		$qui['statut'] == '0minirezo'
		AND !$qui['restreint'];
}

// Autoriser a modifier un mot $id ; note : si on passe l'id_groupe
// dans les options, on gagne du CPU (c'est ce que fait l'espace prive)
// http://doc.spip.org/@autoriser_mot_modifier_dist
function autoriser_mot_modifier_dist($faire, $type, $id, $qui, $opt) {
	return
	isset($opt['id_groupe'])
		? autoriser('modifier', 'groupemots', $opt['id_groupe'], $qui, $opt)
		: (
			$t = sql_getfetsel("id_groupe", "spip_mots", "id_mot=".sql_quote($id))
			AND autoriser('modifier', 'groupemots', $t, $qui, $opt)
		);
}



// http://doc.spip.org/@autoriser_rubrique_editermots_dist
function autoriser_rubrique_editermots_dist($faire,$quoi,$id,$qui,$opts){
	// on verifie que le champ de droit passe en opts colle bien
	$droit = substr($GLOBALS['visiteur_session']['statut'],1);
	if (!isset($opts['groupe_champs'][$droit])){
		if (!$id_groupe = $opts['id_groupe'])
			return false;
		include_spip('base/abstract_sql');
		$droit = sql_getfetsel($droit, "spip_groupes_mots", "id_groupe=".intval($id_groupe));
	}
	else
		$droit = $opts['groupe_champs'][$droit];

	return
		($droit == 'oui')
		AND
		// on verifie que l'objet demande est bien dans les tables liees
		in_array(
			table_objet($quoi),
			explode(',', $opts['groupe_champs']['tables_liees'])
		);
}
// http://doc.spip.org/@autoriser_article_editermots_dist
function autoriser_article_editermots_dist($faire,$quoi,$id,$qui,$opts){
	return autoriser_rubrique_editermots_dist($faire,'article',0,$qui,$opts);
}
// http://doc.spip.org/@autoriser_breve_editermots_dist
function autoriser_breve_editermots_dist($faire,$quoi,$id,$qui,$opts){
	return autoriser_rubrique_editermots_dist($faire,'breve',0,$qui,$opts);
}
// http://doc.spip.org/@autoriser_syndic_editermots_dist
function autoriser_syndic_editermots_dist($faire,$quoi,$id,$qui,$opts){
	return autoriser_rubrique_editermots_dist($faire,'syndic',0,$qui,$opts);
}



?>
