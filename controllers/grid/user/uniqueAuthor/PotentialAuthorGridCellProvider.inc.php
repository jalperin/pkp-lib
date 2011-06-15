<?php

/**
 * @file controllers/grid/settings/user/UserEnrollmentGridCellProvider.inc.php
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UserEnrollmentGridCellProvider
 * @ingroup controllers_grid_settings_user
 *
 * @brief Cell provider that retrieves user data
 */

import('lib.pkp.classes.controllers.grid.GridCellProvider');

class PotentialAuthorGridCellProvider extends GridCellProvider {
	/**
	 * Constructor
	 */
	function PotentialAuthorGridCellProvider() {
		parent::GridCellProvider();
	}

	//
	// Template methods from GridCellProvider
	//

	/**
	 * Extracts variables for a given column from a data element
	 * so that they may be assigned to template before rendering.
	 * @param $row GridRow
	 * @param $column GridColumn
	 * @return array
	 */
	function getTemplateVarsFromRowColumn(&$row, $column) {
		$authorLookupPluginMatch =& $row->getData();
		$columnId = $column->getId();

		//assert(is_numeric($uniqueAuthorId) && !empty($columnId));
		switch ($columnId) {
			case 'authorString':
                return array('label' => $authorLookupPluginMatch['authorString']);
            case 'identifierType':
                $label = $authorLookupPluginMatch['pluginName'];
                if ( $authorLookupPluginMatch['identifierType'] ) {
                    $label .= '::' . $authorLookupPluginMatch['identifierType'];
                }
                return array('label' => $label);
		}
	}
}

?>
