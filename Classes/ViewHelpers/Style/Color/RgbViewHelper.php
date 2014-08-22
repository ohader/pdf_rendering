<?php
namespace OliverHader\PdfRendering\ViewHelpers\Style\Color;

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
 * Class TotalViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class RgbViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param float $r
	 * @param float $g
	 * @param float $b
	 * @return string
	 */
	public function render($r, $g, $b) {
		if (!$this->hasPage()) {
			return '';
		}

		/** @var \ZendPdf\Color\Rgb $currentColor */
		$currentColor = $this->getVariable('currentColor');

		if (implode(',', $currentColor->getComponents()) === implode(',', array($r, $g, $b))) {
			return $this->renderChildren();
		}

		$color = new \ZendPdf\Color\Rgb($r, $g, $b);
		$identifier = uniqid('instruction', TRUE);
		$this->createInstruction($identifier)->setColor($color);

		$this->setVariable('currentColor', $color, TRUE);
		$content = $this->wrap($this->renderChildren(), $identifier);
		$this->setVariable('currentColor', $currentColor, TRUE);

		return $content;
	}

}