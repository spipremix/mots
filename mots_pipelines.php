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
	
	if ($flux["args"]["exec"] == "configurer_contenu") {
		$flux["data"] .=  recuperer_fond('prive/squelettes/inclure/configurer',array('configurer'=>'configurer_mots'));
	}

	// si on est sur une page ou il faut inserer les mots cles...
	if (in_array($flux['args']['exec'], array_keys($ou))) {

		$me = $ou[ $flux['args']['exec'] ];
		$objet = $me['objet']; // auteur
		$_id_objet = id_table_objet($objet); // id_auteur
		$opt = isset($me['opt']) ? $me['opt'] : array();
		
		// on récupère l'identifiant de l'objet...
		if ($id_objet = $flux['args'][ $_id_objet ]) {
			$flux['data'] .= mots_ajouter_selecteur_mots($objet, $id_objet, $flux['args']['exec'], $opt);
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

	$result = sql_delete("spip_mots", "length(titre)=0 AND maj < $mydate");

	include_spip('action/editer_liens');
	// optimiser les liens morts :
	// entre mots vers des objets effaces
	// depuis des mots effaces
	$n+= objet_optimiser_liens(array('mot'=>'*'),'*');

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


/**
 * Definir la liste des champs de jointure sur la table mots 
 *
 * @param array $liste
 * @return array
 */
function mots_rechercher_liste_des_jointures($liste) {
	$jointures = array(
		'article' => array(
			'mot' => array('titre' => 3),
		),
		'breve' => array(
			'mot' => array('titre' => 3),
		),
		'rubrique' => array(
			'mot' => array('titre' => 3),
		),
		'document' => array(
			'mot' => array('titre' => 3)
		)
	);
	
	return array_merge_recursive($liste, $jointures);
}


/**
 * Copier le type des groupes sur la table spip_mots
 * a chaque changement d'un groupe.
 *
 * @param array $flux
 * @return array
 */
function mots_post_edition($flux){
	if (($flux['args']['table'] == 'spip_groupes_mots')
		and isset($flux['data']['titre']))
	{
		sql_updateq('spip_mots', array('type' => $flux['data']['titre']),
			'id_groupe=' . $flux['args']['id_objet']);
	}

	return $flux;
}


/**
 * Permet des calculs de noms d'url sur les mots. 
 *
 * @param array $array liste des objets acceptant des urls
 * @return array
**/
function mots_declarer_url_objets($array){
	$array[] = 'mot';
	return $array;
}

/**
 * Libelle des logos de mot et de groupe
 * @param <type> $logo_libelles
 * @return string
 */
function mots_libeller_logo($logo_libelles) {
	$logo_libelles['mot'] = _T('logo_mot_cle').aide("breveslogo");
	$logo_libelles['groupe'] = _T('logo_groupe').aide("breveslogo");
	return $logo_libelles;
}
?>
