{**
 * templates/controllers/grid/gridRowSelectInput.tpl
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Display a checkbox that allows users to select a grid row when ticked
 *}
<input type="checkbox" id="select-{$elementId|escape}" name="{$selectName|escape}[]" value="{$elementId|escape}" class="field checkbox" {if $selected}checked="checked"{/if} />
{** add a hidden element with a value, if present **}
{if $elementValue}
<input type="hidden" id="hidden-{$elementId|escape}" name="{$elementId|escape}" value="{$elementValue|escape}" />
{/if}