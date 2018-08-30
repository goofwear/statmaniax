<div class="smx-ui-title">
    <button type="button" class="hamburger animated fadeInLeft is-closed" data-toggle="offcanvas">
        <span class="hamb-top"></span>
        <span class="hamb-middle"></span>
        <span class="hamb-bottom"></span>
    </button>
    <h2 id="smx-title">SONG LIST</h2>
</div>


<div class="container-fluid">

    <div class="row">
        <?php foreach ($songs as $song): ?>
            <div class="col-md-2">
                <div class="song" style="cursor: pointer;" onclick="window.location='<?=base_url('song/' . $song['game_song_id'])?>';">
                    <img src="https://data.stepmaniax.com/<?= $song['cover_path'] ?>/cover.png" width="100%">
                    <h4 class="truncate smx-font"><?= $song['title'] ?></h4>
                    <h6 class="truncate"><?= $song['artist'] ?></h6>
                    <h6><?php if(isset($song['genre'])) echo $song['genre']; else; echo "</br>"; ?></h6>
                    <hr>
                    <h5><?= $song['bpm'] ?> BPM</h5>
                    <h6 class="truncate"><a href="https://<?= $song['website'] ?>"><small><?= $song['website'] ?></small></a> </h6>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

