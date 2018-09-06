{include file="header.tpl" title='Games'}

<div class="container">
<div class="row">
<div class="page-header">
<h4>Games ({$total_items|number_format})</h4>

<form class="form-inline" action="?" method="get">
  <div class="form-group">
    <label class="sr-only" for="exampleInputAmount">Search</label>
    <div class="input-group">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
      <input type="text" placeholder="Search for a game" value="{$query}" class="form-control" id="query" name="query">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Search</button>
</form>
</div>

{if $msg}<p class="bg-success" style="padding:5px 10px">{$msg}</p>{/if}

<table class="table table-striped">
<thead> <tr><th>#</th> <th>Name</th> <th>Plays</th> <th>Edit/Delete</th> </tr> </thead>
<tbody>
{foreach $games as $g}
<tr style="line-height:30px"> 
<th scope=row>{$g.id}</th>
<td><a href="{$settings['siteurl']}/play/{$g.id}/" target="_blank" style="line-height:30px"><img src="{$settings['imgcdn']}/{$g.thumb}" width="30" height="30" /> {$g.name}</a></td>
<td>{$g.play_count|number_format}</td>
<td><a href="games_edit.php?type=edit&id={$g.id}">Modify</a></td>
</tr>
{/foreach}
</tbody>
</table>

{if $total_pages > 1}
<div class="paging">
<div class="pagewrap">
{if $p != 1}
<a href="games.php?{if $query}query={$query}&{/if}p={$p-1}" class="btn btn-default">Prev</a>
{/if}
{section name=page start=$page_from loop=$page_to+1 step=1}
<a href="games.php?{if $query}query={$query}&{/if}p={$smarty.section.page.index}" class="btn btn-default{if $p == $smarty.section.page.index} active{/if}">{$smarty.section.page.index}</a>
{/section}
{if $p != $total_pages}
<a href="games.php?{if $query}query={$query}&{/if}p={$p+1}" class="btn btn-default">Next</a>
{/if}
</div></div>
{/if}

</div></div>
{include file="footer.tpl"}