<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'Ybvideoplayerfr',
	array(
#		'Playlist' => 'show',
		'Video' => 'list, show',
#		'Tag' => 'show',
		
	),
	// non-cacheable actions
	array(
	)
);

?>
