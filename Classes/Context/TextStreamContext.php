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
 * TextStreamContext
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class TextStreamContext {

	/**
	 * @return TextStreamContext
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
	 * @var float
	 */
	protected $currentX;

	/**
	 * @var float
	 */
	protected $currentY;

	/**
	 * @var array|TextStreamInstruction[]
	 */
	protected $instructions = array();

	/**
	 * @return float
	 */
	public function getX() {
		return $this->x;
	}

	/**
	 * @param float $x
	 * @return TextStreamContext
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
	 * @return TextStreamContext
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
	 * @return TextStreamContext
	 */
	public function setWidth($width) {
		$this->width = $width;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getCurrentX() {
		return $this->currentX;
	}

	/**
	 * @param float $currentX
	 * @return TextStreamContext
	 */
	public function setCurrentX($currentX) {
		$this->currentX = $currentX;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getCurrentY() {
		return $this->currentY;
	}

	/**
	 * @param float $currentY
	 * @return TextStreamContext
	 */
	public function setCurrentY($currentY) {
		$this->currentY = $currentY;
		return $this;
	}

	/**
	 * @param string $identifier
	 * @param TextStreamInstruction $instruction
	 */
	public function setInstruction($identifier, TextStreamInstruction $instruction) {
		$this->instructions[$identifier] = $instruction;
	}

	/**
	 * @param string $identifier
	 * @return bool
	 */
	public function hasInstruction($identifier) {
		return isset($this->instructions[$identifier]);
	}

	/**
	 * @param string $identifier
	 * @return TextStreamInstruction
	 */
	public function getInstruction($identifier) {
		return $this->instructions[$identifier];
	}

	/**
	 * @return array|TextStreamInstruction[]
	 */
	public function getInstructions() {
		return $this->instructions;
	}

}