<?php
	header('Content-type: text/html; charset=utf-8');
	session_start();
	$_SESSION["new_game"] = "new";
	
	$score = null;
	if(file_exists("score.sc") == true)
	{
		$file = fopen("score.sc", "r");
		$score = json_decode(fgets($file), true);
	}
	else
	{
		$file = fopen("score.sc", "w");
		$score = array();
		for($i = 0; $i < 10; $i++)
		{
			$score []= array("name" => "пусто", "score" => 0);
		}
		$score[0]["name"] = "новичёк";
		$score[0]["score"] = 1000;
		fwrite($file, json_encode($score));
	}
	fclose($file);
?>
<!DOCTYPE html>
<html>
    <head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/menu.css" />
        <title>Тетрис</title>
    </head>
    <body>
        <h1 align="center" class="header">Клёвая Игра Тетрис</h1>
		<div align="center">
		<a href="game.php?width=10&height=21">Играть</a>
		</div>
		<br><br>
		<table>
			<caption>РЕКОРДЫ</caption>
			<tr>
				<td>
					ИМЯ
				</td>
				<td>
					ОЧКИ
				</td>
			</tr>
			<?php
				if($score != null)
				{
					for($i = 0; $i < count($score); $i++)
					{
						echo "<tr>";
						echo "<td>" . $score[$i]["name"] . "</td>";
						echo "<td>" . $score[$i]["score"] . "</td>";
						echo "</tr>";
					}
				}
			?>
		</table>
    </body>
</html>