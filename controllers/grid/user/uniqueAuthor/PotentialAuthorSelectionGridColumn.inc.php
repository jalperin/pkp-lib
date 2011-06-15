<?php
/**
 * @file lib/pkp/controllers/grid/user/uniqueAuthor/PotentialAuthorSelectionGridColumn.inc.php
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PotentialAuthorSelectionGridColumn
 * @ingroup controllers_grid_users_uniqueAuthor
 *
 * @brief Implements a column with radio buttons to select from unique author identifiers.
 */

import('lib.pkp.classes.controllers.grid.GridColumn');

class PotentialAuthorSelectionGridColumn extends GridColumn {

	/** @var string */
	var $_selectName;


	/**
	 * Constructor
	 * @param $selectName string The name of the form parameter
	 *  to which the selected files will be posted.
	 */
	function PotentialAuthorSelectionGridColumn($selectName) {
		assert(is_string($selectName) && !empty($selectName));
		$this->_selectName = $selectName;

		import('lib.pkp.classes.controllers.grid.ColumnBasedGridCellProvider');
		$cellProvider = new ColumnBasedGridCellProvider();
		parent::GridColumn('select', 'common.select', null, 'controllers/grid/gridRowSelectInput.tpl', $cellProvider);
	}


	//
	// Getters and Setters
	//
	/**
	 * Get the select name.
	 * @return string
	 */
	function getSelectName() {
		return $this->_selectName;
	}


	//
	// Public methods
	//
	/**
	 * Method expected by ColumnBasedGridCellProvider
	 * to render a cell in this column.
	 *
	 * @see ColumnBasedGridCellProvider::getTemplateVarsFromRowColumn()
	 */
	function getTemplateVarsFromRow($row) {
		// Retrieve the file data.
        $authorLookupPluginMatch =& $row->getData();

        // Construct a string to use as an identifier.
        $id = $authorLookupPluginMatch['pluginName'];
        if ( $authorLookupPluginMatch['identifierType'] ) {
            $id .= '-' . $authorLookupPluginMatch['identifierType'];
        }
        $id .= '-' . $authorLookupPluginMatch['authorId'];

        $value = $authorLookupPluginMatch['authorString'];

		// Return the data expected by the column's cell template.
		return array(
			'elementId' => $id,
            'elementValue' => $value,
			'selectName' => $this->getSelectName());
	}
}

?>
