<?php
	namespace TYPO3\YbVideoplayer\Command;

	use TYPO3\CMS\Core\Resource\Collection\FolderBasedFileCollection;
	use TYPO3\CMS\Extbase\Domain\Model\File;

        class ImportFromFilesCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

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
                 * videoRepository
                 *
                 * @var \TYPO3\CMS\Core\Resource\FileRepository
                 * @inject
                 */
                protected $fileRepository;

                /**
                * imports Videos from a given folder to a given pid
                * @param string $folder where to find the videos
                * @param int $destinationPid where to put the videos
		* @param int $creatorUid who created the new videos
                */

                /**
                * queries formulardata from the database
                * @param string $folder
                * @param int $destinationPid
		* @param int $creatorUid
                */
                public function ImportFromFilesCommand($folder, $destinationPid, $creatorUid)
                {
			\t3lib_div::devLog('entered command', 'yb_videoplayer', 1, array($folder, $destinationPid, $creatorUid));

			$storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
			$storage = $storageRepository->findByUid(1);
			
			if(!$storage->hasFolder($folder))
			{
				\t3lib_div::devLog('folder not found:', 'yb_videoplayer', 1, array($folder));
				return;
			}
			
			//use getFilesInFolder when migrating to 6.2
			$files = $storage->getFileList($folder);
			\t3lib_div::devLog('loaded files:', 'yb_videoplayer', 1, $files);

			$videos = array();

			foreach($files as $file)
			{
				$videos[] = $this->createVideoFromFile($file['name'], $file['identifier']);
			}

			\t3lib_div::devLog('created videos:', 'yb_videoplayer', 1, $videos);
		}

		/* creates Videorecord from a given file
		* @param string $fileName
		* @param string $identifier
		* @return \TYPO3\YbVideoplayer\Domain\Model\Video
		*/
		protected function createVideoFromFile($fileName, $identifier)
		{
			\t3lib_div::devLog('creating video record for:', 'yb_videoplayer', 1, array($fileName));

			//check if file is allready imported
			/*$video = $this->fileRepository->findByIdentifier($identifier);
			if($video)
			{
				\t3lib_div::devLog('video allready migrated:', 'yb_videoplayer', 1, $video->getName());
				return $video;
			}*/
			
			$video = new \TYPO3\YbVideoplayer\Domain\Model\Video();
			
			return $video;
		}
	}
?>
