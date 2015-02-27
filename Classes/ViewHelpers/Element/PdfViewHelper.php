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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use OliverHader\PdfRendering\ViewHelpers\AbstractDocumentViewHelper;

/**
 * Class PdfViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class PdfViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param string $fileName
	 * @param int $x
	 * @param int $y
	 * @param int $page
	 * @return void
	 */
	public function render($fileName, $x, $y, $page = 0) {
		if (!$this->hasPage()) {
			return;
		}

		$fileName = GeneralUtility::getFileAbsFileName($fileName);
		$document = \ZendPdf\PdfDocument::load($fileName);

		/** @var \ZendPdf\Page $page */
		$page = clone $document->pages[$page];

		$this->getPage()->saveGS();
		$this->getPage()->translate($x, $y);
		$this->getPage()->drawContentStream($page->getPageDictionary()->__get('Contents'), 0,0,0,0);
		$this->getPage()->restoreGS();
	}

}