<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'globalVideoPlayer',
	array(
		'Video' => 'showGlobalPlayer',
	),
	// non-cacheable actions
	array(
	)
);

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
?>
