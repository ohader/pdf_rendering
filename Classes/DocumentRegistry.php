<?php
namespace OliverHader\PdfRendering;

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
class DocumentRegistry implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * @return DocumentRegistry
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
	 * @var array
	 */
	protected $register = array();

	public function has($name) {
		return isset($this->register[$name]);
	}

	public function get($name) {
		return ($this->has($name) ? $this->register[$name] : NULL);
	}

	public function add($name, $value) {
		$this->register[$name] = $value;
	}

	public function remove($name) {
		unset($this->register[$name]);
	}

}