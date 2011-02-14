<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2011                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

function mots_autoriser(){}


function autoriser_mots_bouton_dist($faire, $type, $id, $qui, $opt){
	return 	($GLOBALS['meta']['articles_mots'] != 'non' OR sql_countsel('spip_groupes_mots'));
}
function autoriser_motcreer_bouton_dist($faire, $type, $id, $qui, $opt){
	return 	($GLOBALS['meta']['articles_mots'] != 'non');
}

// Voir un objet
// http://doc.spip.org/@autoriser_voir_dist
function autoriser_groupemots_voir_dist($faire, $type, $id, $qui, $opt) {
	if ($qui['statut'] == '0minirezo') return true;
	$acces = sql_fetsel("comite,forum", "spip_groupes_mots", "id_groupe=".intval($id));
	if ($qui['statut']=='1comite' AND ($acces['comite'] == 'oui' OR $acces['forum'] == 'oui'))
		return true;
	if ($qui['statut']=='6forum' AND $acces['forum'] == 'oui')
		return true;
	return false;
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
		$qui['statut'] == '0minirezo' AND !$qui['restreint']
		AND autoriser('voir','groupemots',$id,$qui,$opt);
}

/**
 * Autoriser a supprimer un groupe de mots $id
 */
function autoriser_groupemots_supprimer_dist($faire, $type, $id, $qui, $opt) {
	if (!autoriser('modifier','groupemots',$id))
		return false;
	return sql_countsel('spip_mots','id_groupe='.intval($id))?false:true;
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

function autoriser_mot_creer_dist($faire, $type, $id, $qui, $opt) {
	if ($qui['statut'] != '0minirezo' OR $qui['restreint'])
		return false;

	$where = '';
	// si objet associe, verifier qu'un groupe peut etre associe
	// a la table correspondante
	if (isset($opt['associer_objet'])
	  AND $associer_objet = $opt['associer_objet']){
		if (!preg_match(',^(\w+)\|[0-9]+$,',$associer_objet,$match))
			return false;
		$where = "tables_liees REGEXP '(^|,)".$match[1]."($|,)'";
	}
	// si pas de groupe de mot qui colle, pas le droit
	if (!sql_countsel('spip_groupes_mots',$where))
		return false;

	if (isset($opt['id_groupe']))
		return autoriser('modifier','groupemots',$opt['id_groupe']);
	
	return true;
}


function autoriser_objet_editermots_dist($faire,$quoi,$id,$qui,$opts){
	// on recupere les champs du groupe s'ils ne sont pas passes en opt
	$droit = substr($GLOBALS['visiteur_session']['statut'],1);
	if (!isset($opts['groupe_champs'])){
		if (!$id_groupe = $opts['id_groupe'])
			return false;
		include_spip('base/abstract_sql');
		$opts['groupe_champs'] = sql_fetsel("*", "spip_groupes_mots", "id_groupe=".intval($id_groupe));
	}
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

// http://doc.spip.org/@autoriser_rubrique_editermots_dist
function autoriser_rubrique_editermots_dist($faire,$quoi,$id,$qui,$opts){
	return autoriser_objet_editermots_dist($faire,'rubrique',0,$qui,$opts);
}
// http://doc.spip.org/@autoriser_article_editermots_dist
function autoriser_article_editermots_dist($faire,$quoi,$id,$qui,$opts){
	return autoriser_objet_editermots_dist($faire,'article',0,$qui,$opts);
}
// http://doc.spip.org/@autoriser_breve_editermots_dist
function autoriser_breve_editermots_dist($faire,$quoi,$id,$qui,$opts){
	return autoriser_objet_editermots_dist($faire,'breve',0,$qui,$opts);
}
// http://doc.spip.org/@autoriser_syndic_editermots_dist
function autoriser_syndic_editermots_dist($faire,$quoi,$id,$qui,$opts){
	return autoriser_objet_editermots_dist($faire,'syndic',0,$qui,$opts);
}


// http://doc.spip.org/@autoriser_mot_iconifier_dist
function autoriser_mot_iconifier_dist($faire,$quoi,$id,$qui,$opts){
 return (($qui['statut'] == '0minirezo') AND !$qui['restreint']);
}

function autoriser_groupemots_iconifier_dist($faire,$quoi,$id,$qui,$opts){
 return (($qui['statut'] == '0minirezo') AND !$qui['restreint']);
}

?>
