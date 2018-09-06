{include file="header.tpl" title='Categories'}

<div class="container">
<div class="row">
<div class="page-header">
<h4>Categories ({$total_items|number_format})</h4>

{if $msg}<p class="bg-success" style="padding:5px 10px">{$msg}</p>{/if}

<button type="button" style="margin-bottom:10px" class="btn btn-primary" onclick="addcat()">Add Category</button>

<form style="display:none" method="post" action="?" id="loginit" enctype="multipart/form-data">
<div class="form-group">
<label for="exampleInputEmail1">Name</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="name" placeholder="name" value="" />
</div>
<div class="form-group">
<label for="exampleInputEmail2">Description</label>
<input type="text" class="form-control" id="exampleInputEmail2" name="desc" placeholder="description" value="" />
</div>
<button type="submit" class="btn btn-full btn-bigtxt btn-primary" style="float:left;clear:both;margin-bottom:10px">Submit</button>
</form>

<table class="table table-striped">
<thead> <tr><th>Name</th> <th>Description</th> <th>Edit/Delete</th> </tr> </thead>
<tbody>
{foreach $games as $g}
<tr style="line-height:30px"> 
<td><a href="{$settings['siteurl']}/tag/{$g.name}" target="_blank" style="line-height:30px">{$g.name|capitalize|replace:'-':' '}</a></td>
<td>{$g.seodesc}</td>
<td><a href="category_edit.php?type=edit&id={$g.id}">Modify</a></td>
</tr>
{/foreach}
</tbody>
</table>


</div></div>
<script>
var addcat=function(){
	$('#loginit').toggle();
}
</script>
{include file="footer.tpl"}