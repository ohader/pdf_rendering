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

	protected $x;
	protected $y;
	protected $width;

	protected $currentX;
	protected $currentY;

	/**
	 * @var array|TextStreamInstruction[]
	 */
	protected $instructions = array();

	public function getX() {
		return $this->x;
	}

	public function setX($x) {
		$this->x = $x;
		return $this;
	}

	public function getY() {
		return $this->y;
	}

	public function setY($y) {
		$this->y = $y;
		return $this;
	}

	public function getWidth() {
		return $this->width;
	}

	public function setWidth($width) {
		$this->width = $width;
		return $this;
	}

	public function getCurrentX() {
		return $this->currentX;
	}

	public function setCurrentX($currentX) {
		$this->currentX = $currentX;
		return $this;
	}

	public function getCurrentY() {
		return $this->currentY;
	}

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