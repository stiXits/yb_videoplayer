<?php

class SetFullnameIdentIfierHook{

	function processDatamap_afterDatabaseOperations($status, $table, $id, &$fieldArray, &$pObj){
		if($table == 'tx_ybvideoplayer_domain_model_video')
		{
			//not the fastest possibility, TODO: get identifier from and set hash to mysql table#	
			$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\\TYPO3\\CMS\\ExtBase\\Object\\ObjectManager');
			$videoRepository = $objectManager->get('\\TYPO3\\YbVideoplayer\\Domain\\Repository\\VideoRepository');
			$video = $videoRepository->findByUid($id);

			if($video->getFile())
			{
				$persistenceManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
				$video->setFullnameIdentifier(sha1($video->getFile()->getOriginalResource()->getIdentifier()));
				$videoRepository->update($video);
				$persistenceManager->persistAll();
			}
		}
	}

}

?>
