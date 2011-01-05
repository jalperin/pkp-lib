{**
 * footer.tpl
 *
 * Copyright (c) 2000-2009 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site footer.
 *
 *}
</div><!-- content -->
</div><!-- main -->
{** for wordpress **}
{php}wp_footer(){/php}
</div><!-- body -->
<br clear="all" />

<div class="license">
{if $displayCreativeCommons}
{translate key="common.ccLicense"}
{/if}
</div>

{if $pageFooter}
<br /><br />
{$pageFooter}
{/if}
{call_hook name="Templates::Common::Footer::PageFooter"}


{get_debug_info}
{if $enableDebugStats}{include file=$pqpTemplate}{/if}

</div><!-- container -->
</body>
</html>
