{include file="header.tpl" title='Admin Home'}

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
	<h4>Game Plays <span style="font-weight:normal;font-size:0.8em">({$gameplays|number_format} total)</span></h4>
	</div>
  <div class="panel-body">
    <canvas id="myBarChart2" width="400" height="400"></canvas>
  </div>
</div>
</div>
  <div class="col-md-6">

<div class="panel panel-default">
	<div class="panel-heading">
	<h4>Sign ups <span style="font-weight:normal;font-size:0.8em">({$usercount|number_format} total)</span></h4>
	</div>
  <div class="panel-body">
    <canvas id="myBarChart" width="400" height="400"></canvas>
  </div>
</div>
</div></div>

Last cron update: {insert name="timedif" input_time=$lastupdate}

</div></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.bundle.js"></script>
<script>
{literal}
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
{/literal}
var data = {
labels: [
{foreach $users as $data}
"{$data.dater}",
{/foreach}
],
datasets: [
{
backgroundColor:'rgba(54, 162, 235, 0.8)',
borderWidth: 1,
data: [{foreach $users as $data}
{$data.counter},
{/foreach}],
}
]
};
var data2 = {
	labels: [
	{foreach $games as $data}
	"{$data.dayofweek}",
	{/foreach}
	],
datasets: [
{
backgroundColor:'rgba(54, 162, 235, 0.8)',
borderWidth: 1,
data: [{foreach $games as $data}
{$data.play_count},
{/foreach}],
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
</script>
{include file="footer.tpl"}