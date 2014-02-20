<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_ybvideoplayer_domain_model_tag'] = array (
        'ctrl' => $TCA['tx_ybvideoplayer_domain_model_tag']['ctrl'],
        'interface' => array (
                'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,title,description'
        ),
        'feInterface' => $TCA['tx_ybvideoplayer_domain_model_tag']['feInterface'],
        'columns' => array (
                'sys_language_uid' => array (
                        'exclude' => 1,
                        'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
                        'config' => array (
                                'type'                => 'select',
                                'foreign_table'       => 'sys_language',
                                'foreign_table_where' => 'ORDER BY sys_language.title',
                                'items' => array(
                                        array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
                                        array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
                                )
                        )
                ),
                'l10n_parent' => array (
                        'displayCond' => 'FIELD:sys_language_uid:>:0',
                        'exclude'     => 1,
                        'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
                        'config'      => array (
                                'type'  => 'select',
                                'items' => array (
                                        array('', 0),
                                ),
                                'foreign_table'       => 'tx_ybvideoplayer_domain_model_tag',
                                'foreign_table_where' => 'AND tx_ybvideoplayer_domain_model_tag.pid=###CURRENT_PID### AND tx_ybvideoplayer_domain_model_tag.sys_language_uid IN (-1,0)',
                        )
                ),
                'l10n_diffsource' => array (
                        'config' => array (
                                'type' => 'passthrough'
                        )
                ),
                'hidden' => array (
                        'exclude' => 1,
                        'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
                        'config'  => array (
                                'type'    => 'check',
                                'default' => '0'
                        )
                ),
                'starttime' => array (
                        'exclude' => 1,
                        'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
                        'config'  => array (
                                'type'     => 'input',
                                'size'     => '8',
                                'max'      => '20',
                                'eval'     => 'date',
                                'default'  => '0',
                                'checkbox' => '0'
                        )
              ),
                'endtime' => array (
                        'exclude' => 1,
                        'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
                        'config'  => array (
                                'type'     => 'input',
                                'size'     => '8',
                                'max'      => '20',
                                'eval'     => 'date',
                                'checkbox' => '0',
                                'default'  => '0',
                                'range'    => array (
                                        'upper' => mktime(3, 14, 7, 1, 19, 2038),
                                        'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
                                )
                        )
                ),
                'title' => array (
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_tag.title',
                        'config' => array (
                                'type' => 'input',
                                'size' => '30',
                        )
                ),
		'description' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_tag.description',
                        'config' => array(
                                'type' => 'text',
                                'cols' => 40,
                                'rows' => 15,
                                'eval' => 'trim',
                                'wizards' => array(
                                        'RTE' => array(
                                                'icon' => 'wizard_rte2.gif',
                                                'notNewRecords'=> 1,
                                                'RTEonly' => 1,
                                                'script' => 'wizard_rte.php',
                                                'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                                                'type' => 'script'
                                        )
                                )
                        ),
                        'defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts]',
                ),
        ),
        'types' => array (
                '0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title, description;;;;2-2-2')
        ),
        'palettes' => array (
                '1' => array('showitem' => '')
        )
);


?>
