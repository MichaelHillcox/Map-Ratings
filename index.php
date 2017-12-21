<?php
    require __DIR__."/app/funcs.php"; // hate this but no need for a full class

    $config = require(__DIR__."/app/config.php");
    const supported = ['cod', 'cod2', 'cod4', 'codww', 'cod6', 'blackops','urbanterror', 'etqw', 'q3', 'sof2', 'bc2', 'moh', 'bf3', 'homefront', 'off'];

    // check for issues in the config.
    if( !in_array($config['game'], supported) )
        showError( "Sorry but that game isn't supported" );

    try {
		$db = new PDO(sprintf("mysql:host=%s;dbname=%s", $config['db']['host'], $config['db']['name']),
			$config['db']['user'],
			$config['db']['pass']);
	} catch( PDOException $ex ) {
		showError("Looks like there has been a database error");
	}

	$req = $db->query("SELECT map, likes, dislikes, (likes + dislikes) AS total FROM {$config['db']['table']} ORDER BY likes DESC");
	$votes = $req->fetchAll();

	$totalMaps = number_format($req->rowCount());
	$totalVotes = number_format(array_sum( array_column($votes, "total") ));
?>

<!DOCTYPE html>
<html lang="en-US">
	<head>
		<title><?= $config['title'] ?></title>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="CoD4 Map Popularity votes.">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
		<link rel="stylesheet" href="app/assets/master.css">
	</head>
	<body>
		<main>
			<header>
				<h1><?= $config['title'] ?></h1>
				<div class="stats">Showing <span><?= $totalVotes ?></span> votes on <span><?= $totalMaps ?></span> maps</div>
			</header>
			<div id="maps">
				<?php foreach ($votes as $vote): ?>
					<div class="item noimg">
						<div class="details">
							<h2><?= formatName($vote['map']) ?></h2>
							<p><?= $vote['map'] ?></p>
							<div class="votes">
								<div class="upvotes"><?= $vote['likes'] ?></div>
								<div class="downvotes"><?= $vote['dislikes'] ?></div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</main>
        <footer>
            <div class="maker" title="Coded with love by Michael Hillcox"><a href="https://github.com/MichaelHillcox/Map-Ratings">&lt;&bsol;&gt; with &hearts;</a> by <a href="http://michaelhillcox.me">Michael Hillcox</a></div>
        </footer>
	</body>
</html>
