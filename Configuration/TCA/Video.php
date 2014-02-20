<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_ybvideoplayer_domain_model_video'] = array (
        'ctrl' => $TCA['tx_ybvideoplayer_domain_model_video']['ctrl'],
        'interface' => array (
                'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,title,description,preview,subtitle,tag,file'
        ),
        'feInterface' => $TCA['tx_ybvideoplayer_domain_model_video']['feInterface'],
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
                                'foreign_table'       => 'tx_ybvideoplayer_domain_model_video',
                                'foreign_table_where' => 'AND tx_ybvideoplayer_domain_model_video.pid=###CURRENT_PID### AND tx_ybvideoplayer_domain_model_video.sys_language_uid IN (-1,0)',
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
                'file' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_video.file',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('file', array(
               	        	'appearance' => array(
                                'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:media.addFileReference'
                        	),
                	), 'avi,flv,mp4,mpg,mov'), 
                ),
                'title' => array (
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_video.title',
                        'config' => array (
                                'type' => 'input',
                                'size' => '30',
                                'eval' => 'required,trim',
                        )
                ),
                'description' => array (
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_video.description',
                        'config' => array (
                                'type' => 'text',
                                'cols' => '30',
                                'rows' => '5',
                                'wizards' => array(
                                        '_PADDING' => 2,
                                        'RTE' => array(
                                                'notNewRecords' => 1,
                                                'RTEonly'       => 1,
                                                'type'          => 'script',
                                                'title'         => 'Full screen Rich Text Editing|Formatteret redigering i hele vinduet',
                                                'icon'          => 'wizard_rte2.gif',
                                                'script'        => 'wizard_rte.php',
                                        ),
                                ),
                        )
               ),
               'preview' => array (
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_video.preview',
                        'config' => array (
                                'type' => 'group',
                                'internal_type' => 'file',
                                'allowed' => 'gif,png,jpeg,jpg',
                                'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
                                'show_thumbs' => 1,
                                'size' => 1,
                                'minitems' => 0,
                                'maxitems' => 1,
                        )
                ),
               'subtitle' => array (
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_video.subtitle',
                        'config' => array (
                                'type' => 'group',
                                'internal_type' => 'file',
                                'allowed' => '',
                                'disallowed' => 'php,php3',
                                'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],
                                'size' => 1,
                                'minitems' => 0,
                                'maxitems' => 1,
                        )
                ),
                'tag' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_video.tag',
                        'config' => array(
                                'type' => 'select',
                                'foreign_table' => 'tx_ybvideoplayer_domain_model_video',
                                'MM' => 'tx_ybvideoplayer_video_tag_mm',
                                'size' => 10,
                                'autoSizeMax' => 30,
                                'maxitems' => 9999,
                                'multiple' => 0,
                                'wizards' => array(
                                        '_PADDING' => 1,
                                        '_VERTICAL' => 1,
                                        'edit' => array(
                                                'type' => 'popup',
                                                'title' => 'Edit',
                                                'script' => 'wizard_edit.php',
                                                'icon' => 'edit2.gif',
                                                'popup_onlyOpenIfSelected' => 1,
                                                'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                                                ),
                                        'add' => Array(
                                                'type' => 'script',
                                                'title' => 'Create new',
                                                'icon' => 'add.gif',
                                                'params' => array(
                                                        'table' => 'tx_ybvideoplayer_domain_model_video',
                                                        'pid' => '###CURRENT_PID###',
                                                        'setValue' => 'prepend'
                                                        ),
                                                'script' => 'wizard_add.php',
                                        ),
                                ),
                        ),
                ),
	),
       'types' => array (
                '0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title;;;;2-2-2, description;;;richtext[]:rte_transform[mode=ts_css];3-3-3, preview, subtitle, file, tag')
        ),
        'palettes' => array (
                '1' => array('showitem' => 'starttime, endtime')
        )
);


?>

