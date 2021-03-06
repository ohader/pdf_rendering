<?php
namespace OliverHader\PdfRendering\ViewHelpers;

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

/**
 * Class TotalViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class DocumentViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param string $fileName
	 * @param string $defaultFont
	 * @param string $defaultFontSize
	 * @param array $defaultColor
	 * @param float $defaultCharacterSpacing
	 * @param int $resolution
	 */
	public function render($fileName, $defaultFont = NULL, $defaultFontSize = NULL, array $defaultColor = NULL, $defaultCharacterSpacing = 0.0, $resolution = 72) {
		$fileName = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($fileName);

		if (!file_exists($fileName)) {
			return;
		}

		$this->initializeDefaultFont($defaultFont);
		$this->initializeDefaultFontSize($defaultFontSize);
		$this->initializeDefaultColor($defaultColor);
		$this->initializeDefaultStyle($defaultCharacterSpacing);
		$this->initializeResolution($resolution);

		$document = \ZendPdf\PdfDocument::load($fileName);

		$this->setDocument($document);
		$this->renderChildren();
		$this->unsetDocument();
		$this->unsetDefaults();

		$content = $document->render();
		$this->getResponse()->setContent($content);
	}

	/**
	 * @return NULL|\TYPO3\CMS\Extbase\Mvc\Response
	 */
	protected function getResponse() {
		$response = NULL;
		if ($this->templateVariableContainer->exists('response')) {
			$response = $this->templateVariableContainer->get('response');
		}
		return $response;
	}

	protected function initializeDefaultFont($defaultFont = NULL) {
		if ($defaultFont === NULL) {
			$defaultFont = \ZendPdf\Font::FONT_HELVETICA;
		}

		$font = \ZendPdf\Font::fontWithName($defaultFont);
		$this->setVariable('defaultFont', $font);
		$this->setVariable('currentFont', $font, TRUE);
	}

	protected function initializeDefaultFontSize($defaultFontSize = NULL) {
		if ($defaultFontSize === NULL) {
			$defaultFontSize = 10;
		}

		$this->setVariable('defaultFontSize', $defaultFontSize);
		$this->setVariable('currentFontSize', $defaultFontSize, TRUE);
	}

	protected function initializeDefaultColor(array $defaultColor = NULL) {
		if ($defaultColor === NULL) {
			$defaultColor = array(
				'r' => 0,
				'g' => 0,
				'b' => 0,
			);
		}

		$color = new \ZendPdf\Color\Rgb(
			$defaultColor['r'],
			$defaultColor['g'],
			$defaultColor['b']
		);

		$this->setVariable('defaultColor', $color);
		$this->setVariable('currentColor', $color, TRUE);
	}

	/**
	 * @param float $defaultCharacterSpacing
	 */
	protected function initializeDefaultStyle($defaultCharacterSpacing = 0.0) {
		$this->setVariable('defaultCharacterSpacing', $defaultCharacterSpacing);
		$this->setVariable('currentCharacterSpacing', $defaultCharacterSpacing);
	}

	/**
	 * @param int $resolution
	 */
	protected function initializeResolution($resolution) {
		$this->setResolution($resolution);
	}

	protected function unsetDefaults() {
		$this->unsetVariable('defaultFont');
		$this->unsetVariable('currentFont');
		$this->unsetVariable('defaultFontSize');
		$this->unsetVariable('currentFontSize');
		$this->unsetVariable('defaultColor');
		$this->unsetVariable('currentColor');
		$this->unsetVariable('defaultCharacterSpacing');
		$this->unsetVariable('currentCharacterSpacing');
	}

}