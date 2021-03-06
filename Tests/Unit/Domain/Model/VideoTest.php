<?php

namespace TYPO3\YbVideoplayer\Tests;
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
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \TYPO3\YbVideoplayer\Domain\Model\Video.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Videoplayer
 *
 * @author Yannick Bäumer <stix@phcn.de>
 */
class VideoTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \TYPO3\YbVideoplayer\Domain\Model\Video
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \TYPO3\YbVideoplayer\Domain\Model\Video();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getFileReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setFileForStringSetsFile() { 
		$this->fixture->setFile('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getFile()
		);
	}
	
	/**
	 * @test
	 */
	public function getDescriptionReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setDescriptionForStringSetsDescription() { 
		$this->fixture->setDescription('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getDescription()
		);
	}
	
	/**
	 * @test
	 */
	public function getPreviewReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setPreviewForStringSetsPreview() { 
		$this->fixture->setPreview('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPreview()
		);
	}
	
	/**
	 * @test
	 */
	public function getPreview2ReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setPreview2ForStringSetsPreview2() { 
		$this->fixture->setPreview2('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPreview2()
		);
	}
	
	/**
	 * @test
	 */
	public function getVideoidReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setVideoidForStringSetsVideoid() { 
		$this->fixture->setVideoid('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getVideoid()
		);
	}
	
	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle() { 
		$this->fixture->setTitle('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getTitle()
		);
	}
	
	/**
	 * @test
	 */
	public function getTagReturnsInitialValueForTag() { 
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getTag()
		);
	}
	
}
?>
