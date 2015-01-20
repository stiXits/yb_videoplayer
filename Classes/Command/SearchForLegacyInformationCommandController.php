<?php
	namespace TYPO3\YbVideoplayer\Command;

        class SearchForLegacyInformationCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

                /**
                * Tries to add legacy Information to existing videos
		* @param string $mappingFile Mapping of old filenames to new one as csv file
                * @param string $legacyTable where to find the legacy videos
                * @param string $legacyFileNameColumn the column that specifies the filename
                * @param string $legacyDescriptionColumn the column that specifies the description
		* @param string $legacyTitleColumn the column that specifies the title
		* @param string $legacyPreviewColumn the column that specifies the preview image
		* @param string $legacyEndColumn the column that specifies the end image
                */

		private $mappingFile;
                private $legacyTable;
                private $legacyFileNameColumn;
                private $legacyDescriptionColumn;
		private $legacyTitleColumn;
		private $legacyPreviewColumn;
		private $legacyEndColumn;

		/**
	         * videoRepository
	         *
	         * @var \TYPO3\YbVideoplayer\Domain\Repository\VideoRepository
	         * @inject
	         */
	        protected $videoRepository;


		/**
		 * Storage Reository to find filestorages
		 */
		protected $storageRepository;

		/**
		 * FileStorage for image search
		 */
		protected $fileStorage;

		/**
		 * Uitlity to instantiate objects, injection does not function with all objects in commandcontrollers
		 */
                protected $objectManager;

		/**
		 * Utility to persist Domain Objects before the end of the commandcontroller task
		 */
		protected $persistenceManager;

		protected $debug = 1;

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
                 * @param string $legacyPreviewColumn the column that specifies the preview image
                 * @param string $legacyEndColumn the column that specifies the end image
                 */
                public function SearchForLegacyInformationCommand(	$mappingFile,
									$legacyTable, 
									$legacyFileNameColumn, 
									$legacyDescriptionColumn,
									$legacyTitleColumn,
									$legacyPreviewColumn, 
									$legacyEndColumn)
		{
			try
			{
				//add legacy information
				$localVideos = $this->videoRepository->findAll()->toArray();
				//\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('all videos:', 'yb_videoplayer', 1, $localVideos);

				foreach ($localVideos as $key => &$video)
				{
					$pattern = '/(.*\/)(.*)\.(.*)/';
					$stringParts = null;
			                preg_match($pattern, $video->getFullnameidentifier(), $stringParts);
					//\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('stringparts:', 'yb_videoplayer', 1, $stringParts);

					$this->addInformationToVideo(	$video, 
									$stringParts[2], 
									$legacyTable, 
									$legacyFileNameColumn, 
									$legacyDescriptionColumn, 
									$legacyTitleColumn);

	                                //add legacy preview and end picture
        	                        $this->fileStorage = $this->storageRepository->findByUid(1);
                	                $this->addImagesToVideos(       $video,
                        	                                        $stringParts[2],
                                	                                $legacyTable,
                                        	                        $legacyFileNameColumn,
                                               	                 	$legacyPreviewColumn,
                                                       	         	$legacyEndColumn);

					$this->videoRepository->update($video);
					$this->persistenceManager->persistAll();
				}
				
			}
                        catch(\Exception $e)
                        {
                                \TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Exception ocurred:', 'yb_videoplayer', 1, array($e));
                                throw $e;
                        }
		}

		private function addInformationToVideo(	&$video, 
							$fileName, 
							$legacyTable, 
							$legacyFileNameColumn, 
							$legacyDescriptionColumn, 
							$legacyTitleColumn)
		{	
			/*\TYPO3\CMS\Core\Utility\GeneralUtility::devLog(	'searching data for:', 
										'yb_videoplayer', 
										1, 
										array(	'filename' => $fileName, 
											'table' => $legacyTable, 
											'filenameColumn' => $legacyFileNameColumn, 
											'descriptionCOlumn' => $legacyDescriptionColumn, 
											'titleColumns' => $legacyTitleColumn));*/

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(	$legacyDescriptionColumn . ', ' . $legacyTitleColumn, 
									$legacyTable,
									$legacyFileNameColumn . ' like \'%' . $fileName . '%\'');
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			if($row)
			{
				\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('found data:', 'yb_videoplayer', 1, $row);
				$video->setTitle($row[$legacyTitleColumn]);
				$video->setDescription($row[$legacyDescriptionColumn]);
			}
		}

		private function addImagesToVideos(	&$video,
							$fileName, 
							$legacyTable,
							$legacyFileNameColumn, 
							$legacyPreviewColumn, 
							$legacyEndColumn)
		{
                         /*\TYPO3\CMS\Core\Utility\GeneralUtility::devLog(	'searching images for:',
                                                                         	'yb_videoplayer',
                                                                         	1,
                                                                         	array(  'filename' => $fileName,
                                                                                	'table' => $legacyTable,
                                                                                	'filenameColumn' => $legacyFileNameColumn,
                                                                                	'previewColumn' => $legacyPreviewColumn,
                                                                                	'endColumns' => $legacyEndColumn));*/

                        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(	$legacyPreviewColumn . ', ' . $legacyEndColumn, 
									$legacyTable, 
									$legacyFileNameColumn . ' like \'%' . $fileName . '%\'');

                        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                        if($row)
                        {
				//get folder to search
				$folder = 'user_upload';
				if(!$this->fileStorage->hasFolder($folder))
                        	{
                                	/*\t3lib_div::devLog('folder not found:', 'yb_videoplayer', 1, array($folder));
                                	\t3lib_div::devLog('following folders exist::', 'yb_videoplayer', 1, $storage->getFoldersInFolder('.'));*/

                                	return;
                        	}
				$folder = $this->fileStorage->getFolder($folder);

				\t3lib_div::devLog(	'preview for:', 
							'yb_videoplayer', 
							1, 
							array('preview' => $row[$legacyPreviewColumn], 
							'end' => $row[$legacyEndColumn]));
				
				//find file-identifier for a file with specifi
				$previewImage = $this->findFileIdentifier($row[$legacyPreviewColumn]);
				$endImage = $this->findFileIdentifier($row[$legacyEndColumn]);

                                /*\TYPO3\CMS\Core\Utility\GeneralUtility::devLog( ' found identifiers:',
                                                                                'yb_videoplayer',
                                                                                1, 
                                                                                array(  $previewImage,
                                                                                        $endImage));*/

				if($previewImage && $this->fileStorage->hasFile($previewImage)){
					$previewImage = $this->getFileReference($video, $this->fileStorage->getFile($previewImage), 'preview');
					$video->setPreview($previewImage);
                                        /*\TYPO3\CMS\Core\Utility\GeneralUtility::devLog( 'preview:',
                                                                                        'yb_videoplayer',
                                                                                        1,
                                                                                        array($previewImage));*/

				}
                                if($endImage && $this->fileStorage->hasFile($endImage)){
					$endImage = $this->getFileReference($video, $this->fileStorage->getFile($endImage), 'endscreen');
                                        $video->setEndscreen($endImage);
                                	/*\TYPO3\CMS\Core\Utility\GeneralUtility::devLog( 'endscreen:',
                                        	                                    	'yb_videoplayer',
                                                                                	1,
                                                                                	array($endImage));*/

				}

                                /*\TYPO3\CMS\Core\Utility\GeneralUtility::devLog(	'video:', 
										'yb_videoplayer', 
										1, 
										array($video->getPreview()));*/
                        }
		}

		/*
		 * @param string $fileName
		 */
		private function findFileIdentifier($fileName)
		{
                        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(  'identifier',
                                                                        'sys_file',
                                                                        'name = \'' . $fileName . '\'');
                        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                        \TYPO3\CMS\Core\Utility\GeneralUtility::devLog( ' searchResult:', 
                                                                        'yb_videoplayer',
                                                                        1,
                                                                        $row);
			if(!$row)
				return false;
			return $row['identifier'];
		}

                /**
                 * helper to get a filereference object
                 * TODO: move this functionality to the Video Domain Model
                 *
		 * @param \TYPO3\YbVideoplayer\Domain\Model\Video $video
                 * @param \TYPO3\CMS\Core\Resource\FileInterface $file
                 * @return \TYPO3\YbVideoplayer\Domain\Model\Video
                 */
                protected function getFileReference(&$video, &$file, $fieldname)
                {
                        $fileRef = $this->objectManager->get('\TYPO3\YbVideoplayer\Domain\Model\FileReference');

                        $fileRef->setOriginalResource($video, $file, $fieldname);
			return $fileRef;
                }

		/**
		 * opens file, returns false if name is empty
		 * @param string $fileName
		 */ 
		protected function openFile($fileName)
		{
			if(trim($filename) == '')
			{
				log('no Mapping File given!', 'yb_videoplayer', 1, array());
				return false;
			}
			
		}

		protected function log($message, $extensionName, $level, $data)
		{
			if($this->debug)
				\TYPO3\CMS\Core\Utility\GeneralUtility::devLog( $message,
                                                                        	$extensionName,
                                                                        	$level,
                                                                        	$data);
		}


	}
?>
