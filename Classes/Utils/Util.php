<?php
namespace TYPO3\YbVideoplayer\Utils;

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

class Util
{
	/**
	 * Returrns the Files Identifier without a Prefix that is seperated be a specific character
	 * If this Character is "", no prefix will be searched
	 * @param TYPO3\CMS\Extbase\Domain\Model\File $file
	 * @return array
	 */
	public static function getPrefixFreeIdentifier(\TYPO3\CMS\Core\Resource\FileInterface $file)
	{
                $_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['yb_videoplayer']);
                $seperator = $_extConfig['prefixSeperator'];
		$prefixes = str_replace(',', '|', $_extConfig['prefixes']);
		

                if($seperator == '')
                        return $file->getIdentifier();

		// get identifier without name
		$namelessIdentifier = str_replace($file->getName(), '', $file->getIdentifier());

		$pattern = '/(' . $prefixes . ')*\\' . $seperator . '(.*)/';
		$stringParts = null;
		preg_match($pattern, $file->getName(), $stringParts);

		//prefix found
		if($stringParts[1] != '') 
                {
			return array('prefix' => $stringParts[1], 'identifier' => $namelessIdentifier . $stringParts[2]);
		}
		return array('prefix' => '', 'identifier' => $file->getIdentifier());
	}
}

?>
