<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if(isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']) == false) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'] = array();
}
 
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'TYPO3\\YbVideoplayer\\Command\\ImportFromFilesCommandController';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'TYPO3\\YbVideoplayer\\Command\\SearchForLegacyInformationCommandController';


$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:yb_videoplayer/Classes/Hooks/SetFullnameIdentifierHook.php:SetFullnameIdentifierHook';


/*\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'globalVideoPlayer',
	array(
		'Video' => 'showGlobalPlayer',
	),
	// non-cacheable actions
	array(
	)
);*/

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'TYPO3.' . $_EXTKEY,
        'localVideoPlayer',
        array(
                'Video' => 'showLocalPlayer',
        ),
        // non-cacheable actions
        array(
        )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'TYPO3.' . $_EXTKEY,
        'videoListContainer',
        array(
               'Playlist' => 'videolist',

        ),
        // non-cacheable actions
        array(
        )
);

$TYPO3_CONF_VARS['SYS']['fal']['registeredDrivers']['NFS'] = array(
        'class' => 'TYPO3\YbVideoplayer\Resource\Driver\NFSDriver',
        'label' => 'NFS',
        'flexFormDS' => 'FILE:EXT:yb_videoplayer/Configuration/FlexForm/NFSDriverFlexForm.xml'
);

$TYPO3_CONF_VARS['SYS']['fal']['registeredDrivers']['Stream'] = array(
        'class' => 'TYPO3\YbVideoplayer\Resource\Driver\NFSDriver',
        'label' => 'Stream',
        'flexFormDS' => 'FILE:EXT:yb_videoplayer/Configuration/FlexForm/NFSDriverFlexForm.xml',
	'shortName' => 'Stream'
);
?>
