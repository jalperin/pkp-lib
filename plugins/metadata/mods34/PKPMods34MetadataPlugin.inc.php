<?php

/**
 * @defgroup plugins_metadata_mods34
 */

/**
 * @file plugins/metadata/mods34/PKPMods34MetadataPlugin.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PKPMods34MetadataPlugin
 * @ingroup plugins_metadata_mods34
 *
 * @brief Abstract base class for MODS metadata plugins
 */


import('lib.pkp.classes.plugins.MetadataPlugin');

class PKPMods34MetadataPlugin extends MetadataPlugin {
	/**
	 * Constructor
	 */
	function PKPMods34MetadataPlugin() {
		parent::MetadataPlugin();
	}


	//
	// Override protected template methods from PKPPlugin
	//
	/**
	 * @see PKPPlugin::getName()
	 */
	function getName() {
		return 'Mods34MetadataPlugin';
	}

	/**
	 * @see PKPPlugin::getDisplayName()
	 */
	function getDisplayName() {
		return PKPLocale::translate('plugins.metadata.mods34.displayName');
	}

	/**
	 * @see PKPPlugin::getDescription()
	 */
	function getDescription() {
		return PKPLocale::translate('plugins.metadata.mods34.description');
	}
}

?>
