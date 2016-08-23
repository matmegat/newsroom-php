<?php $__id = sprintf('chart_%s', substr(md5(microtime(true)), 0, 8)); ?>
<!--[if lt IE 9]><script src="<?= $vd->assets_base ?>lib/excanvas.min.js"></script><![endif]-->
<script src="<?= $vd->assets_base ?>lib/chart.min.js"></script>
<canvas 
	width="<?= $options->width ?>" 
	height="<?= $options->height ?>"
	id="<?= $__id ?>"></canvas>
	
<script>

$(function() {
	
	var canvas = document.getElementById(<?= json_encode($__id) ?>);
	var context = canvas.getContext("2d");
	
	var data = {
		labels: <?= json_encode($data->labels) ?>,
		datasets: [{
			fillColor: <?= json_encode($options->get_css_color('fill')) ?>,
			strokeColor: <?= json_encode($options->get_css_color('line')) ?>,
			pointColor: <?= json_encode($options->get_css_color('point')) ?>,
			pointStrokeColor: "white",
			data: <?= json_encode($data->points) ?>
		}]
	};	
	
	var options = {
		scaleFontSize: <?= json_encode($options->font_size) ?>,
		scaleFontColor: <?= json_encode($options->get_css_color('font')) ?>,
		scaleGridLineColor: <?= json_encode($options->get_css_color('grid')) ?>,
		scaleLineColor: <?= json_encode($options->get_css_color('step')) ?>,
		bezierCurve: <?= json_encode((bool) $options->curve) ?>,
		animation: false
	};
	
	var chart = new Chart(context);
	chart.Line(data, options);
	
});
	
</script>