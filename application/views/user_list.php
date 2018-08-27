<?php



foreach($users as $user){
	if (isset($user['picture_path'])){


		
		$userid = $this->data->parse_picture_path($user['picture_path']);
		echo '<a href="scores/'.$userid.'">';  
		echo $user['username'];
		echo '<img src="https://data.stepmaniax.com/'.$user['picture_path'].'" width="100"></a>';
		echo "<br>";
		
	}



}


?>
