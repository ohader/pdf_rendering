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
class CharacterSpacingViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param float $value
	 * @return string
	 */
	public function render($value) {
		if (!$this->hasPage()) {
			return '';
		}

		$currentCharacterSpacing = $this->getVariable('currentCharacterSpacing');

		if ($currentCharacterSpacing === $value) {
			return $this->renderChildren();
		}

		$identifier = uniqid('instruction', TRUE);
		$this->createInstruction($identifier)->setCharacterSpacing($value);

		$this->setVariable('currentCharacterSpacing', $value, TRUE);
		$content = $this->wrap($this->renderChildren(), $identifier);
		$this->setVariable('currentCharacterSpacing', $currentCharacterSpacing, TRUE);

		return $content;
	}

}