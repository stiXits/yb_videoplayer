<?php
namespace TYPO3\YbVideoplayer\Resource\Driver

/**
 * Driver for a mounted nfs share
 *
 * @package yb_videoplayer
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author Yannick Bäumer <yannick.baeumer@phcn.de>
 */
class NFSDriver extends \TYPO3\CMS\Core\Resource\Driver\LocalDriver
{
	/**
	 * The NFS Share publicly reachable path (usualle http://...)
	 * 
	 * @var string
	 */
	protected publicMountPath;

	public function getPublicUrl(\TYPO3\CMS\Core\Resource\ResourceInterface $fileOrFolder, $relativeToCurrentScript = FALSE) {
		$publicUrl = PathUtility::getRelativePathTo(PathUtility::dirname((PATH_site . $publicUrl))) . PathUtility::basename($publicUrl);
		//todo: replace the mounts absolute path with the mounts publicMountPath
		$this->absoluteBasePath;
		$this->publicMountPath;
		return $publicUrl;
	}
}
