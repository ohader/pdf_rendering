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

use OliverHader\PdfRendering\Context\TextStreamInstruction;
use OliverHader\PdfRendering\Context\TextRenderInstruction;
use OliverHader\PdfRendering\ViewHelpers\AbstractDocumentViewHelper;
use OliverHader\PdfRendering\Context\TextRenderContext;
use OliverHader\PdfRendering\Context\TextStreamContext;

/**
 * Class TextViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class TextViewHelper extends AbstractDocumentViewHelper {

	const ARGUMENT_AlignLeft = 'left';
	const ARGUMENT_AlignCenter = 'center';
	const ARGUMENT_AlignRight = 'right';

	/**
	 * @param float $x
	 * @param float $y
	 * @param float $width
	 * @param string $align
	 * @return void
	 */
	public function render($x, $y, $width = NULL, $align = self::ARGUMENT_AlignLeft) {
		if (!$this->hasPage()) {
			return;
		}

		if ($width === NULL) {
			$width = $this->getPage()->getWidth();
		}

		$align = $this->sanitizeAlign($align);

		$textRenderContext = TextRenderContext::create();
		$textStreamContext = TextStreamContext::create()
			->setX($x)->setY($y)->setWidth($width)
			->setCurrentX($x)->setCurrentY($y)
			->setAlign($align);

		$this->templateVariableContainer->add('textRenderContext', $textRenderContext);
		$this->templateVariableContainer->add('textStreamContext', $textStreamContext);

		$this->processChildren();
		$textRenderContext->prepare($textStreamContext);
		$textRenderContext->process($this->getPage());

		$this->templateVariableContainer->remove('textStreamContext');
		$this->templateVariableContainer->remove('textRenderContext');
	}

	protected function processChildren() {
		$content = trim($this->renderChildren());
		$content = preg_replace('#[\s]+#', ' ', $content);

		$textStreamContext = $this->getTextStreamContext();

		$instructions = array();
		$chunks = explode('{instruction', $content);

		foreach ($chunks as $chunk) {
			if ($chunk === '') {
				continue;
			}

			$indicator = strstr($chunk, '}', TRUE);
			$identifier = 'instruction' . $indicator;

			// No instruction found
			if (!$textStreamContext->hasInstruction($identifier)) {
				$this->renderContent($chunk);
			// Already processed (closing instruction)
			} elseif (isset($instructions[$identifier])) {
				$this->processInstruction($instructions[$identifier]);
				$chunkContent = substr($chunk, strlen($indicator) + 1);
				$this->renderContent($chunkContent);
			// Starting new instruction
			} else {
				$instructions[$identifier] = $this->processInstruction($textStreamContext->getInstruction($identifier));
				$chunkContent = substr($chunk, strlen($indicator) + 1);
				$this->renderContent($chunkContent);
			}
		}

		return $content;
	}

	/**
	 * @param TextStreamInstruction $instruction
	 * @return TextStreamInstruction
	 */
	protected function processInstruction(TextStreamInstruction $instruction) {
		$page = $this->getPage();

		$revertInstruction = TextStreamInstruction::create();

		if ($instruction->getFont() || $instruction->getFontSize()) {
			$revertInstruction->setFont($page->getFont())->setFontSize($page->getFontSize());
		}

		if ($instruction->getColor()) {
			$revertInstruction->setColor($this->getVariable('currentColor'));
			$this->setVariable('currentColor', $instruction->getColor());
		}

		if ($instruction->getCharacterSpacing() !== NULL) {
			$revertInstruction->setCharacterSpacing($this->getVariable('currentCharacterSpacing'));
			$this->setVariable('currentCharacterSpacing', $instruction->getCharacterSpacing());
		}

		$this->getTextRenderContext()->addInstruction($instruction);
		$instruction->process($page);

		return $revertInstruction;
	}

	/**
	 * @param string $content
	 */
	protected function renderContent($content) {
		if ($content === NULL || $content === FALSE || $content === '') {
			return;
		}

		$textStreamContext = $this->getTextStreamContext();

		$line = '';
		$usedWidth = 0;
		$words = preg_split('#([\s,.-])#', $content, NULL, PREG_SPLIT_DELIM_CAPTURE);

		$offsetX = $textStreamContext->getX();
		$currentX = $textStreamContext->getCurrentX();
		$currentY = $textStreamContext->getCurrentY();
		$width = $textStreamContext->getWidth();
		$availableWidth = $width + $offsetX - $currentX;

		foreach ($words as $word) {
			if ($word === '') {
				continue;
			}

			$wordWidth = $this->calculateWordWidth($word);

			if ($usedWidth + $wordWidth > $availableWidth) {
				if ($line !== '') {
					$this->drawText($line, $currentX, $currentY, $usedWidth);
					$line = '';
				}
				$usedWidth = 0;
				$currentX = $textStreamContext->getX();
				$currentY -= $this->getLineHeight();
				$availableWidth = $width + $offsetX - $currentX;

				$word = ltrim($word);
				$wordWidth = $this->calculateWordWidth($word);
			}

			$line .= $word;
			$usedWidth += $wordWidth;
		}

		if ($line !== '' && $usedWidth) {
			$this->drawText($line, $currentX, $currentY, $usedWidth);
			$currentX += $usedWidth;
		}

		$textStreamContext->setCurrentX($currentX)->setCurrentY($currentY);
	}

	/**
	 * @param string $text
	 * @param float $x
	 * @param float $y
	 * @param float $width
	 */
	protected function drawText($text, $x, $y, $width) {
		$textRenderInstruction = TextRenderInstruction::create()
			->setText($text)->setX($x)->setY($y)->setWidth($width);
		$this->getTextRenderContext()->addInstruction($textRenderInstruction);
	}

	/**
	 * @return float
	 */
	protected function getLineHeight() {
		$page = $this->getPage();
		$font = $page->getFont();
		$fontSize = $page->getFontSize();
		return ($font->getLineHeight() + $font->getLineGap()) / $font->getUnitsPerEm() * $fontSize;
	}

	/**
	 * @param string $text
	 * @return float|int
	 */
	protected function calculateWordWidth($text) {
		$page = $this->getPage();
		$font = $page->getFont();
		$fontSize = $page->getFontSize();

		$width = 0;
		foreach (preg_split('/(?<!^)(?!$)/u', $text) as $character) {
			$characterWidth = $font->widthForGlyph(
				$font->glyphNumberForCharacter(utf8_ord($character))
			);
			$width += $characterWidth / $font->getUnitsPerEm() * $fontSize;
		}
		return $width;
	}

	/**
	 * @param string $align
	 * @return string
	 */
	protected function sanitizeAlign($align) {
		$alignments = array(
			self::ARGUMENT_AlignLeft,
			self::ARGUMENT_AlignCenter,
			self::ARGUMENT_AlignRight
		);

		if (!in_array($align, $alignments)) {
			$align = self::ARGUMENT_AlignLeft;
		}

		return $align;
	}

}