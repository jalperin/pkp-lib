<?php
/**
 * @file lib/pkp/controllers/grid/user/uniqueAuthor/UniqueAuthorSelectionGridColumn.inc.php
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UniqueAuthorSelectionGridColumn
 * @ingroup controllers_grid_users_uniqueAuthor
 *
 * @brief Implements a column with radio buttons to select from unique author identifiers.
 */

import('lib.pkp.classes.controllers.grid.GridColumn');

class UniqueAuthorSelectionGridColumn extends GridColumn {

	/** @var string */
	var $_selectName;


	/**
	 * Constructor
	 * @param $selectName string The name of the form parameter
	 *  to which the selected files will be posted.
	 */
	function UniqueAuthorSelectionGridColumn($selectName) {
		assert(is_string($selectName) && !empty($selectName));
		$this->_selectName = $selectName;

		import('lib.pkp.classes.controllers.grid.ColumnBasedGridCellProvider');
		$cellProvider = new ColumnBasedGridCellProvider();
		parent::GridColumn('select', 'common.select', null, 'controllers/grid/gridRowRadioInput.tpl', $cellProvider);
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
        $uniqueAuthorId = $row->getData();

		// Grab the unique author id of the first author in each row
        // based on the fact that all the authors per row have the same id.

		// Return the data expected by the column's cell template.
		return array(
			'elementId' => $uniqueAuthorId,
			'selectName' => $this->getSelectName());
	}
}

?>
