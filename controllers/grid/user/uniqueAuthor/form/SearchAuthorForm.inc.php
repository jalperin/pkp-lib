<?php

/**
 * @file controllers/grid/users/author/form/AuthorForm.inc.php
 *
 * Copyright (c) 2003-2008 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SearchAuthorForm
 * @ingroup controllers_grid_users_author_form
 *
 * @brief Form for search existing authors internally and externally (using authorLookup plugins)
 */

import('lib.pkp.classes.form.Form');

class SearchAuthorForm extends Form {
	/**
	 * Constructor.
	 */
	function SearchAuthorForm() {
		parent::Form('controllers/grid/users/uniqueAuthor/form/searchUniqueAuthorsForm.tpl');

		// Validation checks for this form
		$this->addCheck(new FormValidator($this, 'searchTerm', 'required', 'submission.submit.form.searchTerms'));
		$this->addCheck(new FormValidatorPost($this));
	}

	//
	// Overridden template methods
	//
	/**
	 * Assign form data to user-submitted data.
	 * @see Form::readInputData()
	 */
	function readInputData() {
		$this->readUserVars(array('searchTerm'));
	}
}

?>
