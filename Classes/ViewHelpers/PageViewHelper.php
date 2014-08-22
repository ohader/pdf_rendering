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
class PageViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param int $pageNumber
	 * @return void
	 */
	public function render($pageNumber) {
		$pageIndex = $pageNumber - 1;

		if (!$this->hasDocument()) {
			return;
		}

		$document = $this->getDocument();
		if ($pageNumber < 1 || $pageNumber > count($document->pages)) {
			return;
		}

		$page = $document->pages[0];
		$this->applyDefaults($page);

		$this->setPage($page);
		$this->renderChildren();
		$this->unsetPage();
	}

	protected function applyDefaults(\ZendPdf\Page $page) {
		$defaultFont = $this->getVariable('defaultFont');
		$defaultFontSize = $this->getVariable('defaultFontSize');
		$currentColor = $this->getVariable('currentColor');

		$page->setFont($defaultFont, $defaultFontSize);
		$page->setFillColor($currentColor);
	}

}