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

}