<?php
namespace OliverHader\PdfRendering\ViewHelpers\Condition;

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
 * Class SwitchViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class SwitchViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper {

	/**
	 * An array of \TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\AbstractNode
	 * @var array
	 */
	private $childNodes = array();

	/**
	 * Setter for ChildNodes - as defined in ChildNodeAccessInterface
	 *
	 * @param array $childNodes Child nodes of this syntax tree node
	 * @return void
	 */
	public function setChildNodes(array $childNodes) {
		$this->childNodes = $childNodes;
	}

	/**
	 * @param mixed $expression
	 * @param bool $all Whether to render all child nodes (e.g. for visual debugging)
	 * @return string the rendered string
	 */
	public function render($expression, $all = FALSE) {
		$content = '';
		$this->backupSwitchState();
		$templateVariableContainer = $this->renderingContext->getViewHelperVariableContainer();

		$templateVariableContainer->addOrUpdate('TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper', 'switchExpression', $expression);
		$templateVariableContainer->addOrUpdate('TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper', 'break', FALSE);
		$templateVariableContainer->addOrUpdate('TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper', 'all', $all);

		foreach ($this->childNodes as $childNode) {
			if (
				!$childNode instanceof \TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\ViewHelperNode
				|| $childNode->getViewHelperClassName() !== 'TYPO3\CMS\Fluid\ViewHelpers\CaseViewHelper'
			) {
				continue;
			}
			$content = $childNode->evaluate($this->renderingContext);
			if ($templateVariableContainer->get('TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper', 'break') === TRUE
				&& $templateVariableContainer->get('TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper', 'all') !== TRUE) {
				break;
			}
		}

		$templateVariableContainer->remove('TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper', 'switchExpression');
		$templateVariableContainer->remove('TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper', 'break');

		$this->restoreSwitchState();
		return $content;
	}

}