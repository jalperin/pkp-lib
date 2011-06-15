<?php

/**
 * @file lib/pkp/controllers/grid/user/uniqueAuthor/UniqueAuthorGridHandler.inc.php
 *
 * Copyright (c) 2000-2009 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UniqueAuthorGridHandler
 * @ingroup controllers_grid_user_uniqueAuthor
 *
 * @brief Handle requests to disambiguate authors
 */

import('lib.pkp.classes.controllers.grid.GridHandler');

import('controllers.grid.user.uniqueAuthor.UniqueAuthorGridCellProvider');
//import('controllers.grid.settings.user.form.UserForm');

class UniqueAuthorGridHandler extends GridHandler {
	/**
	 * Constructor
	 */
	function UserGridHandler() {
		parent::GridHandler();
		// FIXME: need to give authors access.
		$this->addRoleAssignment(array(
			ROLE_ID_PRESS_MANAGER),
			array('fetchGrid', 'fetchRow')
		);
	}


	//
	// Implement template methods from PKPHandler.
	//
	/**
	 * @see PKPHandler::authorize()
	 */
	function authorize(&$request, $args, $roleAssignments) {
		return true;
		// FIXME: this is not the right policy.
		import('classes.security.authorization.OmpPressAccessPolicy');
		$this->addPolicy(new OmpPressAccessPolicy($request, $roleAssignments));
		return parent::authorize($request, $args, $roleAssignments);
	}

	/**
	 * @see PKPHandler::initialize()
	 */
	function initialize(&$request) {
		parent::initialize($request);

		// FIXME: may need different locales
		// Load user-related translations.
		Locale::requireComponents(array(
			LOCALE_COMPONENT_PKP_USER
		));

		// FIXME: locale key does not exist yet
		// Basic grid configuration.
		$this->setTitle('grid.user.existingDisambiguatedAuthors');
        $this->setInstructions('grid.user.selectUniqueAuthorInstructions');

		//
		// Grid columns.
		//
		// Add checkbox column to the grid.
		import('lib.pkp.controllers.grid.user.uniqueAuthor.UniqueAuthorSelectionGridColumn');
		$this->addColumn(new UniqueAuthorSelectionGridColumn('uniqueAuthorId'));

		// Author Info
		$cellProvider = new UniqueAUthorGridCellProvider();
		$this->addColumn(
			new GridColumn(
				'authorString',
				'user.name',
				null,
				'controllers/grid/users/uniqueAuthor/uniqueAuthorAuthorStringGridCell.tpl',
				$cellProvider
			)
		);
	}


	//
	// Implement methods from GridHandler.
	//

	/**
	 * @see GridHandler::loadData()
	 * @param $request PKPRequest
	 * @return array Grid data.
	 */
	function loadData($request, $filter) {
		// Get all users for this press that match search criteria.
		$uniqueAuthorDao =& DAORegistry::getDAO('UniqueAuthorDAO');

		$uniqueAuthorIds =& $uniqueAuthorDao->getUniqueAuthorIdsByContent(
			$filter['searchTerm']
		);

		return $uniqueAuthorIds;
	}

	/**
	 * @see GridHandler::getFilterSelectionData()
	 * @return array Filter selection data.
	 */
	function getFilterSelectionData($request) {
		// Get the search terms.
		$searchTerm = $request->getUserVar('searchTerm');

		return array('searchTerm' => $searchTerm);
	}
}

?>
