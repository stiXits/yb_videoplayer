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
	 * preview image
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	protected $preview;

        /**
         * endscreen image
         *
         * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
         */
        protected $endscreen;

        /**
         * subtitles file
         *
         * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
         */
        protected $subtitles;


	/**
	 * the videos title
	 *
	 * @var \string
	 */
	protected $title;

	/**
	 * Categories
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
	 */
	protected $categories;

	/**
	 * Adds a Category
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\Category $category
	 * @return void
	 */
	public function addCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $category) {
	    $this->categories->attach($category);
	}

	/**
	 * Removes a Category
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove The Category to be removed
	 * @return void
	 */
	public function removeCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove) {
	    $this->categories->detach($categoryToRemove);
	}
	
	/**
	 * Returns the categories
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
	 */
	public function getCategories() {
	    return $this->categories;
	}

	/**
	 * Sets the categories
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
	 * @return void
	 */
	public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories) {
	    $this->categories = $categories;
	}

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
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $preview
	 */
	public function getPreview() {
		return $this->preview;
	}

	/**
	 * Sets the preview
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $preview
	 * @return void
	 */
	public function setPreview($preview) {
		$this->preview = $preview;
	}

	/**
	 * Returns the endscreen
	 *
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $endscreen
	 */
	public function getEndscreen() {
		return $this->endscreen;
	}

	/**
	 * Sets the endscreen
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $endscreen
	 * @return void
	 */
	public function setEndscreen($endscreen) {
		$this->endscreen = $endscreen;
	}

        /**
         * Returns the subtitles
         *
         * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $subtitles
         */
        public function getSubtitles() {
                return $this->subtitles;
        }

        /**
         * Sets the subtitles
         *
         * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $subtitles
         * @return void
         */
        public function setSubtitles($subtitles) {
                $this->subtitles = $subtitles;
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

}
?>

