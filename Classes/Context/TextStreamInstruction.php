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
use ZendPdf\Resource\Font\AbstractFont;
use ZendPdf\Color\ColorInterface;

/**
 * TextStreamInstruction
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class TextStreamInstruction implements InstructionInterface {

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

	/**
	 * @var AbstractFont
	 */
	protected $font;

	/**
	 * @var float
	 */
	protected $fontSize;

	/**
	 * @var ColorInterface
	 */
	protected $color;

	/**
	 * @var string
	 */
	protected $text;

	/**
	 * @var float
	 */
	protected $characterSpacing;

	/**
	 * @return AbstractFont
	 */
	public function getFont() {
		return $this->font;
	}

	/**
	 * @param AbstractFont $font
	 * @return TextStreamInstruction
	 */
	public function setFont($font) {
		$this->font = $font;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getFontSize() {
		return $this->fontSize;
	}

	/**
	 * @param float $fontSize
	 * @return TextStreamInstruction
	 */
	public function setFontSize($fontSize) {
		$this->fontSize = $fontSize;
		return $this;
	}

	/**
	 * @return ColorInterface
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @param ColorInterface $color
	 * @return TextStreamInstruction
	 */
	public function setColor($color) {
		$this->color = $color;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @param string $text
	 * @return TextStreamInstruction
	 */
	public function setText($text) {
		$this->text = $text;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getCharacterSpacing() {
		return $this->characterSpacing;
	}

	/**
	 * @param float $characterSpacing
	 * @return TextStreamInstruction
	 */
	public function setCharacterSpacing($characterSpacing) {
		$this->characterSpacing = $characterSpacing;
		return $this;
	}

	/**
	 * @param \ZendPdf\Page $page
	 */
	public function process(\ZendPdf\Page $page) {
		if ($this->getFont() || $this->getFontSize()) {
			$page->setFont(
				$this->getFont(),
				$this->getFontSize()
			);
		}

		if ($this->getColor()) {
			$page->setFillColor($this->getColor());
		}

		if ($this->getCharacterSpacing() !== NULL) {
			$page->setCharacterSpacing($this->getCharacterSpacing());
		}
	}

}