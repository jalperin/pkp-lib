<?php

/**
 * @defgroup uniqueAuthor
 */

/**
 * @file classes/site/UniqueAuthor.inc.php
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UniqueAuthor
 * @ingroup uniqueAuthor
 * @see UniqueAuthorDAO
 *
 * @brief Describes authors with unique identifiers
 */


class UniqueAuthor extends DataObject {
	/**
	 * Constructor.
	 */
	function UniqueAuthor() {
		parent::DataObject();
	}

	//
	// Get/set methods
	//
	/**
	 * Set the Identifier Type
	 * @param $identifierType string
	 */
	function setIdentifierType($identifierType) {
		$this->setData('identifierType', $identifierType);
	}

	/**
	 * Get the Identifier Type
	 * @return string
	 */
	function getIdentifierType() {
		return $this->getData('identifierType');
	}

	/**
	 * Set the Identifier ID
	 * @param $identifierId string
	 */
	function setIdentifierId($identifierId) {
		$this->setData('identifierId', $identifierId);
	}

	/**
	 * Get the Identifier ID
	 * @return string
	 */
	function getIdentifierId() {
		return $this->getData('identifierId');
	}

	/**
	 * Set the content
	 * @param $content string
	 */
	function setContent($content) {
		$this->setData('content', $content);
	}

	/**
	 * Get the content
	 * @return string
	 */
	function getContent() {
		return $this->getData('content');
	}



}

?>
