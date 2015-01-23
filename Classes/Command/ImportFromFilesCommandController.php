<?php
	namespace TYPO3\YbVideoplayer\Command;

	use TYPO3\CMS\Core\Resource\Collection\FolderBasedFileCollection;
	use TYPO3\CMS\Extbase\Domain\Model\File;

        class ImportFromFilesCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

                /**
                * imports Videos from a given folder to a given pid
                * @param string $folder where to find the videos
		* @param string $videoExtensions Extensions to be considered (commaseperated)
		* @param int $storagePid where to find the videofolder
                * @param int $destinationPid where to put the videos
                * @param int $creatorUid who created the new videos
                */

                private $folder;
		private $videoExtensions;
		private $storagePid;
                private $destinationPid;
                private $creatorUid;

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

		protected $debugMode = 0;

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
		 * TODO: ignore non video files
                 * queries formulardata from the database
                 * @param string $folder The name of the folder containing the videofiles to be imported
		 * @param string $videoExtensions Extensions to be considered (commaseperated)
		 * @param int $storagePid Where to find the videofolder
                 * @param int $destinationPid Where should the videos be put (site-tree)
		 * @param int $creatorUid Who shall be the owner of the videos
                 */
                public function ImportFromFilesCommand($folder, $videoExtensions, $storagePid, $destinationPid, $creatorUid)
                {
			$storage = $this->storageRepository->findByUid($storagePid);

			$videoExtensionsArray = explode(',', $videoExtensions);
			
			//in case someone used spaces...
			foreach($videoExtensionsArray as &$extension)
				$extension = trim($extension);
			
			
			if(!$storage || !$storage->hasFolder($folder))
			{
				if(!$storage)
				{
					$this->debug('storage not found:', 'yb_videoplayer', 1, array($storagePid));
					return;
				}
				$this->debug('folder not found:', 'yb_videoplayer', 1, array($folder));
				$this->debug('following folders exist::', 'yb_videoplayer', 1, $storage->getFoldersInFolder('.'));
				return;
			}
			
			try
			{

        	                $videoFiles = $storage->getFilesInFolder($storage->getFolder($folder));
                	        $videos = array();

				foreach($videoFiles as &$videoFile)
				{
					if(!in_array($videoFile->getExtension(), $videoExtensionsArray))
					{
						$this->simpleDebug('skipping unsupported file (extension not specified): ' . $videoFile->getName());
						continue;
					}

					$video = $this->allreadyImported($videoFile, $videos);
					if($video)
						$this->addResolutionToVideo($video, $videoFile);
					else {
						$video = $this->createVideoFromFile($videoFile);
						$video->setPid($destinationPid);
						$this->videoRepository->add($video);
						$videos[] = $video;
					}
				}
				$this->persistenceManager->persistAll();


				$this->applyVideoFiles($videos, $videoFiles);
			}
                        catch(\Exception $e)
                        {
                        	$this->debug('Exception ocurred:', 'yb_videoplayer', 1, array($e));
                                throw new \Exception();
                        }

		}

		/**
		 * creates Videorecord from a given file
		 * @param string $fileName
		 * @param string $identifier
		 * @return \TYPO3\YbVideoplayer\Domain\Model\Video
		 */
		protected function createVideoFromFile(\TYPO3\CMS\Core\Resource\FileInterface $file)
		{
			
			//check if file is allready imported
			$newFileIdentifier = \TYPO3\YbVideoplayer\Utils\Util::getPrefixFreeIdentifier($file);

			$video = $this->objectManager->get('\TYPO3\YbVideoplayer\Domain\Model\Video');
			$video->setTitle($file->getName());
			$video->setFullnameidentifier($newFileIdentifier['identifier']);

			return $video;
		}

		/**
		 * checks if the video has allready been imported
		 * @param \TYPO3\CMS\Core\Resource\FileInterface $file
		 * @param array $imported
		 */
		protected function allreadyImported(\TYPO3\CMS\Core\Resource\FileInterface &$file, &$imported)
		{
                        $newFileIdentifier = \TYPO3\YbVideoplayer\Utils\Util::getPrefixFreeIdentifier($file);

			//check if video has allready been imported in this run
			foreach($imported as $video)
			{
				if($newFileIdentifier['identifier'] == $video->getFullnameidentifier()) {
					return $video;
				}
			}

                        //check if file has ever been imported
                        $video = $this->videoRepository->findByfullnameidentifier($newFileIdentifier['identifier'])->getFirst();
 
                        if($video)
                        {
                                 return $video;
                        }

			return null;
		}

                /** Creates Filereferences to the files and assignes them to the videorecord
                 * @param array $videos
                 * @return \TYPO3\YbVideoplayer\Domain\Model\Video
                 */
                protected function applyVideoFiles(&$videos, &$videoFiles)
                {
			foreach($videos as $key => &$video)
			{
				$this->addFileToVideo($video, $videoFiles[$video->getTitle()]);
			}
		}

		/**
		 * adds a specified file to a specified video object
		 * TODO: move this functionality to the Video Domain Model
		 *
                 * @param \TYPO3\YbVideoplayer\Domain\Model\Video $video
                 * @param \TYPO3\CMS\Core\Resource\FileInterface $resolution
		 * @return \TYPO3\YbVideoplayer\Domain\Model\Video
		 */
		protected function addFileToVideo(&$video, &$file)
		{
                	$fileRef = $this->objectManager->get('\TYPO3\YbVideoplayer\Domain\Model\FileReference');

                        try
                        {
                        	$identifier  = \TYPO3\YbVideoplayer\Utils\Util::getPrefixFreeIdentifier($file);
                                $fileRef->setOriginalResource($video, $file, $identifier['prefix']);
                                $video->addFile($fileRef);
                                $video->setFullnameidentifier($identifier['identifier']);
                                $this->videoRepository->update($video);
                                $this->persistenceManager->persistAll();
                        }
                        catch(\Exception $e)
                        {
                                $this->debug('Exception ocurred:', 'yb_videoplayer', 1, array($e));
                        }
			
			return $video;
		}


		/**
		 * adds a new resolution, including file, to an existing video
		 * TODO: move this functionality to the Video Domain Model
		 *
		 * @param \TYPO3\YbVideoplayer\Domain\Model\Video $video
		 * @param \TYPO3\CMS\Core\Resource\FileInterface $resolution
		 * @return \TYPO3\YbVideoplayer\Domain\Model\Video
		 */
		protected function addResolutionToVideo(&$video, &$resolution)
		{
			// check if file allready exists
			$files = $video->getFiles()->toArray();
			foreach ($files as $key => &$file)
			{
				if($resolution->getIdentifier() == $file->getOriginalResource()->getIdentifier())
					return $video;
			}
			$this->addFileToVideo($video, $resolution);	
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
