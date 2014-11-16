<?php
namespace TYPO3\YbVideoplayer\Resource\Driver;

//use TYPO3\CMS\Core\Utility\PathUtility;
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
	 * @var string $identifier
	 */
	public function getPublicUrl($identifier) {
		$publicUrl = rtrim($this->configuration['publicPath'], '/') . '/' . ltrim($identifier(), '/');
		return $publicUrl;
	}
}
?>
