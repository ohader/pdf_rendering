<?php
namespace OliverHader\PdfRendering\View;

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

/**
 * PageView
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class PdfView extends \TYPO3\CMS\Fluid\View\StandaloneView {

	/**
	 * @var \TYPO3\CMS\Extbase\Mvc\Response
	 * @inject
	 */
	protected $response;

	/**
	 * @return PdfView
	 */
	static public function create() {
		return self::getObjectManager()->get(__CLASS__);
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	static protected function getObjectManager() {
		return GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Extbase\\Object\\ObjectManager'
		);
	}

	/**
	 * @param string $actionName
	 * @return \ZendPdf\PdfDocument
	 */
	public function render($actionName = NULL) {
		$documentIdentifier = uniqid('document', TRUE);
		$this->assign('documentIdentifier', $documentIdentifier);
		$this->baseRenderingContext->getTemplateVariableContainer()->add('response', $this->response);

		parent::render($actionName);
		return \ZendPdf\PdfDocument::parse($this->response->getContent());
	}

}