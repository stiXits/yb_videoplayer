<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'videocontainer',
	array(
		'Video' => 'show',
	),
	// non-cacheable actions
	array(
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'TYPO3.' . $_EXTKEY,
        'playlistcontainer',
        array(
               'Playlist' => 'list',

        ),
        // non-cacheable actions
        array(
        )
);


?>
