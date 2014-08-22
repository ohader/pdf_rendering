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
class FontViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param string $fontName
	 * @param int $fontSize
	 * @return string
	 */
	public function render($fontName = NULL, $fontSize = NULL) {
		if (!$this->hasPage()) {
			return NULL;
		}

		if ($fontName === NULL && $fontSize === NULL) {
			return $this->renderChildren();

		}

		$currentFont = $this->getVariable('currentFont');
		$currentFontName = $this->getFontName($currentFont);
		$currentFontSize = $this->getVariable('currentFontSize');

		if (($fontName === NULL || $currentFontName === $fontName) && ($fontSize === NULL || (int)$currentFontSize === (int)$fontSize)) {
			return $this->renderChildren();
		}

		if ($fontName !== NULL) {
			$font = \ZendPdf\Font::fontWithName($fontName);
			$this->setVariable('currentFont', $font, TRUE);
		} else {
			$font = $currentFont;
		}
		if ($fontSize !== NULL) {
			$this->setVariable('currentFontSize', $fontSize, TRUE);
		} else {
			$fontSize = $currentFontSize;
		}

		$identifier = uniqid('instruction', TRUE);
		$this->createInstruction($identifier)->setFont($font)->setFontSize($fontSize);
		$content = $this->wrap($this->renderChildren(), $identifier);

		if ($fontName !== NULL) {
			$this->setVariable('currentFont', $currentFont, TRUE);
		}
		if ($fontSize !== NULL) {
			$this->setVariable('currentFontSize', $currentFontSize, TRUE);
		}

		return $content;
	}

}