<?php
	namespace TYPO3\YbVideoplayer\Command;

	use TYPO3\CMS\Core\Resource\Collection\FolderBasedFileCollection;
	use TYPO3\CMS\Extbase\Domain\Model\File;

        class ImportFromFilesCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

                /**
                * imports Videos from a given folder to a given pid
                * @param string $folder where to find the videos
                * @param int $destinationPid where to put the videos
                * @param int $creatorUid who created the new videos
                */

                private $folder;
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

		/**
		 * initializes Objects that can't be injected
		 */
   		function __construct() {
			\t3lib_div::devLog('constructor called', 'yb_videoplayer', 1, array());
			$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\\TYPO3\\CMS\\ExtBase\\Object\\ObjectManager');
                        $this->storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
			$this->persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
			$this->configurationManager = $this->objectManager->get('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager');

   		}

                /**
                * queries formulardata from the database
                * @param string $folder
                * @param int $destinationPid
		* @param int $creatorUid
                */
                public function ImportFromFilesCommand($folder, $destinationPid, $creatorUid)
                {
			\t3lib_div::devLog('entered command', 'yb_videoplayer', 1, array($folder, $destinationPid, $creatorUid));
			$storage = $this->storageRepository->findByUid(1);
			
			if(!$storage->hasFolder($folder))
			{
				\t3lib_div::devLog('folder not found:', 'yb_videoplayer', 1, array($folder));
				return;
			}
			
			//use getFilesInFolder when migrating to 6.2
			$videoFiles = $storage->getFileList($folder);
			$videos = array();
			\t3lib_div::devLog('loaded files:', 'yb_videoplayer', 1, $videoFiles);

			foreach($videoFiles as &$videoFile)
			{
				$videoFile = $storage->getFile($videoFile['identifier']);
				
				try
				{
					$video = $this->createVideoFromFile($videoFile);
				}
                                catch(\Exception $e)
                                {
                                        \t3lib_div::devLog('Exception ocurred:', 'yb_videoplayer', 1, array($e));
					\t3lib_div::devLog('loaded files:', 'yb_videoplayer', 1, array($videoFiles));
					throw new \Exception();
                                }

				$video->setPid($destinationPid);
				$this->videoRepository->add($video);
				$videos[] = $video;
			}
			$this->persistenceManager->persistAll();

			\t3lib_div::devLog('loaded files:', 'yb_videoplayer', 1, $videoFiles);

			$this->applyVideoFiles($videos, $videoFiles);

			\t3lib_div::devLog('video UID:', 'yb_videoplayer', 1, array($videos[0]->getTitle(), $videos[0]->getUid()));
		}

		/* creates Videorecord from a given file
		* @param string $fileName
		* @param string $identifier
		* @return \TYPO3\YbVideoplayer\Domain\Model\Video
		*/
		protected function createVideoFromFile(\TYPO3\CMS\Core\Resource\FileInterface $file)
		{
			\t3lib_div::devLog('creating video record for:', 'yb_videoplayer', 1, array($file->getName(), $file->getUid()));

			//check if file is allready imported
			$video = $this->videoRepository->findByfullnameidentifier($file->getIdentifier())->getFirst();
			if($video)
			{
				\t3lib_div::devLog('video allready migrated:', 'yb_videoplayer', 1, array('title' => $video->getTitle(), 'uid' => $video->getUid()));
				return $video;
			}
			
			$video = $this->objectManager->get('\TYPO3\YbVideoplayer\Domain\Model\Video');
			$video->setTitle($file->getName());

			return $video;
		}

                /* Creates Filereferences to the files and assignes them to the videorecord
                * @param array $videos
                * @return \TYPO3\YbVideoplayer\Domain\Model\Video
                */
                protected function applyVideoFiles($videos, $videoFiles)
                {
			foreach($videos as $key => &$video)
			{
	                        $fileRef = $this->objectManager->get('\TYPO3\YbVideoplayer\Domain\Model\FileReference');

	                        try
	                        {
					$identifier  = \TYPO3\YbVideoplayer\Utils\Util::getPrefixFreeIdentifier($videoFiles[$video->getTitle()]);
	                                $fileRef->setOriginalResource($video, $videoFiles[$video->getTitle()], $identifier['prefix']);
	                                $video->addFile($fileRef);
					$video->setFullnameidentifier($identifier['identifier']);
					$this->videoRepository->update($video);
					$this->persistenceManager->persistAll();
	                        }
	                        catch(\Exception $e)
	                        {
	                                \t3lib_div::devLog('Exception ocurred:', 'yb_videoplayer', 1, array($e));
	                        }
			}
		}

	}
?>
