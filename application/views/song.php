<div class="smx-ui-title">
    <h2 id="smx-title">SONG</h2>
</div>
 <div class="container-fluid">
    <div class="row">
        <div class="col-md-3 smx-cover-pers">
            <img class="smx-cover" src="https://data.stepmaniax.com/<?= $song['cover_path'] ?>/cover.png" width="100%">
            <div style="padding-top: 25px"></div>
            <h1><?=$song['title']?></h1>
            <h4><?=$song['artist']?> // <?=$song['genre']?></h4>
             <table class="table">
                <thead>
                    <td><b>Basic</b></td>
                    <td><b>Easy</b></td>
                    <td><b>Hard</b></td>
                    <td style="border-right: 1px white solid;"><b>Wild</b></td>
                    <td>Dual</td>
                    <td>Full</td>
                    <td>Team</td>
                </thead>
                <tbody>
                <tr>
                    <td><?=$song['basic']?></td>
                    <td><?=$song['easy']?></td>
                    <td><?=$song['hard']?></td>
                    <td style="border-right: 1px white solid;"><?= $song['wild'] ?></td>
                    <td><?=$song['duel']?></td>
                    <td><?=$song['full']?></td>
                    <td><?=$song['team']?></td>
                </tr>
                </tbody>
            </table>
         </div>
        <div class="col-md-9">
            <table class="table table-dark" data-sorting="true">
                <thead>
                <th>Player</th>
                <th data-type="number">Score</th>
                <th data-type="number">Grade</th>
                <th data-type="date">Date</th>
                </thead>
                <tbody>
                <?php

                foreach ($scores as $score) { ?>

                    <tr>
                        <td></td>
                        <td data-toggle="tooltip" data-placement="bottom" data-html="true" title="Grading:
Perfect!!: <?= $score['perfect1'] ?><br/>Perfect!: <?= $score['perfect2'] ?>
<br/>Early: <?= $score['early'] ?>
<br/>Late: <?= $score['late'] ?>
<br/>Miss: <?= $score['misses'] ?>"><?= $score['score'] ?></td>
                        <td><?= $score['grade'] ?></td>
                        <td><?= $score['created_at'] ?></td>
                    </tr>

                <?php }


                ?>

                </tbody>
            </table>

        </div>
    </div>
 </div>

<script>
    jQuery(function ($) {
        $('.table').footable({
            "expandFirst": false,
            "showToggle": false
        });
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>