<?php
namespace OliverHader\PdfRendering\ViewHelpers\Style;

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
class BoldViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @return string
	 */
	public function render() {
		if (!$this->hasPage()) {
			return NULL;
		}

		$currentFont = $this->getVariable('currentFont');
		$currentFontSize = $this->getVariable('currentFontSize');

		if ($currentFont->isBold()) {
			return $this->renderChildren();
		}

		$currentFontName = $this->getFontName($currentFont);
		$currentFontParts = explode('-', $currentFontName, 2);
		if (isset($currentFontParts[1])) {
			$currentFontParts[1] = 'Bold' . $currentFontParts[1];
		} else {
			$currentFontParts[1] = 'Bold';
		}

		$fontName = implode('-', $currentFontParts);
		$font = \ZendPdf\Font::fontWithName($fontName);
		$identifier = uniqid('instruction', TRUE);
		$this->createInstruction($identifier)->setFont($font)->setFontSize($currentFontSize);

		$this->setVariable('currentFont', $font, TRUE);
		$content = $this->wrap($this->renderChildren(), $identifier);
		$this->setVariable('currentFont', $currentFont, TRUE);

		return $content;
	}

}