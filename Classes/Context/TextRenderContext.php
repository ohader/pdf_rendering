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
use ZendPdf\Page;

/**
 * TextRenderContext
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class TextRenderContext {

	/**
	 * @return TextRenderContext
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
	 * @var array|InstructionInterface[]
	 */
	protected $instructionStack = array();

	/**
	 * @param InstructionInterface $instruction
	 */
	public function addInstruction(InstructionInterface $instruction) {
		$this->instructionStack[] = $instruction;
	}

	/**
	 * @param Page $page
	 */
	public function process(Page $page) {
		foreach ($this->instructionStack as $instruction) {
			$instruction->process($page);
		}
	}

	/**
	 * @param TextStreamContext $textStreamContext
	 */
	public function prepare(TextStreamContext $textStreamContext) {
		$this->prepareAlign($textStreamContext);
	}

	/**
	 * @param TextStreamContext $textStreamContext
	 */
	protected function prepareAlign(TextStreamContext $textStreamContext) {
		if ($textStreamContext->isAlignedLeft()) {
			return;
		}

		/** @var TextRenderInstruction[] $instructions */
		foreach ($this->arrangeTextLines() as $instructions) {
			$this->alignTextInstructions($textStreamContext, $instructions);
		}
	}

	/**
	 * @return array
	 */
	protected function arrangeTextLines() {
		$textLines = array();

		foreach ($this->instructionStack as $instruction) {
			if (!$instruction instanceof TextRenderInstruction) {
				continue;
			}

			$key = sprintf('%0.4f', $instruction->getY());
			$textLines[$key][] = $instruction;
		}

		return $textLines;
	}

	/**
	 * @param TextStreamContext $textStreamContext
	 * @param array|TextRenderInstruction[] $instructions
	 */
	protected function alignTextInstructions(TextStreamContext $textStreamContext, array $instructions) {
		$usedX = NULL;
		$maximumX = $textStreamContext->getX() + $textStreamContext->getWidth();

		foreach ($instructions as $instruction) {
			$instructionUsedX = $instruction->getX() + $instruction->getWidth();
			if ($usedX === NULL || $instructionUsedX > $usedX) {
				$usedX = $instructionUsedX;
			}
		}

		$availableWidth = $maximumX - $usedX;
		if ($availableWidth <= 0) {
			return;
		}

		if ($textStreamContext->isAlignedRight()) {
			$deltaX = $availableWidth;
		} elseif ($textStreamContext->isAlignedCenter()) {
			$deltaX = $availableWidth / 2;
		} else {
			$deltaX = 0;
		}

		foreach ($instructions as $instruction) {
			$instruction->addX($deltaX);
		}
	}

}