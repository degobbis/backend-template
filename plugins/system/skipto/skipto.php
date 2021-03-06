<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.skipto
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Skipto plugin to add accessible keyboard navigation to the site and administrator templates.
 *
 * @since  4.0.0
 */
class PlgSystemSkipto extends CMSPlugin
{
	/**
	 * Application object.
	 *
	 * @var    \Joomla\CMS\Application\CMSApplication
	 * @since  4.0.0
	 */
	protected $app;

	/**
	 * Add the CSS and JavaScript for the skipto navigation menu.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function onAfterDispatch()
	{
		$section = $this->params->get('section_skipto', 2);

		if ($section !== 3 && ($this->app->isClient('administrator') && $section !== 2 || $this->app->isClient('site') && $section !== 1))
		{
			return;
		}

		// Get the document object.
		$document = $this->app->getDocument();

		if ($document->getType() !== 'html')
		{
			return;
		}

		// Load language file.
		$this->loadLanguage();

		// Add strings for translations in JavaScript.
		$document->addScriptOptions(
			'skipto-settings',
			[
				'settings' => [
					'skipTo' => [
						'buttonDivRole'  => 'navigation',
						'buttonDivLabel' => Text::_('PLG_SYSTEM_SKIPTO_SKIP_TO_KEYBOARD'),
						'buttonLabel'    => Text::_('PLG_SYSTEM_SKIPTO_SKIP_TO'),
						'buttonDivTitle' => '',
						'menuLabel'      => Text::_('PLG_SYSTEM_SKIPTO_SKIP_TO_AND_PAGE_OUTLINE'),
						'landmarksLabel' => Text::_('PLG_SYSTEM_SKIPTO_SKIP_TO'),
						'headingsLabel'	 => Text::_('PLG_SYSTEM_SKIPTO_PAGE_OUTLINE'),
						'contentLabel'   => Text::_('PLG_SYSTEM_SKIPTO_CONTENT'),
					]
				]
			]
		);

		HTMLHelper::_('script', 'vendor/skipto/dropMenu.js', ['version' => 'auto', 'relative' => true], ['defer' => true]);
		HTMLHelper::_('script', 'vendor/skipto/skipTo.js', ['version' => 'auto', 'relative' => true], ['defer' => true]);
		HTMLHelper::_('stylesheet', 'vendor/skipto/SkipTo.css', ['version' => 'auto', 'relative' => true]);

		$document->addScriptDeclaration("document.addEventListener('DOMContentLoaded', function() {
			window.SkipToConfig = Joomla.getOptions('skipto-settings');
			window.skipToMenuInit();
		});"
		);
	}
}
