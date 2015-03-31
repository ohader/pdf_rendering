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
class CaseViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\CaseViewHelper {

	/**
	 * @param mixed $value The switch value. If it matches, the child will be rendered
	 * @param boolean $default If this is set, this child will be rendered, if none else matches
	 *
	 * @return string the contents of this view helper if $value equals the expression of the surrounding switch view helper, or $default is TRUE. otherwise an empty string
	 * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
	 */
	public function render($value = NULL, $default = FALSE) {
		$templateVariableContainer = $this->renderingContext->getViewHelperVariableContainer();

		if ($templateVariableContainer->get('TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper', 'all') === TRUE) {
			$default = TRUE;
		}

		return parent::render($value, $default);
	}

}