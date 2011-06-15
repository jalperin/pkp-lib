{**
 * templates/controllers/grid/users/uniqueAuthor/uniqueAuthorAuthorStringGridCell.tpl
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * a cell with an array of author strings placed, one per line, inside of a cell
 *}
{if $id}
	{assign var=cellId value="cell-"|concat:$id}
{else}
	{assign var=cellId value=""}
{/if}
<span {if $cellId}id="{$cellId|escape}" {/if}class="pkp_linkActions gridCellContainer">
	{foreach name="authorStringArray" from=$authorStrings item="authorString" key="identifierType"}
	    <span style="float: left">{$authorString|escape}</span>
        <span style="float: right">{$identifierType|escape}</span>
        {if !$smarty.foreach.authorStringArray.last}<br />{/if}
	{/foreach}
</span>

