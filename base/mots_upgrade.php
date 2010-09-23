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
 * Installation/maj des tables mots et groupes de mots...
 *
 * @param string $nom_meta_base_version
 * @param string $version_cible
 */
function mots_upgrade($nom_meta_base_version,$version_cible){
	$current_version = 0.0;
	if (   (!isset($GLOBALS['meta'][$nom_meta_base_version]) )
			|| (($current_version = $GLOBALS['meta'][$nom_meta_base_version])!=$version_cible)){

		if ($current_version==0.0){
			include_spip('base/create');
			// creer les tables
			creer_base();
			// mettre les metas par defaut
			$config = charger_fonction('config','inc');
			$config();
			ecrire_meta($nom_meta_base_version,$current_version=$version_cible);
		}
	}
}


/**
 * Desinstallation/suppression des tables mots et groupes de mots
 *
 * @param string $nom_meta_base_version
 */
function mots_vider_tables($nom_meta_base_version) {
	sql_drop_table("spip_mots");
	sql_drop_table("spip_groupes_mots");
	sql_drop_table("spip_mots_articles");
	sql_drop_table("spip_mots_breves");
	sql_drop_table("spip_mots_rubriques");
	sql_drop_table("spip_mots_syndic");
	sql_drop_table("spip_mots_documents");

	effacer_meta($nom_meta_base_version);
}

?>
