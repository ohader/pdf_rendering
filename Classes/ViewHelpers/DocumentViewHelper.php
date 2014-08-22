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
	 * @return NULL|string
	 */
	public function render($fileName, $defaultFont = NULL, $defaultFontSize = NULL, array $defaultColor = NULL) {
		$fileName = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($fileName);

		if (!file_exists($fileName)) {
			return '';
		}

		$this->initializeDefaultFont($defaultFont);
		$this->initializeDefaultFontSize($defaultFontSize);
		$this->initializeDefaultColor($defaultColor);

		$document = \ZendPdf\PdfDocument::load($fileName);

		$this->setDocument($document);
		$this->renderChildren();
		$this->unsetDocument();
		$this->unsetDefaults();

		return $document->render();
	}

	/**
	 * @return NULL|\ZendPdf\PdfDocument
	 */
	protected function getResponseDocument() {
		$responseDocument = NULL;
		if ($this->templateVariableContainer->exists('documentIdentifier')) {
			$documentIdentifier = $this->templateVariableContainer->get('documentIdentifier');
			$responseDocument = \OliverHader\PdfRendering\DocumentRegistry::create()->get($documentIdentifier);
		}
		return $responseDocument;
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

	protected function unsetDefaults() {
		$this->unsetVariable('defaultFont');
		$this->unsetVariable('currentFont');
		$this->unsetVariable('defaultFontSize');
		$this->unsetVariable('currentFontSize');
		$this->unsetVariable('defaultColor');
		$this->unsetVariable('currentColor');
	}

}