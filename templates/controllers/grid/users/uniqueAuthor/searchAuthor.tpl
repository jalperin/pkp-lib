{**
 * controllers/grid/users/uniqueAuthor/searchAuthor.tpl
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * An author needs to be searched for in two places: unique authors and potential author grids.
 * The unique author grid holds the search form.
 *
 *}
{assign var='uniqueId' value=""|uniqid}

{url|assign:uniqueAuthorSearchFormUrl router=$smarty.const.ROUTE_COMPONENT component="grid.users.author.AuthorGridHandler" op="fetchSearchForm" monographId=$monographId|escape}
{load_url_in_div id="uniqueAuthorSearchFormContainer" url=$uniqueAuthorSearchFormUrl}

<script type="text/javascript">
    // Attach the AjaxFormHandler
    $(function() {ldelim}
        $('#selectUniqueAuthorForm').pkpHandler(
            '$.pkp.controllers.wizard.uniqueAuthor.form.UniqueAuthorAjaxFormHandler'
        );
    {rdelim});
</script>

<div id="selectUniqueAuthorContainer">
    <form id="selectUniqueAuthorForm" action="{url router=$smarty.const.ROUTE_COMPONENT component="grid.users.author.AuthorGridHandler" op="createAuthorAssociation" monographId=$monographId|escape}" method="post" >

        <div id="uniqueAuthorGridContainer"></div>

        <div id="potentialAuthorGridContainer"></div>
    </form>
</div>