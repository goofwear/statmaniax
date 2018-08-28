<div class="smx-ui-title">
	<h2 id="smx-title">USER LIST</h2>
</div>


<div class="player-info">
    <p class="pi-text">BetterSMX is a alternate implementation of StepmaniaX's score UI, including full user information, deltas, world record identification and more. Data is loaded from a cached version of the StepmaniaX API, so updates to scores may be delayed. If you want to update your score, click the "Refresh" button and the server will download the latest data from SMX. This will take 2 to 3 minutes and will require a full page refresh.</p>
</div>
 <div class="container">
	<div class="userlist-ui">

<?php foreach($users as $user): 
	if (isset($user['picture_path'])):
		$userid = $this->data->parse_picture_path($user['picture_path']);
?>

		<div class="userlist-user">
			<div class="row">
				<div class="col-md-2">
					<img src="https://data.stepmaniax.com/<?=$user['picture_path']?>" width="100">
				</div>
				<div class="col-md-9">
					<a href="scores/<?=$userid?>"><h2 class="smx-userlist-name"><?=$user['username']?></h2></a>
                        		<p class="smx-userlist-additional"><?=number_format($user['total_score'])?> Acc. Points</p>
				</div>
			</div>
	</div>


<?php endif; endforeach; ?>

	<div>
</div>
