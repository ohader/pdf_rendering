<?php
namespace OliverHader\PdfRendering\ViewHelpers\Initialize;

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
 * Class TotalViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class FontViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param string $fontFile
	 * @return void
	 */
	public function render($fontFile) {
		\ZendPdf\Font::fontWithPath(
			GeneralUtility::getFileAbsFileName($fontFile)
		);
	}

}