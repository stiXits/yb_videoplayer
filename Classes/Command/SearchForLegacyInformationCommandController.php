<?php
	namespace TYPO3\YbVideoplayer\Command;

        class SearchForLegacyInformationCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

                /**
                * Tries to add legacy Information to existing videos
                * @param string $legacyTable where to find the legacy videos
                * @param string $legacyFileNameColumn the column that specifies the filename
                * @param string $legacyDescriptionColumn the column that specifies the description
		* @param string $legacyTitleColumn the column that specifies the title
                */

                private $legacyTable;
                private $legacyFileNameColumn;
                private $legacyDescriptionColumn;
		private $legacyTitleColumn;

		/**
	         * videoRepository
	         *
	         * @var \TYPO3\YbVideoplayer\Domain\Repository\VideoRepository
	         * @inject
	         */
	        protected $videoRepository;

    		/**
		*/
   		protected $configurationManager;


		/**
		 * Storage Reository to find filestorages
		 */
		protected $storageRepository;

		/**
		 * Uitlity to instantiate objects, injection does not function with all objects in commandcontrollers
		 */
                protected $objectManager;

		/**
		 * Utility to persist Domain Objects before the end of the commandcontroller task
		 */
		protected $persistenceManager;

		/**
		 * initializes Objects that can't be injected
		 */
   		function __construct() {
			$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\ExtBase\Object\ObjectManager');
                        $this->storageRepository = $this->objectManager->get('TYPO3\CMS\Core\Resource\StorageRepository');
			$this->persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
			$this->configurationManager = $this->objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');

   		}

                /**
                 * execute main job
                 * @param string $legacyTable where to find the legacy videos
                 * @param string $legacyFileNameColumn the column that specifies the filename
                 * @param string $legacyDescriptionColumn the column that specifies the description
                 * @param string $legacyTitleColumn the column that specifies the title
                 */
                public function SearchForLegacyInformationCommand($legacyTable, $legacyFileNameColumn, $legacyDescriptionColumn, $legacyTitleColumn)
                {
			try
			{
				$localVideos = $this->videoRepository->findAll()->toArray();
				\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('all videos:', 'yb_videoplayer', 1, $localVideos);
				foreach ($localVideos as $key => &$video)
				{
					$pattern = '/(.*\/)(.*)\.(.*)/';
					$stringParts = null;
			                preg_match($pattern, $video->getFullnameidentifier(), $stringParts);
					//\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('stringparts:', 'yb_videoplayer', 1, $stringParts);
					$this->addInformationToVideo($video, $stringParts[2], $legacyTable, $legacyFileNameColumn, $legacyDescriptionColumn, $legacyTitleColumn);
					$this->videoRepository->update($video);
				}
			}
                        catch(\Exception $e)
                        {
                                \TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Exception ocurred:', 'yb_videoplayer', 1, array($e));
                                throw $e;
                        }
		}

		public function addInformationToVideo(&$video, $fileName, $legacyTable, $legacyFileNameColumn, $legacyDescriptionColumn, $legacyTitleColumn)
		{	
			\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('searching data for:', 'yb_videoplayer', 1, array('filename' => $fileName, 'table' => $legacyTable, 'filenameColumn' => $legacyFileNameColumn, 'descriptionCOlumn' => $legacyDescriptionColumn, 'titleColumns' => $legacyTitleColumn));
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($legacyDescriptionColumn . ', ' . $legacyTitleColumn, $legacyTable, $legacyFileNameColumn . ' like \'%' . $fileName . '%\'');
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			if($row)
			{
				\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('found data:', 'yb_videoplayer', 1, $row);
				$video->setTitle($row[$legacyTitleColumn]);
				$video->setDescription($row[$legacyDescriptionColumn]);
			}
		}

		public function addImagesToVideos(&$video, $fileName, $legacyTable, $legacyPreviewColumn, $legacyEndColumn)
		{
			\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('searching images for:', 'yb_videoplayer', 1, array('filename' => $fileName, 'table' => $legacyTable, 'filenameColumn' => $legacyFileNameColumn, 'descriptionCOlumn' => $legacyDescriptionColumn, 'titleColumns' => $legacyTitleColumn));
                        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($legacyPreviewColumn . ', ' . $legacyEndColumn, $legacyTable, $legacyFileNameColumn . ' like \'%' . $fileName . '%\'');
                        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                        if($row)
                        {
				$fileSearchService = $this->objectManager->get('\TYPO3\CMS\Recordlist\Service\FileSearchService');
				$fileSearchService = $fileSearchService->searchFiles($folder, $filePattern, $recursive);				

                                \TYPO3\CMS\Core\Utility\GeneralUtility::devLog('found data:', 'yb_videoplayer', 1, $row);
                                $video->setTitle($row[$legacyTitleColumn]);
                                $video->setDescription($row[$legacyDescriptionColumn]);
                        }
		}


	}
?>
