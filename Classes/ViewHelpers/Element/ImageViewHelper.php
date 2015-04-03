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
 * Class ImageViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class ImageViewHelper extends AbstractDocumentViewHelper {

	const ARGUMENT_PositionLeft = 'left';
	const ARGUMENT_PositionRight = 'right';
	const ARGUMENT_PositionCenter = 'center';
	const ARGUMENT_PositionTop = 'top';
	const ARGUMENT_PositionBottom = 'bottom';

	/**
	 * @param string $fileName
	 * @param float $x
	 * @param float $y
	 * @param array $position
	 * @param float $scale
	 * @param int $resolution
	 */
	public function render($fileName, $x, $y, array $position = NULL, $scale = 1.0, $resolution = 72) {
		if (!$this->hasPage()) {
			return;
		}

		$position = $this->sanitizePosition($position);
		$fileName = GeneralUtility::getFileAbsFileName($fileName);
		$image = $this->createImage($fileName);

		$factor = $this->getResolution() / $resolution * 0.75 / 2.54;
		$width = $image->getPixelWidth() * $factor * $scale;
		$height = $image->getPixelHeight() * $factor * $scale;

		$fromX = $x;
		$fromY = $y;

		if ($position['x'] === self::ARGUMENT_PositionCenter) {
			$fromX = $x - $width / 2;
		} elseif ($position['x'] === self::ARGUMENT_PositionRight) {
			$fromX = $x - $width;
		}
		if ($position['y'] === self::ARGUMENT_PositionCenter) {
			$fromY = $y - $height / 2;
		} elseif ($position['y'] === self::ARGUMENT_PositionTop) {
			$fromY = $y - $height;
		}

		$toX = $fromX + $width;
		$toY = $fromY + $height;

		$this->getPage()->drawImage($image, $fromX, $fromY, $toX, $toY);
	}

	/**
	 * @param string $fileName
	 * @return \ZendPdf\Resource\Image\AbstractImage
	 */
	protected function createImage($fileName) {
		switch ($this->getMimeType($fileName)) {
			case 'image/png':
				return new \ZendPdf\Resource\Image\Png($fileName);
			case 'image/jpeg':
				return new \ZendPdf\Resource\Image\Jpeg($fileName);
			case 'image/tiff':
			case 'image/x-tiff':
				return new \ZendPdf\Resource\Image\Tiff($fileName);
			default:
				return \ZendPdf\Resource\Image\ImageFactory::factory($fileName);
		}
	}

	/**
	 * @param string $fileName
	 * @return bool|string
	 */
	protected function getMimeType($fileName) {
		if (function_exists('finfo_file')) {
			$fileInfo = new \finfo();
			return $fileInfo->file($fileName, FILEINFO_MIME_TYPE);
		} elseif (function_exists('mime_content_type')) {
			return mime_content_type($fileName);
		}
		return FALSE;
	}

	/**
	 * @param array $position
	 * @return array
	 */
	protected function sanitizePosition(array $position = NULL) {
		$xPositions = array(self::ARGUMENT_PositionLeft, self::ARGUMENT_PositionCenter, self::ARGUMENT_PositionRight);
		$yPositions = array(self::ARGUMENT_PositionTop, self::ARGUMENT_PositionCenter, self::ARGUMENT_PositionBottom);

		if ($position === NULL
			|| empty($position['x']) || !in_array($position['x'], $xPositions)
			|| empty($position['y']) || !in_array($position['y'], $yPositions)) {

			$position = array(
				'x' => self::ARGUMENT_PositionLeft,
				'y' => self::ARGUMENT_PositionBottom,
			);
		}

		return $position;
	}

}