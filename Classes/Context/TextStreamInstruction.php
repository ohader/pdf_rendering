<?php
namespace OliverHader\PdfRendering\Context;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * PageView
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class TextStreamInstruction {

	/**
	 * @return TextStreamInstruction
	 */
	static public function create() {
		return self::getObjectManager()->get(__CLASS__);
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	static protected function getObjectManager() {
		return GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Extbase\\Object\\ObjectManager'
		);
	}

	protected $font;
	protected $fontSize;
	protected $color;
	protected $text;

	public function getFont() {
		return $this->font;
	}

	public function setFont($font) {
		$this->font = $font;
		return $this;
	}

	public function getFontSize() {
		return $this->fontSize;
	}

	public function setFontSize($fontSize) {
		$this->fontSize = $fontSize;
		return $this;
	}

	public function getColor() {
		return $this->color;
	}

	public function setColor($color) {
		$this->color = $color;
		return $this;
	}

	public function getText() {
		return $this->text;
	}

	public function setText($text) {
		$this->text = $text;
		return $this;
	}

}