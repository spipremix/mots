<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP
// Fichier source, a modifier dans svn://zone.spip.org/spip-zone/_plugins_/_stable_/acces_restreint/lang/
if (!defined("_ECRIRE_INC_VERSION")) return;

$GLOBALS[$GLOBALS['idx_lang']] = array(

	'titre_formulaire_administrer_mot' => 'إدارة المفتاح',

	'bouton_associer' => 'ربط',
	'bouton_dissocier' => 'فك الربط',
	'bouton_fusionner' => 'دمج',
	'label_associer_objets_mot' => '<b>ربط</b> هذا المفتاح بالعناصر المرتبطة بالمفتاح',
	'label_dissocier_objets_mot' => '<b>فك ربط</b> هذا المفتاح بالعناصر المرتبطة بالمفتاح',
	'label_fusionner_mot' => '<b>دمج</b> بالمفتاح',

	'erreur_admin_mot_action_inconnue' => 'ماذا تريد القيام به؟',
	'erreur_selection_id' => 'اختيار مفتاح او تحديد رقمه التسلسلي في حقل الإدخال',
	'erreur_mot_cle_deja' => 'غير ممكن: هو المفتاح نفسه قيد التحرير حالياً.',

	'label_confirm_fusion' => 'لا يمكن التراجع عن هذه العملية.<br />
<strong>سيتم حذف المفتاح الحالي #@id_mot@</strong> وسيتم نقل الروابط التالية الى المفتاح #@id_mot_new@: ',
	'label_confirm_fusion_check' => 'وضع إشارة لتأكيد دكج المفتاح #@id_mot@ بالمفتاح #@id_mot_new@',

	'result_associer_ras' => 'لم يتم تنفيذ اي شيء: كل العناصر مرتبطة حالياً بهذا المفتاح',
	'result_associer_nb' => ' تم ربطها بهذا المفتاح',
	'result_dissocier_ras' => 'لم يتم تنفيذ اي شيء: لا يرتبط اي من العناصر المحددة بهذا المفتاح',
	'result_dissocier_nb' => ' تم فك ربطها بهذا المفتاح',

	'result_fusionner_ok' => 'يمكن الآن حذف هذا المفتاح: تم نقل كل الروابط الى @mot@ الآخر.',

	'result_cancel_ok' => 'تم إلغاء العملية الأخيرة.',

	'icone_administrer_mot' => 'إدارة متطورة',

	'label_mot_parent' => 'السلف:',
	'label_mot_1_enfant' => 'الولد:',
	'label_mot_nb_enfants' => 'الأولاد:',
	'placeholder_id_mot' => 'أو #ID_MOT',
	'placeholder_select' => 'اختيار…',

);

?>
