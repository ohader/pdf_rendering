<?php
namespace OliverHader\PdfRendering\ViewHelpers\Element;

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

use OliverHader\PdfRendering\ViewHelpers\AbstractDocumentViewHelper;

/**
 * Class LineViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class LineViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param float $fromX
	 * @param float $fromY
	 * @param float $toX
	 * @param float $toY
	 * @param float $lineWidth
	 * @return void
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 */
	public function render($fromX, $fromY, $toX = NULL, $toY = NULL, $lineWidth = 1.0) {
		if (!$this->hasPage()) {
			return;
		}

		// Horizontal line
		if ($toX === NULL && $toY !== NULL) {
			$toX = $fromX;
		// Vertical line
		} elseif ($toY === NULL && $toX !== NULL) {
			$toY = $fromY;
		} elseif ($toX === NULL && $toY === NULL) {
			throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception(
				'ViewHelper either needs to define one of parameters toX or toY',
				1427819652
			);
		}

		$this->getPage()->saveGS();
		$this->getPage()->setLineWidth($lineWidth);
		$this->getPage()->drawLine($fromX, $fromY, $toX, $toY);
		$this->getPage()->restoreGS();
	}

}