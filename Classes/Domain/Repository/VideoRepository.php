<?php
namespace TYPO3\YbVideoplayer\Domain\Repository;

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
class VideoRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * @var \TYPO3\YbVideoplayer\Domain\Repository\TagRepository
	 * @inject
	*/
	protected $tagRepository;

	public function initializeObject() {
		$querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
		$querySettings->setRespectStoragePage(FALSE);
		$this->setDefaultQuerySettings($querySettings);
	}

	public function findAll()
	{
		$query = $this->createQuery();
		return $query->execute();
	}

	/*
	 *@var array of strings
	 */
	public function findAllByTags($tagnames)
	{
		$query = $this->createQuery();
		$query->matching($query->in('title', $tagnames));
		$tags = $query->execute();

		$videos = $this->findAllbyTags($tags);
		return $videos;
	}

	/*
	 * returns all Videos mentioned by one tag that can be found by title
	 * @var string $tagName
	*/
	public function findAllByTagTitle($tags)
	{
		$query = $this->createQuery();

		//prepare query to find all videos matching to the given tags
		$query->matching($query->in('tag', $tags));
		$videos = $query->execute();
		return $videos;				
	}

	public function findByPlaylist($playlist)
	{
		$query = $this->createQuery();

                //prepare query to find all videos matching to the given playlist
                $query->matching($query->equals('playlist', $playlist));
                $videos = $query->execute();
                return $videos;

	}

}
?>
