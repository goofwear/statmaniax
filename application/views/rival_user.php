<?php
$win = 0;
$loss = 0;
?>

<div class="smx-ui-title">
    <button type="button" class="hamburger animated fadeInLeft is-closed" data-toggle="offcanvas">
        <span class="hamb-top"></span>
        <span class="hamb-middle"></span>
        <span class="hamb-bottom"></span>
    </button>
    <h2 id="smx-title">COMPARE</h2>
</div>

<div class="player-info">
    <h6 class="smx-font">User: <a style="color: white; text-decoration: underline"
                                  href="<?= base_url('player/' . $user_info['id']) ?>"><?= $user_info['username'] ?></a>
    </h6>
    <h6 class="smx-font">Comparing to: <a style="color: white; text-decoration: underline"
                                          href="<?= base_url('player/' . $rival_info['id']) ?>"><?= $rival_info['username'] ?></a>
    </h6>
    <hr>
    <h6 class="smx-font">Win/Loss: <span id="win">?</span>:<span id="loss">?</span></h6>
</div>

<div class="container">


    <p class="smx-font" style="color: white">Currently displaying scores for
        <select onchange="setDifficulty();" id="difficulty">
            <option <?php if ($diff == "basic"): ?> selected="selected" <?php endif; ?> value="basic">Basic
            </option>
            <option <?php if ($diff == "easy"): ?> selected="selected" <?php endif; ?> value="easy">Easy
            </option>
            <option <?php if ($diff == "hard"): ?> selected="selected" <?php endif; ?> value="hard">Hard
            </option>
            <option <?php if ($diff == "wild"): ?> selected="selected" <?php endif; ?> value="wild">Wild
            </option>
            <option <?php if ($diff == "dual"): ?> selected="selected" <?php endif; ?> value="dual">Dual
            </option>
            <option <?php if ($diff == "full"): ?> selected="selected" <?php endif; ?> value="full">Full
            </option>
            <option <?php if ($diff == "team"): ?> selected="selected" <?php endif; ?> value="team">Team
            </option>
        </select>
        mode. Select another difficulty to view scores for it.
    </p>


    <table class="table table-dark" data-sorting="true" data-paging="false" data-paging-size="25">
        <thead class="smx-font">
        <tr>
            <th>Song Title</th>
            <th>Artist</th>
            <th>Level</th>
            <th data-type="number">Score</th>
            <th data-type="number">Delta</th>
            <th data-type="number">Rival</th>
            <th data-type="date">Date</th>
        </thead>
        </tr>
        <tbody>
        <?php

        $wr = 0;
        foreach ($user_scores as $key => $score) {


            #print_r($score);

	    if (isset($rival_scores[$key]))
            	$world = $rival_scores[$key]['score'];
	    else
		$world = 0;
            $score_points = $score['score'];
            $delta = $score_points - $world;

            $deltadisplay = "<span>" . $delta . "</span>";

            if ($delta >= 0) {
                $win += 1;
                echo "<tr class='rival-pos smx-font'>";
            } else {
                $loss += 1;
                echo "<tr class='rival-neg smx-font'>";
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

            <td class="truncate-playerui"><a style="color: white; text-decoration: underline"
                                             href="<?= base_url('song/' . $score['game_song_id'] . "/" . $score['name']) ?>"><?= $score['title'] ?></a>
            </td>
            <td class="truncate-playerui"><?= $score['artist'] ?></td>
            <td><?= $score[$score['name']] ?></td>
            <td data-toggle="tooltip" data-placement="bottom" data-html="true" title="Grading:
Perfect!!: <?= $score['perfect1'] ?><br/>Perfect!: <?= $score['perfect2'] ?>
<br/>Early: <?= $score['early'] ?>
<br/>Late: <?= $score['late'] ?>
<br/>Miss: <?= $score['misses'] ?>"><?= $score['score'] ?></td>
            <td><?= $deltadisplay ?></td>
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

    var win = <?=$win?>;
    var loss = <?=$loss?>;

    jQuery(function ($) {
        $('.table').footable({
            "expandFirst": false,
            "showToggle": false
        });
    });


    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    document.getElementById("win").innerHTML = win;
    document.getElementById("loss").innerHTML = loss;

    function setDifficulty() {
        var diff = document.getElementById("difficulty").value;
        window.location = "<?=base_url('player/' . $userid . '/compare/' . $rivalid)?>/" + diff;
    }
</script>
