<?php
namespace TYPO3\YbVideoplayer\Controller;

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
class TagController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {


        /**
         * playlistRepository
         *
         * @var \TYPO3\YbVideoplayer\Domain\Repository\TagRepository
         * @inject
         */
        protected $tagRepository;


	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$tags = $this->tagRepository->findAll();
		$this->view->assign('tags', $tags);
	}

	/**
	 * action show
	 *
	 * @param \TYPO3\YbVideoplayer\Domain\Model\Tag $tag
	 * @return void
	 */
	public function showAction(\TYPO3\YbVideoplayer\Domain\Model\Tag $tag) {
		$this->view->assign('tag', $tag);
	}

	/**
	 * action new
	 *
	 * @param \TYPO3\YbVideoplayer\Domain\Model\Tag $newTag
	 * @dontvalidate $newTag
	 * @return void
	 */
	public function newAction(\TYPO3\YbVideoplayer\Domain\Model\Tag $newTag = NULL) {
		$this->view->assign('newTag', $newTag);
	}

	/**
	 * action create
	 *
	 * @param \TYPO3\YbVideoplayer\Domain\Model\Tag $newTag
	 * @return void
	 */
	public function createAction(\TYPO3\YbVideoplayer\Domain\Model\Tag $newTag) {
		$this->tagRepository->add($newTag);
		$this->flashMessageContainer->add('Your new Tag was created.');
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \TYPO3\YbVideoplayer\Domain\Model\Tag $tag
	 * @return void
	 */
	public function editAction(\TYPO3\YbVideoplayer\Domain\Model\Tag $tag) {
		$this->view->assign('tag', $tag);
	}

	/**
	 * action update
	 *
	 * @param \TYPO3\YbVideoplayer\Domain\Model\Tag $tag
	 * @return void
	 */
	public function updateAction(\TYPO3\YbVideoplayer\Domain\Model\Tag $tag) {
		$this->tagRepository->update($tag);
		$this->flashMessageContainer->add('Your Tag was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \TYPO3\YbVideoplayer\Domain\Model\Tag $tag
	 * @return void
	 */
	public function deleteAction(\TYPO3\YbVideoplayer\Domain\Model\Tag $tag) {
		$this->tagRepository->remove($tag);
		$this->flashMessageContainer->add('Your Tag was removed.');
		$this->redirect('list');
	}

}
?>
