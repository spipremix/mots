<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2010                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

if (!defined("_ECRIRE_INC_VERSION")) return;

// http://doc.spip.org/@action_editer_mots_dist
function action_editer_mots_dist() {

	$securiser_action = charger_fonction('securiser_action', 'inc');
	$arg = $securiser_action();
	// arg = l'eventuel mot a supprimer pour d'eventuelles Row SQL
	if (!preg_match(',^(\d*)\D(-?\d*)\W(\w*)\W(\w*)\W(\w*)\W?(\d*)$,', $arg, $r)) 
		spip_log("action editer_mots: $arg pas compris");
	else action_editer_mots_post($r);
}

// http://doc.spip.org/@action_editer_mots_post
function action_editer_mots_post($r)
{
	$redirect = _request('redirect');
	$cherche_mot = _request('cherche_mot');
	$select_groupe = _request('select_groupe');

	list($x, $id_objet, $id_mot, $table, $table_id, $objet, $nouv_mot) = $r;
	if ($id_mot) {
		if ($objet) {
			// desassocier un/des mot d'un objet precis
			$where = array(
				"objet=" . sql_quote($objet),
				"id_objet=$id_objet"
			);
			if ($id_mot > 0) {
				$where[] = "id_mot=$id_mot";
			}
			sql_delete("spip_mots_liens", $where);
		} else {
			// disparition complete d'un mot
			// on ne doit plus passer ici mais dans action/supprimer_mot
			include_spip('action/editer_mot');
			supprimer_mot($id_mot);
		}
	}
	if ($nouv_mot ? $nouv_mot : ($nouv_mot = _request('nouv_mot'))) {
		$result = sql_countsel("spip_mots_liens", array(
			"objet=" . sql_quote($objet),
			"id_mot=".intval($nouv_mot),
			"id_objet=$id_objet"));
		if (!$result)
			sql_insertq("spip_mots_liens", array('objet'=> $objet, 'id_mot' => $nouv_mot,"id_objet" =>$id_objet));
	}

	// Notifications, gestion des revisions, reindexation...
	if ($table)
		pipeline('post_edition',
			array(
				'args' => array(
					'operation' => 'editer_mots',
					'table' => table_objet_sql($table),
					'id_objet' => $id_objet
				),
				'data' => null
			)
		);

	$redirect = rawurldecode($redirect);
	    
	if ($cherche_mot) {
		if ($p = strpos($redirect, '#')) {
			$a = substr($redirect,$p);
			$redirect = substr($redirect,0,$p);
		} else $a='';
		$redirect .= "&cherche_mot=".urlencode($cherche_mot)
			."&select_groupe=$select_groupe$a";
	}
	redirige_par_entete($redirect);
}
?>
