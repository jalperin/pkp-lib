<?php

/**
 * @file lib/pkp/controllers/grid/user/uniqueAuthor/PotentialAuthorGridHandler.inc.php
 *
 * Copyright (c) 2000-2009 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PotentialAuthorGridHandler
 * @ingroup controllers_grid_user_uniqueAuthor
 *
 * @brief Handle requests to disambiguate authors
 */

import('lib.pkp.classes.controllers.grid.GridHandler');

import('controllers.grid.user.uniqueAuthor.PotentialAuthorGridCellProvider');

class PotentialAuthorGridHandler extends GridHandler {
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

		// Basic grid configuration.
		$this->setTitle('grid.user.potentialAuthorMatches');
        $this->setInstructions('grid.user.selectPotentialAuthorInstructions');

		//
		// Grid columns.
		//
		// Add checkbox column to the grid.
		import('lib.pkp.controllers.grid.user.uniqueAuthor.PotentialAuthorSelectionGridColumn');
		$this->addColumn(new PotentialAuthorSelectionGridColumn('potentialAuthorId'));

		// Author Info
		$cellProvider = new PotentialAuthorGridCellProvider();
		$this->addColumn(
			new GridColumn(
				'authorString',
				'user.name',
				null,
				'controllers/grid/gridCell.tpl',
				$cellProvider
			)
		);

		$this->addColumn(
			new GridColumn(
				'identifierType',
				'common.type',
				null,
				'controllers/grid/gridCell.tpl',
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
        $uniqueAuthorDao =& DAORegistry::getDAO('UniqueAuthorDAO');

        PluginRegistry::loadCategory('authorLookup', true);
        $authorPlugins =& PluginRegistry::getPlugins('authorLookup');
        $results = array();
        foreach ($authorPlugins as $authorPlugin) {
            // Get the plugin name and strip out AuthorLookupPlugin
            $pluginResults = $authorPlugin->getAuthors($filter['searchTerm']);
            foreach ($pluginResults as $identifierString => $authorString) {
                $idArray = explode('-', $identifierString);
                // If there is no hyphen, then there is no identifier type
                // if there is a hyphen, then the first part is the identifier type.
                // N.B. do not confuse with what is displayed on the grid, which is different.
                $identifierType = (count($idArray) == 1)? null : $idArray[0];
                // the last part of the id array is always the actual identifier.
                $id = (count($idArray) == 1) ? $idArray[0]: $idArray[1];

                $pluginNameIdentifierType =
                        $authorPlugin->getShortName() . (empty($identifierType)?'' : '-' . $identifierType);
                if ( !$uniqueAuthorDao->getUniqueAuthorByType($pluginNameIdentifierType, $id) ) {
                    $results[] = array( 'pluginName' => $authorPlugin->getShortName(),
                                        'identifierType' => $identifierType,
                                        'authorId' => $id,
                                        'authorString' => $authorString);
                }
            }
        }
        return $results;
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
