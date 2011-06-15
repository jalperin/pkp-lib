{**
 * templates/controllers/grid/gridRowradioInput.tpl
 *
 * Copyright (c) 2000-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Display a radio that allows users to select a grid row when clicked
 *}
<input type="radio" id="radio-{$elementId|escape}" name="{$selectName|escape}" value="{$elementId|escape}" class="field radio" {if $selected}checked="checked"{/if} />
