<?php
/* Smarty version 3.1.29, created on 2017-01-18 15:40:48
  from "/home/gbapi/public_html/lagged/admin/templates/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_587ffd0045a417_08002805',
  'file_dependency' => 
  array (
    '10016ccbb1f9bc5e3e4872572082259f2af71951' => 
    array (
      0 => '/home/gbapi/public_html/lagged/admin/templates/index.tpl',
      1 => 1484601530,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_587ffd0045a417_08002805 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>'Admin Home'), 0, false);
?>


<div class="container">
<div class="row">
<div class="page-header">
<h3>Admin Home</h3>
</div>

<a class="btn btn-default btn-primary" href="install.php" role="button">Upload a Game</a>
<a class="btn btn-default btn-info" href="settings.php?view=feeds">Game Feeds</a>
<a class="btn btn-default btn-info" href="settings.php" role="button">Change Settings</a>

<h3>Quick Stats</h3>
<div class="row">
  <div class="col-md-6">

<div class="panel panel-default">
	<div class="panel-heading">
	<h4>Game Plays <span style="font-weight:normal;font-size:0.8em">(<?php echo number_format($_smarty_tpl->tpl_vars['gameplays']->value);?>
 total)</span></h4>
	</div>
  <div class="panel-body">
    <canvas id="myBarChart2" width="400" height="400"></canvas>
  </div>
</div>
</div>
  <div class="col-md-6">

<div class="panel panel-default">
	<div class="panel-heading">
	<h4>Sign ups <span style="font-weight:normal;font-size:0.8em">(<?php echo number_format($_smarty_tpl->tpl_vars['usercount']->value);?>
 total)</span></h4>
	</div>
  <div class="panel-body">
    <canvas id="myBarChart" width="400" height="400"></canvas>
  </div>
</div>
</div></div>

Last cron update: <?php echo insert_timedif(array('input_time' => $_smarty_tpl->tpl_vars['lastupdate']->value),$_smarty_tpl);?>


</div></div>

<?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.bundle.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>

var options = {
scaleShowLabels : false,
legend: { display: false },
tooltips: {
    callbacks: {
      // tooltipItem is an object containing some information about the item that this label is for (item that will show in tooltip). 
      // data : the chart data item containing all of the datasets
      label: function(tooltipItem, data) {
        if(tooltipItem.yLabel>999){
		return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
		}else{
		return tooltipItem.yLabel;
		}
      }
    }
  },
scales: {
yAxes: [{
ticks: {
 beginAtZero:true,
}
}]
}
};

var data = {
labels: [
<?php
$_from = $_smarty_tpl->tpl_vars['users']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_data_0_saved_item = isset($_smarty_tpl->tpl_vars['data']) ? $_smarty_tpl->tpl_vars['data'] : false;
$_smarty_tpl->tpl_vars['data'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['data']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
$_smarty_tpl->tpl_vars['data']->_loop = true;
$__foreach_data_0_saved_local_item = $_smarty_tpl->tpl_vars['data'];
?>
"<?php echo $_smarty_tpl->tpl_vars['data']->value['dater'];?>
",
<?php
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_0_saved_local_item;
}
if ($__foreach_data_0_saved_item) {
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_0_saved_item;
}
?>
],
datasets: [
{
backgroundColor:'rgba(54, 162, 235, 0.8)',
borderWidth: 1,
data: [<?php
$_from = $_smarty_tpl->tpl_vars['users']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_data_1_saved_item = isset($_smarty_tpl->tpl_vars['data']) ? $_smarty_tpl->tpl_vars['data'] : false;
$_smarty_tpl->tpl_vars['data'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['data']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
$_smarty_tpl->tpl_vars['data']->_loop = true;
$__foreach_data_1_saved_local_item = $_smarty_tpl->tpl_vars['data'];
echo $_smarty_tpl->tpl_vars['data']->value['counter'];?>
,
<?php
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_1_saved_local_item;
}
if ($__foreach_data_1_saved_item) {
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_1_saved_item;
}
?>],
}
]
};
var data2 = {
	labels: [
	<?php
$_from = $_smarty_tpl->tpl_vars['games']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_data_2_saved_item = isset($_smarty_tpl->tpl_vars['data']) ? $_smarty_tpl->tpl_vars['data'] : false;
$_smarty_tpl->tpl_vars['data'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['data']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
$_smarty_tpl->tpl_vars['data']->_loop = true;
$__foreach_data_2_saved_local_item = $_smarty_tpl->tpl_vars['data'];
?>
	"<?php echo $_smarty_tpl->tpl_vars['data']->value['dayofweek'];?>
",
	<?php
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_2_saved_local_item;
}
if ($__foreach_data_2_saved_item) {
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_2_saved_item;
}
?>
	],
datasets: [
{
backgroundColor:'rgba(54, 162, 235, 0.8)',
borderWidth: 1,
data: [<?php
$_from = $_smarty_tpl->tpl_vars['games']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_data_3_saved_item = isset($_smarty_tpl->tpl_vars['data']) ? $_smarty_tpl->tpl_vars['data'] : false;
$_smarty_tpl->tpl_vars['data'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['data']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
$_smarty_tpl->tpl_vars['data']->_loop = true;
$__foreach_data_3_saved_local_item = $_smarty_tpl->tpl_vars['data'];
echo $_smarty_tpl->tpl_vars['data']->value['play_count'];?>
,
<?php
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_3_saved_local_item;
}
if ($__foreach_data_3_saved_item) {
$_smarty_tpl->tpl_vars['data'] = $__foreach_data_3_saved_item;
}
?>],
}
]
};

var ctx = document.getElementById("myBarChart").getContext("2d");
var myBarChart = new Chart(ctx, {
type: 'bar',
data: data,
options: options
});

var ctx2 = document.getElementById("myBarChart2").getContext("2d");
var myBarChart2 = new Chart(ctx2, {
type: 'bar',
data: data2,
options: options
});
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
