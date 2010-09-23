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
 * Interfaces des tables mots et groupes de mots pour le compilateur
 *
 * @param array $interfaces
 * @return array
 */
function mots_declarer_tables_interfaces($interfaces){

	$interfaces['table_des_tables']['mots']='mots';
	$interfaces['table_des_tables']['groupes_mots']='groupes_mots';


	$interfaces['table_date']['groupes_mots'] = 'date';
	$interfaces['table_date']['mots'] = 'date';

	$interfaces['table_titre']['mots'] = "titre, '' AS lang";

	$interfaces['tables_jointures']['spip_articles'][]= 'mots_articles';
	$interfaces['tables_jointures']['spip_articles'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_breves'][]= 'mots_breves';
	$interfaces['tables_jointures']['spip_breves'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_documents'][]= 'mots_documents';
	$interfaces['tables_jointures']['spip_documents'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_rubriques'][]= 'mots_rubriques';
	$interfaces['tables_jointures']['spip_rubriques'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_syndic'][]= 'mots_syndic';
	$interfaces['tables_jointures']['spip_syndic'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_syndic_articles'][]= 'mots_syndic';
	$interfaces['tables_jointures']['spip_syndic_articles'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_mots'][]= 'mots_articles';
	$interfaces['tables_jointures']['spip_mots'][]= 'mots_breves';
	$interfaces['tables_jointures']['spip_mots'][]= 'mots_rubriques';
	$interfaces['tables_jointures']['spip_mots'][]= 'mots_syndic';
	$interfaces['tables_jointures']['spip_mots'][]= 'mots_documents';
	
	$interfaces['tables_jointures']['spip_groupes_mots'][]= 'mots';

	$interfaces['exceptions_des_jointures']['type_mot'] = array('spip_mots', 'type');
	$interfaces['exceptions_des_jointures']['id_mot_syndic'] = array('spip_mots_syndic', 'id_mot');
	$interfaces['exceptions_des_jointures']['titre_mot_syndic'] = array('spip_mots', 'titre');
	$interfaces['exceptions_des_jointures']['type_mot_syndic'] = array('spip_mots', 'type');

	return $interfaces;
}

/**
 * Table principale spip_mots
 *
 * @param array $tables_principales
 * @return array
 */
function mots_declarer_tables_principales($tables_principales){


	$spip_mots = array(
			"id_mot"	=> "bigint(21) NOT NULL",
			"titre"	=> "text DEFAULT '' NOT NULL",
			"descriptif"	=> "text DEFAULT '' NOT NULL",
			"texte"	=> "longtext DEFAULT '' NOT NULL",
			"id_groupe"	=> "bigint(21) DEFAULT 0 NOT NULL",
			"type"	=> "text DEFAULT '' NOT NULL",
			"maj"	=> "TIMESTAMP");

	$spip_mots_key = array(
			"PRIMARY KEY"	=> "id_mot",
	);

	$tables_principales['spip_mots']     =
		array('field' => &$spip_mots, 'key' => &$spip_mots_key);

		
	$spip_groupes_mots = array(
			"id_groupe"	=> "bigint(21) NOT NULL",
			"titre"	=> "text DEFAULT '' NOT NULL",
			"descriptif"	=> "text DEFAULT '' NOT NULL",
			"texte"	=> "longtext DEFAULT '' NOT NULL",
			"unseul"	=> "varchar(3) DEFAULT '' NOT NULL",
			"obligatoire"	=> "varchar(3) DEFAULT '' NOT NULL",
			"tables_liees" => "text DEFAULT '' NOT NULL",
			# suppression des champs a faire dans la maj
			#"articles"	=> "varchar(3) DEFAULT '' NOT NULL",
			#"breves"	=> "varchar(3) DEFAULT '' NOT NULL",
			#"rubriques"	=> "varchar(3) DEFAULT '' NOT NULL",
			#"syndic"	=> "varchar(3) DEFAULT '' NOT NULL",
			"minirezo"	=> "varchar(3) DEFAULT '' NOT NULL",
			"comite"	=> "varchar(3) DEFAULT '' NOT NULL",
			"forum"	=> "varchar(3) DEFAULT '' NOT NULL",
			"maj"	=> "TIMESTAMP");

	$spip_groupes_mots_key = array(
			"PRIMARY KEY"	=> "id_groupe");
			
	$tables_principales['spip_groupes_mots'] =
		array('field' => &$spip_groupes_mots, 'key' => &$spip_groupes_mots_key);


	return $tables_principales;
}

/**
 * Table auxilaire spip_mots_xx
 *
 * @param array $tables_principales
 * @return array
 */
function mots_declarer_tables_auxiliaires($tables_auxiliaires){


	$spip_mots_articles = array(
			"id_mot"	=> "bigint(21) DEFAULT '0' NOT NULL",
			"id_article"	=> "bigint(21) DEFAULT '0' NOT NULL");

	$spip_mots_articles_key = array(
			"PRIMARY KEY"	=> "id_article, id_mot",
			"KEY id_mot"	=> "id_mot");

	$spip_mots_breves = array(
			"id_mot"	=> "bigint(21) DEFAULT '0' NOT NULL",
			"id_breve"	=> "bigint(21) DEFAULT '0' NOT NULL");

	$spip_mots_breves_key = array(
			"PRIMARY KEY"	=> "id_breve, id_mot",
			"KEY id_mot"	=> "id_mot");

	$spip_mots_rubriques = array(
			"id_mot"	=> "bigint(21) DEFAULT '0' NOT NULL",
			"id_rubrique"	=> "bigint(21) DEFAULT '0' NOT NULL");

	$spip_mots_rubriques_key = array(
			"PRIMARY KEY"	=> "id_rubrique, id_mot",
			"KEY id_mot"	=> "id_mot");

	$spip_mots_syndic = array(
			"id_mot"	=> "bigint(21) DEFAULT '0' NOT NULL",
			"id_syndic"	=> "bigint(21) DEFAULT '0' NOT NULL");

	$spip_mots_syndic_key = array(
			"PRIMARY KEY"	=> "id_syndic, id_mot",
			"KEY id_mot"	=> "id_mot");

	$spip_mots_documents = array(
			"id_mot"	=> "bigint(21) DEFAULT '0' NOT NULL",
			"id_document"	=> "bigint(21) DEFAULT '0' NOT NULL");

	$spip_mots_documents_key = array(
			"PRIMARY KEY"	=> "id_document, id_mot",
			"KEY id_mot"	=> "id_mot");

	$tables_auxiliaires['spip_mots_articles'] = array(
		'field' => &$spip_mots_articles,
		'key' => &$spip_mots_articles_key);
	$tables_auxiliaires['spip_mots_breves'] = array(
		'field' => &$spip_mots_breves,
		'key' => &$spip_mots_breves_key);
	$tables_auxiliaires['spip_mots_rubriques'] = array(
		'field' => &$spip_mots_rubriques,
		'key' => &$spip_mots_rubriques_key);
	$tables_auxiliaires['spip_mots_syndic'] = array(
		'field' => &$spip_mots_syndic,
		'key' => &$spip_mots_syndic_key);
	$tables_auxiliaires['spip_mots_documents'] = array(
		'field' => &$spip_mots_documents,
		'key' => &$spip_mots_documents_key);

	return $tables_auxiliaires;
}




/**
 * Declarer le surnom des groupes de mots
 *
 * @param array $table
 * @return array
 */
function mots_declarer_tables_objets_surnoms($table){
	$table['groupe_mots'] = 'groupes_mots'; # hum
	$table['groupe_mot'] = 'groupes_mots'; # hum
	$table['groupe'] = 'groupes_mots'; # hum (EXPOSE)
	$table['mot'] = 'mots';
	return $table;
}

?>
