<div class="smx-ui-title">
    <button type="button" class="hamburger animated fadeInLeft is-closed" data-toggle="offcanvas">
        <span class="hamb-top"></span>
        <span class="hamb-middle"></span>
        <span class="hamb-bottom"></span>
    </button>
	<h2 id="smx-title">USER LIST</h2>
</div>



 <div class="container">
	<div class="userlist-ui">

<?php foreach($users as $user): 
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
    </div>

