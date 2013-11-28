<?php

/**
 * @file AlmanackThemePlugin.inc.php
 *
 * Copyright (c) 2003-2009 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class AlmanackThemePlugin
 * @ingroup plugins_themes_almanack
 *
 * @brief "Almanack" theme plugin
 */

// $Id: AlmanackThemePlugin.inc.php,v 1.7.2.1 2009/04/08 19:43:27 asmecher Exp $


import('classes.plugins.ThemePlugin');

class AlmanackThemePlugin extends ThemePlugin {
	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	function getName() {
		return 'AlmanackThemePlugin';
	}

	function getDisplayName() {
		return 'Almanack Theme';
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
