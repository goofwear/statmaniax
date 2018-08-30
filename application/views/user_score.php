<div class="smx-ui-title">
    <h2 id="smx-title"><?=$user_info['username']?></h2>
</div>
 <div class="player-info">
    <img src="https://data.stepmaniax.com/<?=$user_info['picture_path']?>" width="100%">
     <hr>
     <h6 class="smx-font">Name: <?= $user_info['first'] ?> <?= $user_info['last'] ?></h6>
     <h6 class="smx-font">Total World Records: <span id="worldrecord-count">??</span></h6>
     <h6 class="smx-font">Country: <?= $user_info['country'] ?></h6>
     <h6 class="smx-font">Total: <?= number_format($user_info['total_score']) ?></h6>
 </div>


 <div class="container">

     <table class="table table-dark" data-sorting="true" data-paging="true">
         <thead class="smx-font">
         <tr>
             <th>Song Title</th>
             <th>Artist</th>
             <th>Score</th>
             <th>Grade</th>
             <th data-type="number">World Record</th>
             <th data-type="date">Date</th>
         </thead>
         </tr>
         <tbody>
         <?php

         $wr = 0;
         foreach ($user_scores as $key => $score) {


             #print_r($score);

             $world = $world_scores[$key]['score'];
             $score_points = $score['score'];
             $delta = $world - $score_points;

             $deltadisplay = "<span class='delta-nowr'>(-" . $delta . ")</span>";

             if ($delta == 0) {
                 $wr += 1;
                 $deltadisplay = "<span class='delta-wr'>(WR)</span>";
                 echo "<tr class='wr smx-font'>";
             } else {
                 echo "<tr class='smx-font'>";
             }
             /*
             echo "<tr>";
             echo "<td>".$score['title']."</td>";
             echo "<td>".$score['artist']."</td>";
             echo "<td>".$score['score']."</td>";
             echo "<td>".$world."</td>";
             echo "<td>".$score['created_at']."</td>";
             echo "</tr>";

             <?=base_url('song/' . $this->data->get_song_id_by_title($score['title']))?>
             ^ don't use, slow. needs optimisation
             */

             ?>

             <td class="truncate-playerui"><?= $score['title'] ?> </td>
             <td class="truncate-playerui"><?= $score['artist'] ?></td>
             <td data-toggle="tooltip" data-placement="bottom" data-html="true" title="Grading:
Perfect!!: <?= $score['perfect1'] ?><br/>Perfect!: <?= $score['perfect2'] ?>
<br/>Early: <?= $score['early'] ?>
<br/>Late: <?= $score['late'] ?>
<br/>Miss: <?= $score['misses'] ?>"><?= $score['score'] ?> <?= $deltadisplay ?></td>
             <td><img src="<?= $this->data->gradetostars($score['grade']) ?>" width="35px"></td>
             <td><?= $world ?></td>
             <td><?= $score['created_at'] ?></td>
             </tr>
             <?php

         }


         ?>

         </tbody>
     </table>


 </div>










<script>

    var wr = <?=$wr?>;

jQuery(function($){
	$('.table').footable({
		"expandFirst": false,
		"showToggle": false
	});
});

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    document.getElementById("worldrecord-count").innerHTML = wr
</script>
