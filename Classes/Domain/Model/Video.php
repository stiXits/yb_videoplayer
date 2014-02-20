<?php
namespace TYPO3\YbVideoplayer\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Yannick Bäer <stix@phcn.de>
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
class Video extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * videofile
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 * @validate NotEmpty
	 */
	protected $file;

	/**
	 * textual description of the video
	 *
	 * @var \string
	 */
	protected $description;

	/**
	 * deprecated: preview image
	 *
	 * @var \string
	 */
	protected $preview;

	/**
	 * deprecated: video path
	 *
	 * @var \string
	 */
	protected $videoid;

	/**
	 * the videos title
	 *
	 * @var \string
	 */
	protected $title;

	/**
	 * tag to categorize the video
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Tag>
	 */
	protected $tag;

#	/**
#	 * __construct
#	 *
#	 * @return Video
#	 */
#	public function __construct() {
#		//Do not remove the next line: It would break the functionality
#		$this->initStorageObjects();
#	}
#
#	/**
#	 * Initializes all ObjectStorage properties.
#	 *
#	 * @return void
#	 */
#	protected function initStorageObjects() {
#		/**
#		 * Do not modify this method!
#		 * It will be rewritten on each save in the extension builder
#		 * You may modify the constructor of this class instead
#		 */
#		$this->tag = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
#	}
#
	/**
	 * Returns the file
	 *
	 * @return @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Sets the file
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
	 * @return void
	 */
	public function setFile($file) {
		$this->filerefSet = true;
		$this->file = $file;
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
	 * Returns the preview
	 *
	 * @return \string $preview
	 */
	public function getPreview() {
		return $this->preview;
	}

	/**
	 * Sets the preview
	 *
	 * @param \string $preview
	 * @return void
	 */
	public function setPreview($preview) {
		$this->preview = $preview;
	}

	/**
	 * Returns the preview2
	 *
	 * @return \string $preview2
	 */
	public function getPreview2() {
		return $this->preview2;
	}

	/**
	 * Sets the preview2
	 *
	 * @param \string $preview2
	 * @return void
	 */
	public function setPreview2($preview2) {
		$this->preview2 = $preview2;
	}

	/**
	 * Returns the videoid
	 *
	 * @return \string $videoid
	 */
	public function getVideoid() {
		return $this->videoid;
	}

	/**
	 * Sets the videoid
	 *
	 * @param \string $videoid
	 * @return void
	 */
	public function setVideoid($videoid) {
		$this->videoid = $videoid;
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
	 * Adds a
	 *
	 * @param \TYPO3\YbVideoplayer\Domain\Model\Tag $tag
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Tag> tag
	 */
	public function addTag($tag) {
		$this->tag->attach($tag);
	}

	/**
	 * Removes a
	 *
	 * @param \TYPO3\YbVideoplayer\Domain\Model\Tag $tagToRemove The Tag to be removed
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Tag> tag
	 */
	public function removeTag($tagToRemove) {
		$this->tag->detach($tagToRemove);
	}

	/**
	 * Returns the tag
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Tag> tag
	 */
	public function getTag() {
		return $this->tag;
	}

	/**
	 * Sets the tag
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Tag> $tag
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\YbVideoplayer\Domain\Model\Tag> tag
	 */
	public function setTag(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $tag) {
		$this->tag = $tag;
	}

        /**
         * Returns the fileextension
         *
         * @return \string $extension
         */
        public function getExtension() {
		preg_match("/.*\.(.*)/", $this->getVideoId(), $extension);;
                return $extension[1];
        }

}
?>

