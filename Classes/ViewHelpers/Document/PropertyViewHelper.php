<?php
namespace OliverHader\PdfRendering\ViewHelpers\Document;

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
 * Class PropertyViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class PropertyViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param string $author
	 * @param string $title
	 * @return void
	 */
	public function render($author = NULL, $title = NULL) {
		if (!$this->hasDocument()) {
			return;
		}

		if ($author !== NULL) {
			$this->getDocument()->properties['Author'] = $author;
		}
		if ($title !== NULL) {
			$this->getDocument()->properties['Title'] = $title;
		}
	}

}