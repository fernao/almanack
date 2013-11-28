<?php

/**
 * @defgroup plugins_themes_almanack
 */
 
/**
 * @file plugins/themes/almanackBlack/index.php
 *
 * Copyright (c) 2003-2009 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_themes_almanack
 * @brief Wrapper for "almanack" theme plugin.
 *
 */

// $Id: index.php,v 1.5.2.1 2009/04/08 19:43:27 asmecher Exp $


require_once('AlmanackBlackThemePlugin.inc.php');

return new AlmanackBlackThemePlugin();

?>
