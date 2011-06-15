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

class UniqueAuthorGridCellProvider extends GridCellProvider {
	/**
	 * Constructor
	 */
	function UniqueAuthorGridCellProvider() {
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
		$uniqueAuthorId =& $row->getData();
		$columnId = $column->getId();

		assert(is_numeric($uniqueAuthorId) && !empty($columnId));
		switch ($columnId) {
			case 'authorString': // User's roles
                // Get all users for this press that match search criteria.
                $uniqueAuthorDao =& DAORegistry::getDAO('UniqueAuthorDAO');
                $uniqueAuthors = $uniqueAuthorDao->getUniqueAuthorsById($uniqueAuthorId);

				$authorStrings = array();
				while ( $uniqueAuthor =& $uniqueAuthors->next() ) {
					$authorStrings[$uniqueAuthor->getIdentifierType().'-'.$uniqueAuthor->getIdentifierId()]
                            = $uniqueAuthor->getContent();
					unset($uniqueAuthor);
				}
				unset($uniqueAuthors);
				return array('authorStrings' => $authorStrings);
		}
	}
}

?>
