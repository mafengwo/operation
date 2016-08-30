{if $paginator}
<ul class="pagination pagination-sm inline">
{if $paginator.cur_page gt 1}
<li><a href="{$page_str}&page={$paginator.cur_page-1}">前一页</a></li>
{/if} {if $paginator.minpage gt 1}
<li><a href="{$page_str}&page=1">1...</a></li>
{/if} {foreach from=$paginator.pages item=page} {if $page eq $paginator.cur_page}

<li class="active"><a href="javascript:void(0);">{$page}</a></li>
{else}
<li><a href="{$page_str}&page={$page}">{$page}</a></li>
{/if} {/foreach} {if $paginator.maxpage lt $paginator.total_pages}
<li><a href="{$page_str}&page={$paginator.total_pages}">...{$paginator.total_pages}</a></li>
{/if} {if $paginator.cur_page lt $paginator.total_pages}
<li><a href="{$page_str}&page={$paginator.cur_page+1}">后一页</a></li>
{/if}
</ul>
{/if}
