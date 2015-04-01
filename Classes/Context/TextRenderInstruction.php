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
 * TextRenderInstruction
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class TextRenderInstruction implements InstructionInterface {

	/**
	 * @return TextRenderInstruction
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
	 * @var float
	 */
	protected $x;

	/**
	 * @var float
	 */
	protected $y;

	/**
	 * @var float
	 */
	protected $width;

	/**
	 * @var string
	 */
	protected $text;

	/**
	 * @return float
	 */
	public function getX() {
		return $this->x;
	}

	/**
	 * @param float $x
	 * @return TextRenderInstruction
	 */
	public function setX($x) {
		$this->x = $x;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getY() {
		return $this->y;
	}

	/**
	 * @param float $y
	 * @return TextRenderInstruction
	 */
	public function setY($y) {
		$this->y = $y;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @param float $width
	 * @return TextRenderInstruction
	 */
	public function setWidth($width) {
		$this->width = $width;
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
	 * @return TextRenderInstruction
	 */
	public function setText($text) {
		$this->text = $text;
		return $this;
	}

	/**
	 * @param \ZendPdf\Page $page
	 */
	public function process(\ZendPdf\Page $page) {
		$page->drawText($this->getText(), $this->getX(), $this->getY());
	}

}