{include file="header.tpl" title='Users'}

<div class="container">
<div class="row">
<div class="page-header">
<h4>Users ({$total_items|number_format})</h4>

<form class="form-inline" action="?" method="get">
  <div class="form-group">
    <label class="sr-only" for="exampleInputAmount">Search</label>
    <div class="input-group">
      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
      <input type="text" placeholder="Search for a user" value="{$query}" class="form-control" id="query" name="query">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Search</button>
</form>
</div>


<table class="table table-striped">
<thead> <tr><th>#</th> <th>Username</th> <th>Email</th> <th>Edit/Delete</th> </tr> </thead>
<tbody>
{foreach $users as $u}
<tr style="line-height:30px"> 
<th scope=row>{$u.id}</th>
<td><a href="{$settings['siteurl']}/profile/{$u.id}" target="_blank" style="line-height:30px"><img src="{$settings['siteurl']}/images/avatars/{if $u.avatar}{$u.avatar}{else}default-avatar.jpg{/if}" width="30" height="30" /> {$u.username}</a></td>
<td>{$u.user_email}</td>
<td><a href="users_edit.php?type=edit&id={$u.id}">Modify</a></td>
</tr>
{/foreach}
</tbody>
</table>

{if $total_pages > 1}
<div class="paging">
<div class="pagewrap">
{if $p != 1}
<a href="users.php?{if $query}query={$query}&{/if}p={$p-1}" class="btn btn-default">Prev</a>
{/if}
{section name=page start=$page_from loop=$page_to+1 step=1}
<a href="users.php?{if $query}query={$query}&{/if}p={$smarty.section.page.index}" class="btn btn-default{if $p == $smarty.section.page.index} active{/if}">{$smarty.section.page.index}</a>
{/section}
{if $p != $total_pages}
<a href="users.php?{if $query}query={$query}&{/if}p={$p+1}" class="btn btn-default">Next</a>
{/if}
</div></div>
{/if}

</div></div>
{include file="footer.tpl"}