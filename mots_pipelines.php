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


/**
 * Definir les meta de configuration liee aux mots
 *
 * @param array $metas
 * @return array
 */
function mots_configurer_liste_metas($metas){
	$metas['articles_mots'] =  'non';
	$metas['config_precise_groupes'] =  'non';
	#$metas['mots_cles_forums'] =  'non';
	return $metas;
}




/**
 * Configuration des mots
 *
 * @param array $flux
 * @return array
 */
function mots_affiche_milieu($flux){
	static $ou = array(
		'articles'    => array('objet' => 'article'),
		'naviguer'    => array('objet' => 'rubrique', 'opt' => array('autoriser_faire' => 'publierdans')),
		'breves_voir' => array('objet' => 'breve'),
		'sites'       => array('objet' => 'syndic'),
	);
	
	if ($flux["args"]["exec"] == "configuration") {
		$configuration_mots = charger_fonction('mots', 'configuration');
		$flux["data"] .=  $configuration_mots();
	}

	// si on est sur une page ou il faut inserer les mots cles...
	if (in_array($flux['args']['exec'], array_keys($ou))) {

		$me = $ou[ $flux['args']['exec'] ];
		$objet = $me['objet']; // auteur
		$_id_objet = id_table_objet($objet); // id_auteur
		$opt = isset($me['opt']) ? $me['opt'] : array();
		
		// on récupère l'identifiant de l'objet...
		if ($id_objet = $flux['args'][ $_id_objet ]) {
			$flux['data'] .= mots_ajouter_selecteur_mots($objet, $id_objet, $exec_retour, $opt);
		}
	}
		
	return $flux;
}



/**
 * Retourne le selecteur de mots pour un objet donnee
 *
 * @param string $objet : nom de l'objet
 * @param int $id_objet : identifiant de l'objet
 * @param string $exec_retour : url de exec de retour
 * @param array $opt options
 * 		@param string $cherche_mot	un mot cherché particulier
 * 		@param string $select_groupe	un/des groupe particulier ?
 * 		@param bool $editable	autorisé ou non à voir ajouter des mots
 * 		@param string $autoriser_faire	En cas d'absence de $editable, qu'elle nom d'autorisation utiliser
 * 
 * @return string	HTML produit.
**/
function mots_ajouter_selecteur_mots($objet, $id_objet, $exec_retour='', $opt = array()) {

	if (!isset($opt['flag_editable'])) {
		$faire = isset($opt['autoriser_faire']) ? $opt['autoriser_faire'] : 'modifier';
		$opt['flag_editable'] = autoriser($faire, $objet, $id_objet);
	}
	
	// pas beau ces request ici...
	// en attendant un formulaire CVT digne de ce nom...
	if (!isset($opt['cherche_mot'])) {
		$opt['cherche_mot'] = _request('cherche_mot');
	}
	if (!isset($opt['select_groupe'])) {
		$opt['select_groupe'] = _request('select_groupe');
	}

	
	$editer_mots = charger_fonction('editer_mots', 'inc');
	return $editer_mots(
		table_objet($objet), $id_objet,
		$opt['cherche_mot'], $opt['select_groupe'],
		$opt['flag_editable'], false, $exec_retour
	);

}






/**
 * Optimiser la base de donnee en supprimant les liens orphelins
 *
 * @param int $n
 * @return int
 */
function mots_optimiser_base_disparus($flux){
	$n = &$flux['data'];


	# les liens des mots affectes a une id_rubrique inexistante
	$res = sql_select("M.id_rubrique AS id",
		      "spip_mots_rubriques AS M
		        LEFT JOIN spip_rubriques AS R
		          ON M.id_rubrique=R.id_rubrique",
			"R.id_rubrique IS NULL");

	$n+= optimiser_sansref('spip_mots_rubriques', 'id_rubrique', $res);


	# les liens de mots affectes a des articles effaces
	$res = sql_select("M.id_article AS id",
		        "spip_mots_articles AS M
		        LEFT JOIN spip_articles AS A
		          ON M.id_article=A.id_article",
			"A.id_article IS NULL");

	$n+= optimiser_sansref('spip_mots_articles', 'id_article', $res);


	# les liens de mots affectes a des breves effacees
	$res = sql_select("M.id_breve AS id",
		        "spip_mots_breves AS M
		        LEFT JOIN spip_breves AS B
		          ON M.id_breve=B.id_breve",
			"B.id_breve IS NULL");

	$n+= optimiser_sansref('spip_mots_breves', 'id_breve', $res);


	# les liens de mots affectes a des sites effaces
	$res = sql_select("M.id_syndic AS id",
		        "spip_mots_syndic AS M
		        LEFT JOIN spip_syndic AS syndic
		          ON M.id_syndic=syndic.id_syndic",
			"syndic.id_syndic IS NULL");

	$n+= optimiser_sansref('spip_mots_syndic', 'id_syndic', $res);


	//
	// Mots-cles
	//

	$result = sql_delete("spip_mots", "length(titre)=0 AND maj < $mydate");


	# les liens mots-articles sur des mots effaces
	$res = sql_select("A.id_mot AS id",
		        "spip_mots_articles AS A
		        LEFT JOIN spip_mots AS M
		          ON A.id_mot=M.id_mot",
			"M.id_mot IS NULL");

	$n+= optimiser_sansref('spip_mots_articles', 'id_mot', $res);

	# les liens mots-breves sur des mots effaces
	$res = sql_select("B.id_mot AS id",
		        "spip_mots_breves AS B
		        LEFT JOIN spip_mots AS M
		          ON B.id_mot=M.id_mot",
			"M.id_mot IS NULL");

	$n+= optimiser_sansref('spip_mots_breves', 'id_mot', $res);

	# les liens mots-rubriques sur des mots effaces
	$res = sql_select("R.id_mot AS id",
		      "spip_mots_rubriques AS R
		        LEFT JOIN spip_mots AS M
		          ON R.id_mot=M.id_mot",
			"M.id_mot IS NULL");

	$n+= optimiser_sansref('spip_mots_rubriques', 'id_mot', $res);

	# les liens mots-syndic sur des mots effaces
	$res = sql_select("S.id_mot AS id",
		        "spip_mots_syndic AS S
		        LEFT JOIN spip_mots AS M
		          ON S.id_mot=M.id_mot",
			"M.id_mot IS NULL");

	$n+= optimiser_sansref('spip_mots_syndic', 'id_mot', $res);



	return $flux;

}



/**
 * Definir la liste des champs de recherche sur la table mots 
 *
 * @param array $liste
 * @return array
 */
function mots_rechercher_liste_des_champs($liste){
	$liste['mot'] = array(
	  'titre' => 8, 'texte' => 1, 'descriptif' => 5
	);

	return $liste;
}

?>
