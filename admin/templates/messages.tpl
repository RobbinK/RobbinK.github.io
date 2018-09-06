{include file="header.tpl" title='Messages'}

<div class="container">
<div class="row">
<div class="page-header">
<h4>Messages</h4>
</div>

<table class="table table-striped">
<thead> <tr> <th>#</th> <th>Email</th> <th>Title</th> <th>Message</th> </tr> </thead>
<tbody>
{foreach $results as $m}
<tr> 
	<th scope=row>{$m.id}</th>
	<td><a href="mailto:{$m.email}" target="_blank">{$m.email}</a></td>
	<td>{$m.title|escape}</td>
	<td>{$m.message|escape}</td>
</tr>
{/foreach}
</tbody>
</table>

</div></div>
{include file="footer.tpl"}