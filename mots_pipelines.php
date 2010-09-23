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
	return $metas;
}




/**
 * Configuration des mots
 *
 * @param array $flux
 * @return array
 */
function mots_affiche_milieu($flux){
	if ($flux["args"]["exec"] == "configuration") {
		$configuration_mots = charger_fonction('mots', 'configuration');
		$flux["data"] .=  $configuration_mots();
	}
	return $flux;
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
