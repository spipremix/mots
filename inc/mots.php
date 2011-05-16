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

if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/presentation');
include_spip('inc/actions');
include_spip('base/abstract_sql');

function filtre_objets_associes_mot_dist($id_mot,$id_groupe) {
	static $occurences = array();

	// calculer tous les liens du groupe d'un coup
	if (!isset ($occurences[$id_groupe]))
		$occurrences[$id_groupe] = calculer_utilisations_mots($id_groupe);

	$associes = array();
	foreach (array('article','breve','site','rubrique') as $type) {
		$table = table_objet($type);
		$nb = (isset($occurrences[$id_groupe][$table][$id_mot]) ? $occurrences[$id_groupe][$table][$id_mot] : 0);
		if ($nb)
			$associes[] = objet_afficher_nb($nb,$type);
	}

	$associes = pipeline('afficher_nombre_objets_associes_a',array('args'=>array('objet'=>'mot','id_objet'=>$id_mot),'data'=>$associes));
	return $associes;

}

/**
 * Calculer les nombres d'elements (articles, etc.) lies a chaque mot
 *
 * @param int $id_groupe
 * @return array
 */
function calculer_utilisations_mots($id_groupe)
{
	$statuts = sql_in('O.statut',  ($GLOBALS['connect_statut'] =="0minirezo")  ? array('prepa','prop','publie') : array('prop','publie'));
	$retour = array();
	$objets = sql_allfetsel('DISTINCT objet', array('spip_mots_liens AS L', 'spip_mots AS M'), array('L.id_mot=M.id_mot', 'M.id_groupe='.$id_groupe));

	foreach($objets as $o) {
		$objet=$o['objet'];
		$_id_objet = id_table_objet($objet);
		$table_objet = table_objet($objet);
		$table_objet_sql = table_objet_sql($objet);
		$res = sql_allfetsel(
			"COUNT(*) AS cnt, L.id_mot",
			"spip_mots_liens AS L
				LEFT JOIN spip_mots AS M ON L.id_mot=M.id_mot
					AND L.objet=" . sql_quote($objet) . "
				LEFT JOIN " . $table_objet_sql . " AS O ON L.id_objet=O.$_id_objet" ,
			"M.id_groupe=$id_groupe AND $statuts",
			"L.id_mot");

		foreach($res as $row) {
			$retour[$table_objet][$row['id_mot']] = $row['cnt'];
		}
	}

	return $retour;
}
?>
