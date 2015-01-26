<?php
namespace TYPO3\YbVideoplayer\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Driver for a mounted nfs share
 *
 * @package yb_videoplayer
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author Yannick Bäumer <yannick.baeumer@phcn.de>
 */


class ReplaceViewHelper extends AbstractViewHelper {

	/**
	* @param string $search
	* @param string $replace
	* @param string $subject
	* @return string
	*/

	public function render($search, $replace = '', $subject = NULL) {
		if ($content === NULL) 
		{
			$content = $this->renderChildren();
		}

		return str_replace($search, $replace, $subject);
	}
}
