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
                    <td><b>Wild</b></td>
                    <td>Dual</td>
                    <td>Full</td>
                    <td>Team</td>
                </thead>
                <tbody>
                <tr>
                    <td><?=$song['basic']?></td>
                    <td><?=$song['easy']?></td>
                    <td><?=$song['hard']?></td>
                    <td><?=$song['wild']?></td>
                    <td><?=$song['duel']?></td>
                    <td><?=$song['full']?></td>
                    <td><?=$song['team']?></td>
                </tr>
                </tbody>
            </table>
         </div>
    </div>
 </div>
