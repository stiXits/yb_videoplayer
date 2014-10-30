<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_ybvideoplayer_domain_model_video'] = array (
        'ctrl' => $TCA['tx_ybvideoplayer_domain_model_video']['ctrl'],
        'interface' => array (
                'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,title,description,preview,subtitle,endscreen,files, fullnameidentifier'
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
                'files' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_video.files',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('files', array(
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
                        'config' =>  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('preview', array(
                                'appearance' => array(
                                'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:media.addFileReference'
                                ),
                        ), 'jpg,png,gif'),
                ),
                'fullnameidentifier' => array (
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_video.fullnameidentifier',
                        'config' => array (
                                'type' => 'input',
                                'size' => '40',
                                'eval' => 'trim',
				'readOnly' => 1,
                        )
                ),
               'endscreen' => array (
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_video.endscreen',
                        'config' =>  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('endscreen', array(
                                'appearance' => array(
                                'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:media.addFileReference'
                                ),
                        ), 'jpg,png,gif'),
                ),
              'subtitle' => array (
                        'exclude' => 0,
                        'label' => 'LLL:EXT:yb_videoplayer/Resources/Private/Language/locallang_db.xlf:tx_ybvideoplayer_domain_model_video.subtitle',
                        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('subtitle', array(
                                'appearance' => array(
                                'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:media.addFileReference'
                                ),
                        ), 'srt'),
                ),
	),
       'types' => array (
                '0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title;;;;2-2-2, description;;;richtext[]:rte_transform[mode=ts_css];3-3-3, preview, subtitle, files, fullnameidentifier')
        ),
        'palettes' => array (
                '1' => array('showitem' => 'starttime, endtime')
        )
);


?>

