<main role="main" class="container">

<div class="starter-template">

<h2><?php echo $user_info['username']; ?></h2>
<div>
<img src="https://data.stepmaniax.com/<?php echo $user_info['picture_path']?>">
</div>

<table class="table" data-sorting="true">
<thead>
	<th data-breakpoints="all" data-title="">Cover</th>
	<th>Song Title</th>
	<th>Artist</th>
	<th data-type="number">Score</th>
	<th data-type="number">World Record</th>
	<th data-type="number">Delta</th>
	<th data-type="date">Date</th>
</thead>
<tbody>
<?php

$wr = 0;
foreach($user_scores as $key=>$score){



	#print_r($score);

	$world = $world_scores[$key]['score'];
	$score_points = $score['score'];
	$delta = $world-$score_points;

	if ($delta == 0){
		$wr+=1;
	}


	 echo "<tr>";
	 echo "<td><img src=\"https://data.stepmaniax.com/".$score['cover_path']."cover.png\">";
	 echo "<td>".$score['title']."</td>";
	 echo "<td>".$score['artist']."</td>";
         echo "<td>".$score['score']."</td>";
         echo "<td>".$world."</td>";
         echo "<td>".$delta."</td>";
	 echo "<td>".$score['created_at']."</td>";
	 echo "</tr>";

}
echo "Total World Records: ".$wr;



?>

</tbody>
</table>



</div>

</main><!-- /.container -->

<pre>





<script>
jQuery(function($){
	$('.table').footable({
		"expandFirst": false,
		"showToggle": false
	});
});
</script>
