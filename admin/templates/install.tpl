{include file="header.tpl" title='Install Game'}
<div class="container">
<div class="row">
<div class="page-header">
<h4>Install Game {$game.name|stripslashes}</h4>
</div>
{if $game.installed==1}<p class="bg-danger">Warning: You have already installed this game!</p>{/if}
{if $errors}<p class="bg-danger">{$errors}</p>{/if}
{if $msg && $gamelink}<p class="bg-success">{$msg}<br>Play it: <a href="{$settings['siteurl']}/g/{$gamelink}">{$game_name}</a>.</p>{else}
<form method="post" action="{$settings['siteurl']}/{$settings['adminfolder']}/install.php?source={$source}&id={$game.id}&submt=true" id="loginit"  enctype="multipart/form-data">
<div class="form-group">
<label for="exampleInputEmail1">Name</label>
<input type="text" required class="form-control" id="exampleInputEmail1" name="name" placeholder="name" value="{$game.name|stripslashes}" />
</div>

<div class="form-group">
<label class="custom">Category</label>
<select name="cat_id">
{foreach $categoriesloop as $cg}
<option value="{$cg.name}" {if $game.cat_id==$cg.name} selected{/if}>{$cg.name|capitalize|replace:'-':' '}</option>
{/foreach}
</select>
</div>

<div class="form-group">
<label for="exampleInputEmail3">Tags (comma seperated)</label>
<input type="text" class="form-control" id="exampleInputEmail3" name="tags" placeholder="tags" value="{$game.tags}" required />
</div>

<div class="form-group">
<label for="exampleInputEmail5">Description</label><br>
<textarea name="description" cols="50" rows="5" id="exampleInputEmail5" required>{$game.description|stripslashes}</textarea>
</div>

<div class="form-group">
<label for="exampleInputEmail6">Instructions</label><br>
<textarea id="exampleInputEmail6" name="instructions" cols="50" rows="5" required>{$game.instructions|stripslashes}</textarea>
</div>

<div class="form-group" style="float:left;width:215px;height:auto">
<label>Thumbnail</label>
{if $game.thumb}<img src="{$game.thumb}" alt="" />{else}
<input type="file" name="avaimage" id="slim">
{/if}
</div>

<div class="form-group" style="float:left;clear:both">
<label for="exampleInputEmail8">URL</label><br/>
<textarea name="embed" cols="50" required rows="1" id="exampleInputEmail8">{$game.swf}</textarea>
</div>

<div class="form-group" style="float:left;clear:both">
<label for="exampleInputEmail8">Options (Check if true)</label><br/>
<label class="checkbox-inline">
  <input type="checkbox" id="inlineCheckbox1" name="feature" value="1" {if $game.feature}checked{/if}> Feature
</label>
<label class="checkbox-inline">
  <input type="checkbox" id="inlineCheckbox2" name="hasscores" value="1" {if $game.has_scores}checked{/if}> High Scores
</label>
<label class="checkbox-inline">
  <input type="checkbox" id="inlineCheckbox3" name="hasawards" value="1" {if $game.has_achs}checked{/if}> Achievements
</label>
<label class="checkbox-inline">
  <input type="checkbox" id="inlineCheckbox3" name="ads" value="1" {if $game.ads}checked{/if}> Video Ads
</label>
</div>

<div id="highscorediv" class="form-group" style="float:left;clear:both;display:none">
<label>High score Boards</label>
{if $game.has_scores==1}
{foreach $boards as $br}
<div class="form-group form-inline">
	<input type="text" class="form-control" name="scoreboard[{$br@index}][0]" placeholder="Board id #1" value="{$br.board_id}" />
	<input type="text" class="form-control" name="scoreboard[{$br@index}][1]" placeholder="Board Name" value="{$br.name}" />
<input type="hidden" class="form-control" name="scoreboard[{$br@index}][2]" placeholder="Board Name" value="{$br.id}" />
</div>
{/foreach}
{else}
<div class="form-group form-inline">
<input type="text" class="form-control" name="scoreboard[0][0]" placeholder="Board id #1"  />
<input type="text" class="form-control" name="scoreboard[0][1]" placeholder="Board Name"  />
</div>

<div class="form-group form-inline">
<input type="text" class="form-control" name="scoreboard[1][0]" placeholder="Board id #2"  />
<input type="text" class="form-control" name="scoreboard[1][1]" placeholder="Board Name"  />
</div>

<div class="form-group form-inline">
<input type="text" class="form-control" name="scoreboard[2][0]" placeholder="Board id #3"  />
<input type="text" class="form-control" name="scoreboard[2][1]" placeholder="Board Name"  />
</div>
{/if}
</div>

<div id="awardsdiv" class="form-group" style="float:left;clear:both;display:none">
<label>Awards</label>
{if $game.has_achs==1}
{foreach $awards as $award}
<div class="form-group form-inline">
<input type="text" name="award[{$award@index}][0]" class="form-control" placeholder="Award ID" value="{$award.ach_id}">
<input type="text" name="award[{$award@index}][1]" class="form-control" placeholder="Award Name" value="{$award.name}">
<input type="text" name="award[{$award@index}][2]" class="form-control" placeholder="Points" value="{$award.points}">
<input type="text" name="award[{$award@index}][3]" class="form-control" placeholder="Description" value="{$award.textdesc}">
<input type="hidden" class="form-control" name="award[{$award@index}][4]" placeholder="Board Name" value="{$award.id}" />
</div>
{/foreach}
{else}
<div class="form-group form-inline">
<input type="text" name="award[0][0]" class="form-control" placeholder="Award ID #1">
<input type="text" name="award[0][1]" class="form-control" placeholder="Award Name">
<input type="text" name="award[0][2]" class="form-control" placeholder="Points">
<input type="text" name="award[0][3]" class="form-control" placeholder="Description">
</div>

<div class="form-group form-inline">
<input type="text" name="award[1][0]" class="form-control" placeholder="Award ID #2">
<input type="text" name="award[1][1]" class="form-control" placeholder="Award Name">
<input type="text" name="award[1][2]" class="form-control" placeholder="Points">
<input type="text" name="award[1][3]" class="form-control" placeholder="Description">
</div>

<div class="form-group form-inline">
<input type="text" name="award[2][0]" class="form-control" placeholder="Award ID #3">
<input type="text" name="award[2][1]" class="form-control" placeholder="Award Name">
<input type="text" name="award[2][2]" class="form-control" placeholder="Points">
<input type="text" name="award[2][3]" class="form-control" placeholder="Description">
</div>

<div class="form-group form-inline">
<input type="text" name="award[3][0]" class="form-control" placeholder="Award ID #4">
<input type="text" name="award[3][1]" class="form-control" placeholder="Award Name">
<input type="text" name="award[3][2]" class="form-control" placeholder="Points">
<input type="text" name="award[3][3]" class="form-control" placeholder="Description">
</div>

<div class="form-group form-inline">
<input type="text" name="award[4][0]" class="form-control" placeholder="Award ID #5">
<input type="text" name="award[4][1]" class="form-control" placeholder="Award Name">
<input type="text" name="award[4][2]" class="form-control" placeholder="Points">
<input type="text" name="award[4][3]" class="form-control" placeholder="Description">
</div>
{/if}
</div>

<button type="submit" class="btn btn-full btn-bigtxt btn-primary" style="float:left;clear:both">Submit</button>
</form>
{/if}
</div></div>
{include file="footer.tpl"}
<link href="{$settings['siteurl']}/libs/imagecrop/slim.css?v=1" rel="stylesheet">
<script src="{$settings['siteurl']}/libs/imagecrop/slim.kickstart.js?v=1"></script>
<script data-rocketsrc="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous" type="text/rocketscript"></script>
<script>
$("#inlineCheckbox2").click(function(){
    if($(this).is(":checked")){
       $("#highscorediv").show();
    }else{
        $("#highscorediv").hide();
    }
});

$("#inlineCheckbox3").click(function(){
    if($(this).is(":checked")){
       $("#awardsdiv").show();
    }else{
        $("#awardsdiv").hide();
    }
});
</script>