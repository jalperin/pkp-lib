{**
 * lib/pkp/templates/controllers/grid/user/uniqueAuthor/searchUniqueAuthorForm.tpl
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Search for all unique authors from one search term
 *
 *}

{assign var='uniqueId' value=""|uniqid}

<script type="text/javascript">
	// Attach the AjaxFormHandler
	$(function() {ldelim}
		$('#searchAuthorForm').pkpHandler(
			'$.pkp.controllers.form.GridFilterAjaxFormHandler'
		);
	{rdelim});
</script>

<form id="searchAuthorForm" method="post" action="">
	{include file="common/formErrors.tpl"}
	{fbvFormArea id="search"}
		{fbvFormSection title="user.name"}
			{fbvElement type="text" id="searchTerm" value=$searchTerm|escape maxlength="40" size=$fbvStyles.size.LARGE}
			{fbvButton type="submit" label="common.search"}
		{/fbvFormSection}
	{/fbvFormArea}
</form>