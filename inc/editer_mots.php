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
include_spip('inc/actions');
include_spip('inc/mots');

// $flag indique si on est autorise a modifier l'article
// http://doc.spip.org/@inc_editer_mots_dist
function inc_editer_mots_dist($objet, $id_objet, $cherche_mot, $select_groupe, $flag, $visible = false, $url_base='') {
	$objet = objet_type($objet);
	return recuperer_fond('prive/objets/editer/liens',array('table_source'=>'mots','objet'=>$objet,'id_objet'=>$id_objet));
}

?>
