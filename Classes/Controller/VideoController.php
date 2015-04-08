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
class VideoController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * videoRepository
	 *
	 * @var \TYPO3\YbVideoplayer\Domain\Repository\VideoRepository
	 * @inject
	 */
	protected $videoRepository;

        /**
         * playlistRepository
         *
         * @var \TYPO3\YbVideoplayer\Domain\Repository\PlaylistRepository
         * @inject
         */
        protected $playlistRepository;

	/**
	 * displays a given video 
	 *
	 * @param  \TYPO3\YbVideoplayer\Domain\Model\Video $video
	 * @dontvalidate $video
	 * @return void
	 */
	public function showGlobalPlayerAction($video) {
		//fetch playlists from settings
                $playlistsFromSettings = explode(',', $this->settings['playlists']);
		$_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['yb_videoplayer']);
                $seperator = $_extConfig['prefixSeperator'];

		$playlists = array();

                //cummulate all assigned playlists to one
                foreach($playlistsFromSettings as &$playlist)
                {
                        array_push($playlists, $this->playlistRepository->findByUid($playlist));
                }

		$_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['yb_videoplayer']);
		$flowplayerLicenseKey = $_extConfig['flowplayerLicenseKey'];

		$this->view->assign('resolutionSeperator', $seperator);
		$this->view->assign('flowplayerLicenseKey', $flowplayerLicenseKey);
                $this->view->assign('video', $video);
		$this->view->assign('playlists', $playlists);
	}

        /**
         * shows a video set in the plugins settings
         * @param  \TYPO3\YbVideoplayer\Domain\Model\Video $video
	 * @dontvalidate $video
         * @return void
         */
        public function showLocalPlayerAction(\TYPO3\YbVideoplayer\Domain\Model\Video $video = NULL) {
		//fetch playlists from settings
                $playlistsFromSettings = explode(',', $this->settings['playlists']);
		$_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['yb_videoplayer']);
		$seperator = $_extConfig['prefixSeperator'];
                $playlists = array();

                //cummulate all assigned playlists to one
                foreach($playlistsFromSettings as &$playlist)
                {
                        array_push($playlists, $this->playlistRepository->findByUid($playlist));
                }

		if($video == NULL)
		{
			if($this->settings['useFirstFromPlaylist'] === '1')
			{
				$video = $playlists[0]->getVideos()->current();
			}
			else
			{
				//fetch video from settings
				$video = $this->videoRepository->findByUid($this->settings['localVideo']);
			}
		}

                $_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['yb_videoplayer']);
                $flowplayerLicenseKey = $_extConfig['flowplayerLicenseKey'];

		$this->view->assign('resolutionSeperator', $seperator);
		$this->view->assign('flowplayerLicenseKey', $flowplayerLicenseKey);
                $this->view->assign('video', $video);
                $this->view->assign('playlists', $playlists);
        }

}
?>

