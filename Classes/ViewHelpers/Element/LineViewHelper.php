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
	 * @param string $fileName
	 * @param int $fromX
	 * @param int $fromY
	 * @param int $toX
	 * @param int $toY
	 * @return void
	 */
	public function render($fromX, $fromY, $toX, $toY) {
		if (!$this->hasPage()) {
			return;
		}

		$this->getPage()->drawLine($fromX, $fromY, $toX, $toY);
	}

}