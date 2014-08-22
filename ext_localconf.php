<?php
defined('TYPO3_MODE') or die();

require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
	'pdf_rendering', 'Resources/Private/Php/ZendPdf/vendor/autoload.php'
);
require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(
	'pdf_rendering', 'Resources/Private/Php/PortableUtf8/functions.php'
);
?>