<?php

/**
 * @file AlmanackBlackThemePlugin.inc.php
 *
 * Copyright (c) 2003-2009 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class AlmanackBlackThemePlugin
 * @ingroup plugins_themes_almanack
 *
 * @brief "AlmanackBlack" theme plugin
 */

// $Id: AlmanackBlackThemePlugin.inc.php,v 1.7.2.1 2009/04/08 19:43:27 asmecher Exp $


import('classes.plugins.ThemePlugin');

class AlmanackBlackThemePlugin extends ThemePlugin {
	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	function getName() {
		return 'AlmanackBlackThemePlugin';
	}

	function getDisplayName() {
		return 'Almanack Black Theme';
	}

	function getDescription() {
		return 'Chunky, blue, solid layout';
	}

	function getStylesheetFilename() {
		return 'almanack.css';
	}
	function getLocaleFilename($locale) {
		return null; // No locale data
	}
}

?>
