{include file="header.tpl" title='Edit Category'}
<div class="container">
<div class="row">
<div class="page-header">
<h4>Edit Category <a href="{$settings['siteurl']}/{$settings['adminfolder']}/category_edit.php?type=delete&id={$tag.id}" type="button" class="btn btn-danger" style="float:right" onclick="return confirm('Are you sure?')">Delete</a></h4>
</div>

{if $errors}<p class="bg-danger">{$errors}</p>{/if}

<form method="post" action="{$settings['siteurl']}/{$settings['adminfolder']}/category_edit.php?type=edit&id={$tag.id}&submt=true" id="loginit" enctype="multipart/form-data">
<div class="form-group">
<label for="exampleInputEmail1">Name</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="name" placeholder="name" value="{$tag.name|capitalize|replace:'-':' '}" />
</div>

<div class="form-group">
<label for="exampleInputEmail1">Description</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="descc" placeholder="name" value="{$tag.seodesc|stripslashes}" />
</div>

<button type="submit" class="btn btn-full btn-bigtxt btn-primary" style="float:left;clear:both">Submit</button>
</form>

</script>