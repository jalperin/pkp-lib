<?php

/**
 * @file classes/uniqueAuthor/UniqueAuthorDAO.inc.php
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UniqueAuthorDAO
 * @ingroup uniqueAuthor
 * @see UniqueAuthor
 *
 * @brief Operations for retrieving and modifying Unique Author objects.
 */

import('lib.pkp.classes.uniqueAuthor.UniqueAuthor');

class UniqueAuthorDAO extends DAO {

	/**
	 * Get a result set of unique author identifiers by a uniqueAuthorId
	 * @param $uniqueAuthorId int
	 * @return DAOResultFactory UniqueAuthors
	 */
	function getUniqueAuthorsById($uniqueAuthorId) {
		$result =& $this->retrieve(
			'SELECT * FROM author_identifiers
			WHERE unique_author_id = ?',
			$uniqueAuthorId
		);

		$returner = new DAOResultFactory($result, $this, '_returnFromRow');
		return $returner;
	}

	/**
	 * Get a unique author by the unique author identifier and an identifier type.
     * Ensures unique result by getting the MAX identifier id
	 * @param $uniqueAuthorId int
     * @param $identifierType string
	 * @return $uniqueAuthor UniqueAuthor
	 */
	function getUniqueAuthorByIdAndType($uniqueAuthorId, $identifierType) {
		$result =& $this->retrieve(
			'SELECT unique_author_id, identifier_type, MAX(identifier_id) as identifier_id, content FROM author_identifiers
			WHERE unique_author_id = ? AND identifier_type = ?
			GROUP BY unique_author_id, identifier_type',
			array($uniqueAuthorId, $identifierType)
		);

        $returner = null;
        if ($result->RecordCount() != 0) {
            $returner =& $this->_returnFromRow($result->GetRowAssoc(false));
        }

        $result->Close();
        unset($result);

        return $returner;
	}

    /**
     * Get the unique author by the type and the id.
     * @param  $identifierType string
     * @param  $identifierId int
     * @return UniqueAuthor
     */
    function getUniqueAuthorByType($identifierType, $identifierId) {
        $result =& $this->retrieve(
            'SELECT * FROM author_identifiers
            WHERE identifier_type = ? AND identifier_id = ?',
            array($identifierType, $identifierId));

        $returner = null;
        if ($result->RecordCount() != 0) {
            $returner =& $this->_returnFromRow($result->GetRowAssoc(false));
        }

        $result->Close();
        unset($result);

        return $returner;
    }

	/**
	 * Get a result set of unique author identifiers
	 * @param $searchTerm string
	 * @return DAOResultFactory UniqueAuthors
	 */
	function getUniqueAuthorIdsByContent($searchTerm, $rangeInfo = null) {
		$result =& $this->retrieveRange(
			'SELECT DISTINCT unique_author_id FROM author_identifiers
			WHERE content like ?',
			'%' . $searchTerm . '%',
			$rangeInfo
		);

		$authorIds = array();
		while (!$result->EOF) {
			$authorIds[] = $result->fields['unique_author_id'];
			$result->moveNext();
		}

		$result->Close();
		unset($result);

		return $authorIds;
	}

    /**
     * Make a new unique author association
     *
     */
    function addUniqueAuthorIdentifier($targetUniqueAuthorId, $identifierType, $identifierId, $content) {
        // first try to see if there is already a unique author with this type and id
        $existingUniqueAuthor =& $this->getUniqueAuthorByType($identifierType, $identifierId);
        if ( $existingUniqueAuthor ) {
            if ( $targetUniqueAuthorId) {
                // We can combine two unique author id's
                $this->update('UPDATE author_identifiers SET unique_author_id = ? WHERE unique_author_id = ?',
                            array($targetUniqueAuthorId, $existingUniqueAuthor->getId()));
                // update the existing unique author
                $existingUniqueAuthor->setId($targetUniqueAuthorId);
            }
            // update the content with this latest
            $existingUniqueAuthor->setContent($content);
            $this->updateObject($existingUniqueAuthor);
            return $existingUniqueAuthor;
        }
        $newUniqueAuthor = new UniqueAuthor();
        $newUniqueAuthor->setIdentifierType($identifierType);
        $newUniqueAuthor->setIdentifierId($identifierId);
        $newUniqueAuthor->setContent($content);
        if ( $targetUniqueAuthorId ) {
            $newUniqueAuthor->setId($targetUniqueAuthorId);
        }
        $this->insertUniqueAuthor($newUniqueAuthor);
        return $newUniqueAuthor;
    }



	/**
	 * Internal function to return a UniqueAuthor object from a row.
	 * @param $row array
	 * @param $callHook boolean
	 * @return UniqueAuthor
	 */
	function &_returnFromRow(&$row, $callHook = true) {
		$uniqueAuthor = new UniqueAuthor();
		$uniqueAuthor->setId($row['unique_author_id']);
		$uniqueAuthor->setIdentifierType($row['identifier_type']);
		$uniqueAuthor->setIdentifierId($row['identifier_id']);
		$uniqueAuthor->setContent($row['content']);

		if ($callHook) HookRegistry::call('UniqueAuthorDAO::_returnFromRow', array(&$uniqueAuthor, &$row));

		return $uniqueAuthor;
	}

	/**
	 * Insert uniqueAuthor information.
	 * @param $uniqueAuthor UniqueAuthor
	 */
	function insertUniqueAuthor(&$uniqueAuthor) {
		$uniqueId = $uniqueAuthor->getId();
		$withId = !empty($uniqueId);
		$params = array();
		if ( $withId ) $params[] = $uniqueId;
		$params = array_merge($params, 	array($uniqueAuthor->getIdentifierType(),
												$uniqueAuthor->getIdentifierId(),
												$uniqueAuthor->getContent()
											)
							);

		$returner = $this->update(
			'INSERT INTO author_identifiers
				(' . ($withId?'unique_author_id, ':'') . ' identifier_type, identifier_id, content)
				VALUES
				(' . ($withId?'?, ':'') . '?, ?, ?)',
			$params
		);
        if ( !$withId ) {
            $uniqueAuthor->setId($this->getInsertUniqueAuthorId());
        }
		return $uniqueAuthor;
	}

	/**
	 * Update existing uniqueAuthor information.
	 * @param $uniqueAuthor UniqueAuthor
	 */
	function updateObject(&$uniqueAuthor) {
		return $this->update(
			'UPDATE author_identifiers
				SET
					content = ?
				WHERE
					unique_author_id = ? AND
					identifier_type = ? AND
					identifier_id = ?',
			array(
				$uniqueAuthor->getContent(),
				$uniqueAuthor->getId(),
				$uniqueAuthor->getIdentifierType(),
				$uniqueAuthor->getIdentifierId()
			)
		);
	}

	/**
	 * Get the ID of the last inserted author identifier.
	 * @return int
	 */
	function getInsertUniqueAuthorId() {
		return $this->getInsertId('author_identifiers', 'unique_author_id');
	}
}
?>
