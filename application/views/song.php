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
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Tip:</strong> Hover over your score to view detailed judgement information.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="hiscores-tab" data-toggle="tab" href="#hiscores" role="tab"
                       aria-controls="hiscores" aria-selected="true">Hi-Scores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab"
                       aria-controls="history" aria-selected="false">History</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="hiscores" role="tabpanel" aria-labelledby="hiscores-tab">
                    <table class="table table-dark" data-sorting="true" data-paging="true">
                        <thead>
                        <th>Player</th>
                        <th data-type="number">Score</th>
                        <th>Grade</th>
                        <th data-type="date">Date</th>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($scores as $score) { ?>

                            <tr>
                                <td>
                                    <a href="<?= base_url('player/' . $score['gamer_id']) ?>"><?= $score['username'] ?></a>
                                </td>
                                <td data-toggle="tooltip" data-placement="bottom" data-html="true" title="Grading:
Perfect!!: <?= $score['perfect1'] ?><br/>Perfect!: <?= $score['perfect2'] ?>
<br/>Early: <?= $score['early'] ?>
<br/>Late: <?= $score['late'] ?>
<br/>Miss: <?= $score['misses'] ?>"><?= $score['score'] ?></td>
                                <td><img src="<?= $this->data->gradetostars($score['grade']) ?>" width="35px"></td>
                                <td><?= $score['created_at'] ?></td>
                            </tr>

                        <?php }


                        ?>

                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <table class="table table-dark" data-sorting="true" data-paging="true">
                        <thead>
                        <th>Player</th>
                        <th data-type="number">Score</th>
                        <th>Grade</th>
                        <th data-type="date">Date</th>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($score_history as $score) { ?>

                            <tr>
                                <td>
                                    <a href="<?= base_url('player/' . $score['gamer_id']) ?>"><?= $score['username'] ?></a>
                                </td>
                                <td data-toggle="tooltip" data-placement="bottom" data-html="true" title="Grading:
Perfect!!: <?= $score['perfect1'] ?><br/>Perfect!: <?= $score['perfect2'] ?>
<br/>Early: <?= $score['early'] ?>
<br/>Late: <?= $score['late'] ?>
<br/>Miss: <?= $score['misses'] ?>"><?= $score['score'] ?></td>
                                <td><img src="<?= $this->data->gradetostars($score['grade']) ?>" width="35px"></td>
                                <td><?= $score['created_at'] ?></td>
                            </tr>

                        <?php }


                        ?>

                        </tbody>
                    </table>
                </div>
            </div>


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