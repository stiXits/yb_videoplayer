<?php
namespace TYPO3\YbVideoplayer\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Yannick Bäumer <stix@phcn.de>
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package yb_videoplayer
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Playlist extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

        /**
         * videoRepository
         *
         * @var \TYPO3\YbVideoplayer\Domain\Repository\VideoRepository
         */
        protected $videoRepository;

	/**
	* @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	*/
	protected $objectManager;

	/**
	 * textual description of the playlist
	 *
	 * @var \string
	 */
	protected $description;

	/**
	 * the playlists name
	 *
	 * @var \string
	 */
	protected $title;

	/**
	 * Videos residing inside of a Playlist
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Video>
	 * @lazy
	 */
	protected $videos;

	/**
	 * use videos from page
	 * @var int
	 */
	protected $videopid;

	/**
	 * used for lazy initialization of videos if videopid is set
	 * @var int
	 */
	protected $initializedVideoid;


	/**
	 * __construct
	 *
	 * @return Playlist
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * lazy initialization of seldomly used properties
	 */
	protected function lazyInit()
	{
		if($this->objectManager == NULL)
			$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
		if($this->videoRepository == NULL)
			$this->videoRepository = $this->objectManager->get('\TYPO3\YbVideoplayer\Domain\Repository\VideoRepository');
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->videos = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the description
	 *
	 * @return \string $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the description
	 *
	 * @param \string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Returns the title
	 *
	 * @return \string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param \string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

        /**
         * Returns the videoPid
         *
         * @return int
         */
        public function getVideopid() {
                return $this->videopid;
        }
 
        /**
         * Sets the videopid
         *
         * @param int $videopid
         * @return void
         */
        public function setVideopid($videopid) {

                $this->videopid = $videopid;
        }


	/**
	 * Adds a
	 *
	 * @param \TYPO3\YbVideoplayer\Domain\Model\Video $videos
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Video> videos
	 */
	public function addVideos($videos) {
		$this->videos->attach($videos);
	}

	/**
	 * Removes a
	 *
	 * @param \TYPO3\YbVideoplayer\Domain\Model\Video $videosToRemove The Video to be removed
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Video> videos
	 */
	public function removeVideos($videosToRemove) {
		$this->videos->detach($videosToRemove);
	}

	/**
	 * Returns the videos
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Video> videos
	 */
	public function getVideos() {
		if($this->videopid != $this->initializedVideoid)
		{
			$this->lazyInit();
                	$videos = $this->fillOjectStorageFromQueryResult($this->videoRepository->findByPid($this->getVideopid()));
                        $this->setVideos($videos);
		}

		return $this->videos;
	}

	/**
	 * Sets the videos
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Video> $videos
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Video> videos
	 */
	public function setVideos(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $videos) {
		$this->videos = $videos;
	}

        /**
        * Fill objectStorage from QueryResult
        *
        * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $queryResult
        * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
        */
        protected function fillOjectStorageFromQueryResult(\TYPO3\CMS\Extbase\Persistence\QueryResultInterface $queryResult=NULL) {
                $objectStorage = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\ObjectStorage');
                if (NULL!==$queryResult) {
                        foreach($queryResult AS $object) {
                                $objectStorage->attach($object);
                        }
                }
                return $objectStorage;
        }


}
?>
