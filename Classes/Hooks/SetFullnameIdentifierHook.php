<?php

class SetFullnameIdentIfierHook{

	//function processDatamap_afterDatabaseOperations($status, $table, $id, &$fieldArray, &$pObj){
		function processDatamap_afterAllOperations(&$pObj){
		//if($table == 'tx_ybvideoplayer_domain_model_video')
		{
			//not the fastest possibility, TODO: get identifier from and set hash to mysql table#	
			$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\\TYPO3\\CMS\\ExtBase\\Object\\ObjectManager');
			$videoRepository = $objectManager->get('\\TYPO3\\YbVideoplayer\\Domain\\Repository\\VideoRepository');

			//get content object id
			$datamap = $pObj->datamap['tx_ybvideoplayer_domain_model_video'];
			reset($datamap);
			$id = key($datamap);
			if (!is_numeric($id)) {
				$id = $pObj->substNEWwithIDs[$id];
			}
		
			$video = $videoRepository->findByUid($id);

			if($video->getFiles()->count() > 0)
			{
				$persistenceManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');	
				$prefixFreeIdentifier = \TYPO3\YbVideoplayer\Utils\Util::getPrefixFreeIdentifier($video->getFiles()->current()->getOriginalResource())['identifier'];
				$video->setFullnameIdentifier(sha1($prefixFreeIdentifier));
				$videoRepository->update($video);
				$persistenceManager->persistAll();
			}
		}
	}

}

?>
