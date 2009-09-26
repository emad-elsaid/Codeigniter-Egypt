<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"width" : {"type":"number", "default":"400" },
	"height" : {"type":"number", "default":"250" },
	"plot" : "following paramters are the defualt plot paramters",
	"drawingType": {"type":"dropdown", "options":{
				    "Areas":"Areas",
			        "Bars":"Bars",
			        "ClusteredBars":"ClusteredBars",
			        "ClusteredColumns":"ClusteredColumns",
			        "Columns":"Columns",
			        "Grid":"Grid",
			        "Lines":"Lines",
			        "Markers":"Markers",
			        "MarkersOnly":"MarkersOnly",
			        "Pie":"Pie",
			        "Scatter":"Scatter",
			        "Stacked":"Stacked",
			        "StackedAreas":"StackedAreas",
			        "StackedBars":"StackedBars",
			        "StackedColumns":"StackedColumns",
			        "StackedLines":"StackedLines"
					}
				},
	"lines" : {"type":"checkbox", "default":true},
	"areas" : {"type":"checkbox", "default":false},
	"markers" : {"type":"checkbox", "default":false},
	"tension" : {"type":"number", "default":0},
	"gap" : {"type":"number", "default":5},
	"shadow" : "lines shadow offset below",
	"dx" : {"type":"number", "default":0},
	"dy" : {"type":"number", "default":0},
	"dw" : {"type":"number", "default":0},
	"grid lines tip" : "you can show/hide grid lines from the following variables",
	"hMajorLines" : {"type":"checkbox", "default":true},
	"hMinorLines" : {"type":"checkbox", "default":false},
	"vMajorLines" : {"type":"checkbox", "default":true},
	"vMinorLines" : {"type":"checkbox", "default":false},
	"data serieses" : "you can enter you data serieses here every series in a line, every series consist of numbers separated by comma ex: 1, 2, 0.5, 1.5, 1, 2.8, 0.4",
	"serieses": {"type":"textarea"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php
$serieses = '';
$exp_ser = array_map( 'trim', explode( "\n", trim($info->serieses) ));
foreach ($exp_ser as $index=>$value) 
{
	$serieses .= "c.addSeries('Series $index', [$value]);\n";
}

add("dojox.charting.Chart2D");
add( <<<EOT
<script type="text/javascript">
        dojo.addOnLoad(function() {
            var c = new dojox.charting.Chart2D("chart{$id}");
            
            c.addPlot("default", {
				type:		"{$info->drawingType}",
				lines:		{$info->lines},
				areas:		{$info->areas},
				markers:	{$info->markers},
				tension:	{$info->tension},
				gap:		{$info->gap},
				shadows:	{dx: {$info->dx}, dy: {$info->dy}, dw: {$info->dw}}
				});
            
            c.addAxis("x");
            c.addAxis("y", { vertical: true });
            c.addPlot("Grid", {
				type:		"Grid",
				hAxis:		"x",
				vAxis:		"y",
				hMajorLines:	{$info->hMajorLines},
				hMinorLines:	{$info->hMinorLines},
				vMajorLines:	{$info->vMajorLines},
				vMinorLines:	{$info->vMinorLines}
			});
			
			$serieses
            c.render();
        });
</script>
EOT
);
?>
<div id="chart<?=$id?>" style="width: <?=$info->width?>px; height: <?=$info->height?>px;"></div>
<?php } ?>
