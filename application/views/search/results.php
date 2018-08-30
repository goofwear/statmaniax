<div class="smx-ui-title">
    <h2 id="smx-title">SEARCH</h2>
</div>


<div class="container">

    <form method="post">

        <div class="row">
            <div class="col-10">
                <input class="form-control form-control-lg" name="query" placeholder="Search for a player...">
            </div>
            <div class="col-2">
                <button type="submit" name="search" class="btn btn-lg btn-success">Search!</button>
            </div>
        </div>

    </form>
    <hr>
    <div class="userlist-ui" style="margin-top: 0">

        <?php foreach ($results as $user):
            if (isset($user['picture_path'])):
                $userid = $this->data->parse_picture_path($user['picture_path']);
                ?>

                <div class="userlist-user">
                    <div class="row">
                        <div class="col-2">
                            <img class="hide-on-small" src="https://data.stepmaniax.com/<?= $user['picture_path'] ?>"
                                 width="100">
                        </div>
                        <div class="col-10">
                            <div class="smx-userlist-container">
                                <div class="center smx-font">
                                    <a href="player/<?= $userid ?>"><h2><?= $user['username'] ?></h2></a>
                                    <p><?= number_format($user['total_score']) ?> Acc. Points</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            <?php endif; endforeach; ?>

        <div>

        </div>

