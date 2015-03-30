<?php
	namespace TYPO3\YbVideoplayer\Command;

        class SearchForLegacyInformationCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

                /**
                * Tries to add legacy Information to existing videos
		* @param string $mappingFileName Mapping of old filenames to new one as csv file (absolute path)
                * @param string $legacyTable where to find the legacy videos
                * @param string $legacyFileNameColumn the column that specifies the filename
                * @param string $legacyDescriptionColumn the column that specifies the description
		* @param string $legacyTitleColumn the column that specifies the title
		* @param string $legacyPreviewColumn the column that specifies the preview image
		* @param string $legacyEndColumn the column that specifies the end image
		* @param int $ImageFileStoragePid Where should be searched for images
                */

		private $mappingFileName;
                private $legacyTable;
                private $legacyFileNameColumn;
                private $legacyDescriptionColumn;
		private $legacyTitleColumn;
		private $legacyPreviewColumn;
		private $legacyEndColumn;
		private $ImageFileStoragePid;

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

		protected $debugMode = 0;

		/**
		 * initializes Objects that can't be injected
		 */
   		function __construct() {
			$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\ExtBase\Object\ObjectManager');
                        $this->storageRepository = $this->objectManager->get('TYPO3\CMS\Core\Resource\StorageRepository');
			$this->persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
			$this->configurationManager = $this->objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');
                        $_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['yb_videoplayer']);
                        $this->debugMode = $_extConfig['debugMode'];
   		}

                /**
                 * execute main job
		 * @param string $mappingFileName Name of the file consisting mappinginformation (absolute path)
                 * @param string $legacyTable where to find the legacy videos
                 * @param string $legacyFileNameColumn the column that specifies the filename
                 * @param string $legacyDescriptionColumn the column that specifies the description
                 * @param string $legacyTitleColumn the column that specifies the title
                 * @param string $legacyPreviewColumn the column that specifies the preview image
                 * @param string $legacyEndColumn the column that specifies the end image
		 * @param int $ImageFileStoragePid Where should be searched for images
                 */
                public function SearchForLegacyInformationCommand(	$mappingFileName,
									$legacyTable, 
									$legacyFileNameColumn, 
									$legacyDescriptionColumn,
									$legacyTitleColumn,
									$legacyPreviewColumn, 
									$legacyEndColumn,
									$ImageFileStoragePid)
		{
			try
			{
				$this->fileStorage = $this->storageRepository->findByUid($ImageFileStoragePid);

				//initiate mappings-array
				$mappingFile = $this->openFile($mappingFileName);
				$mappingArray = false;
				if($mappingFile)
				{
					$mappingArray = array();

					while (!feof($mappingFile) && $i < 100) {
						$line = fgetcsv($mappingFile);
						if(trim($line[0]) != '' && trim($line[1] != ''))
					   		$mappingArray[$line[0]] = trim($line[1]);
					}
				}

				//add legacy information
				$localVideos = $this->videoRepository->findAll()->toArray();

				foreach ($localVideos as $key => &$video)
				{
					$pattern = '/(.*\/)(.*)\.(.*)/';
					$stringParts = null;
			                preg_match($pattern, $video->getFullnameidentifier(), $stringParts);
					$fileName = $stringParts[2] . '.' . $stringParts[3];
					if(trim($fileName) == '.')
						continue;

					if(!$this->addInformationToVideo(	$video, 
										$fileName, 
										$legacyTable, 
										$legacyFileNameColumn, 
										$legacyDescriptionColumn, 
										$legacyTitleColumn))
					{
						$this->addInformationToVideo(	$video,
	                                        				$this->verifyFileName($fileName, $mappingArray),
	                                                                        $legacyTable,
	                                                                        $legacyFileNameColumn,
	                                                                        $legacyDescriptionColumn,
	                                                                        $legacyTitleColumn);
					}

	                                //add legacy preview and end picture
					if(!$this->fileStorage)
					{
						$this->debug('Filestorage not found: ', 'yb_videoplayer', 1, array());
						$this->videoRepository->update($video);
                                        	$this->persistenceManager->persistAll();
						continue;
					}

                	                if(!$this->addImagesToVideos(	$video,
                        	        				$fileName,
                                	                                $legacyTable,
                                        	                        $legacyFileNameColumn,
                                               	                 	$legacyPreviewColumn,
                                                       	         	$legacyEndColumn))
					{
						$this->addImagesToVideos(       $video,
                                                				$this->verifyFileName($fileName, $mappingArray),
	                                                                        $legacyTable,
	                                                                        $legacyFileNameColumn,
	                                                                        $legacyPreviewColumn,
	                                                                        $legacyEndColumn);
					}

					$this->videoRepository->update($video);
					$this->persistenceManager->persistAll();
				}
				
			}
                        catch(\Exception $e)
                        {
                                $this->debug('Exception ocurred:', 'yb_videoplayer', 1, array($e));
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
			$this->debug(	'searching data for:', 
					'yb_videoplayer', 
					1, 
					array(	'filename' => $fileName, 
						'table' => $legacyTable, 
						'filenameColumn' => $legacyFileNameColumn, 
						'descriptionColumn' => $legacyDescriptionColumn, 
						'titleColumns' => $legacyTitleColumn
					)
			);

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(	$legacyDescriptionColumn . ', ' . $legacyTitleColumn . ', ' . 'crdate', 
									$legacyTable,
									$legacyFileNameColumn . ' like \'%' . $fileName . '%\'');
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			if($row)
			{
				$this->debug('found data:', 'yb_videoplayer', 1, $row);
				$video->setTitle($row[$legacyTitleColumn]);
				$video->setDescription($row[$legacyDescriptionColumn]);
				$video->setCrdate($row['crdate']);
				$this->debug('set crdate of: ' . $video->getUid(), 'yb_videoplayer', 1, array($row['crdate']));
				return true;
			}

			return false;
		}

		private function addImagesToVideos(	&$video,
							$fileName, 
							$legacyTable,
							$legacyFileNameColumn, 
							$legacyPreviewColumn, 
							$legacyEndColumn)
		{
                         $this->debug(	'searching images for:',
                         		'yb_videoplayer',
                                        1,
                                        array(  'filename' => $fileName,
                                                'table' => $legacyTable,
                                                'filenameColumn' => $legacyFileNameColumn,
                                                'previewColumn' => $legacyPreviewColumn,
                                                'endColumns' => $legacyEndColumn
					)
			);

                        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(	$legacyPreviewColumn . ', ' . $legacyEndColumn, 
									$legacyTable, 
									$legacyFileNameColumn . ' like \'%' . $fileName . '%\'');

                        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                        if($row)
                        {
				//find file-identifier for a file with specific name
				$previewImage = $this->findFileIdentifier($row[$legacyPreviewColumn]);
				$endImage = $this->findFileIdentifier($row[$legacyEndColumn]);

                                $this->debug(	'found identifiers:',
                                                'yb_videoplayer',
                                                1, 
                                                array(  $previewImage,
                                                        $endImage));

				if($previewImage && $this->fileStorage->hasFile($previewImage)){
					$previewImage = $this->getFileReference($video, $this->fileStorage->getFile($previewImage), 'preview');
					$video->setPreview($previewImage);
				}
                                if($endImage && $this->fileStorage->hasFile($endImage)){
					$endImage = $this->getFileReference($video, $this->fileStorage->getFile($endImage), 'endscreen');
                                        $video->setEndscreen($endImage);
				}

				return true;
                        }

			return false;
		}

		/*
		 * @param string $fileName
		 * TODO: add Storage id to enable mirrored filestructures on different storages
		 */
		private function findFileIdentifier($fileName)
		{
                        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(  'identifier',
                                                                        'sys_file',
                                                                        'name = \'' . $fileName . '\'');
                        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

			if(!$row)
			{
				$this->simpleDebug('File not found: ' . $fileName);
				return false;
			}
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
			if(trim($fileName) == '')
			{
				$this->simpleDebug('no Mapping File given!', 'yb_videoplayer');
			}
			else
			{
				return fopen($fileName, 'r');
			}
			return false;
		}

		/**
		 * if there is a mapping for the file name, it will be replaced with the mapped filename
		 * @param string $fileName
		 * @param array $mapping
		 * @return string eventually mapped filename
		 */
		protected function verifyFileName($fileName, $mapping)
		{
			if(array_key_exists($fileName, $mapping))
			{
				return $mapping[$fileName];
			}
			
			return $fileName;
		}

		protected function debug($message, $extensionName, $level, $data)
		{
			if($this->debugMode)
				\TYPO3\CMS\Core\Utility\GeneralUtility::devLog( $message,
                                                                        	$extensionName,
                                                                        	$level,
                                                                        	$data);
		}

		protected function simpleDebug($message)
		{
                        if($this->debugMode)
                                 \TYPO3\CMS\Core\Utility\GeneralUtility::devLog( $message,
                                                                                'yb_videoplayer',
                                                                                 1,
                                                                                 array());
		}



	}
?>
