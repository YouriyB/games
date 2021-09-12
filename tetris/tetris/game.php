<?php
	header('Content-type: text/html; charset=utf-8');
	require("class/GameProcess.php");
	session_start();
	if(isset($_SESSION["new_game"]) == false || isset($_GET["width"]) == false || isset($_GET["height"]) == false)
		header("Location: index.php");
?>
<!DOCTYPE html>
<html>
    <head>
    	<meta charset="utf-8">
    	<title>Тетрис</title>
    	<link rel="stylesheet" type="text/css" href="css/game.css" />
		<script src="class/NextFigure.js"></script>
		<script src="class/FieldScore.js"></script>
    </head>
    <body>
    	<h1 id="error" align="center">ИГРАЙ</h1>
    	<script>
    		let widthArea = 0;//количество колонок
    		let heightArea = 0;//количество строк
    	</script>
			<?php
				//проверяем на начало новой игры
				if($_SESSION["new_game"] == "new")
				{
					$_SESSION["new_game"] = "old";
					//создаём игровое поле
					$_SESSION["game"] = new GameProcess($_GET["width"], $_GET["height"]);
				}
				
				echo "<script>";
				echo "widthArea = " . $_GET["width"] . ";";
				echo "heightArea = " . $_GET["height"] . ";";
				echo "</script>";
    		?>
		<table class="screen">
			<tr>
				<td class="backMenu">
					<a href="index.php">Назад в МЕНЮ</a>
				</td>
				<td  rowspan="2">
					<table id="gameField" align="center"></table>
				</td>
				<td class="nextFigure">
					<div>следующая фигура :<span id="idNextFigure"></span></div>
				</td>
			</tr>
			<tr>
				<td class="score" align="center">
					<div id="idScore"></div>
				</td>
				<td class="help">
					<div>
						перемещение фигуры : стрелками<br>
						вращение против часовой стрелки : "Z"<br>
						вращение по часовой стрелке : "X"<br>
						пауза : пробел
					</div>
				</td>
			</tr>
		</table>
    	<script>
    		document.addEventListener("DOMContentLoaded", LoadAllElements);
			document.addEventListener("keydown", KeyDown);
			//document.addEventListener("keyup", KeyUp);
			
			let PrototypeParse = null;
			let speedGameProcess = 50;
			let actionKeyForSend = ["none", "none", "none"];
			//let actionKeySecond = ["none", "none"];
			let pause = false;
			
			let table = document.getElementById("gameField");
			let score = document.getElementById("idScore");
			let nextFigure = document.getElementById("idNextFigure");
			
			let fieldNextFigure = new NextFigure();
			let fieldScore = null;
			
			//связь с сервером
    		function NewAjax()
    		{
				let ajax = null;
				try
				{
					ajax = new XMLHttpRequest();
				}
				catch(ex)
				{
					try
					{
						ajax = new ActiveXObject("Microsoft.XMLHTTP");
					}
					 catch (ex)
					{
						ajax = new ActiveXObject("Msxml2.XMLHTTP");
					}
				}
				if(ajax)
				{
					ajax.onreadystatechange = function()
						{
							if(ajax.readyState == 4 && ajax.status == 200)
							{
								if(PrototypeParse != null)
								{
									PrototypeParse(ajax.responseText);
								}
							}
						};
				}
				return ajax;
			}
			function SendData(path)
			{
				let ajax = NewAjax();
				ajax.open("GET", path, true);
				ajax.send(null);
			}
			//закончилась связь с сервером
			
			function LoadAllElements()
			{
				CreateGameField();
				nextFigure.appendChild(fieldNextFigure.Field);
				PrototypeParse = parseGetScore;
				SendData("request/GetTopScore.php");
			}
			function CreateGameField()
			{
	    		let tr = document.createElement("tr");
	    		let td = document.createElement("td");
	    		for(let j = 0; j < widthArea; j++)
	    		{
					tr.appendChild(td.cloneNode(true));
				}
	    		for(let i = 0; i < heightArea; i++)
	    		{
					table.appendChild(tr.cloneNode(true));
				}
			}
			function GameProcess()
			{
				PrototypeParse = parseCompleteStep;
				SendData("request/StepGame.php?data=" + JSON.stringify(actionKeyForSend));
			}
			function KeyDown(event)
			{
				switch(event.keyCode)
				{
					case 37://лево
						{
							actionKeyForSend[1] = "left";
							//actionKeySecond[1] = "left";
						}
						break;
					case 39://право
						{
							actionKeyForSend[1] = "right";
							//actionKeySecond[1] = "right";
						}
						break;
					case 40://вниз
						{
							actionKeyForSend[0] = "down";
							//actionKeySecond[0] = "down";
						}
						break;
					case 90://Z
						actionKeyForSend[2] = "counterclock";
						break;
					case 88://X
						actionKeyForSend[2] = "clock";
						break;
					case 32://SPACE
						GamePause();
						break;
				}
			}
			/*function KeyUp(event)
			{
				switch(event.keyCode)
				{
					case 37:
					case 39:
						actionKeySecond[1] = "none";
						break;
					case 40:
						actionKeySecond[0] = "none";
						break;
				}
			}*/
			function GamePause()
			{
				let error = document.getElementById("error");
				if(pause == false)
				{
					pause = true;
					error.innerHTML = "ПАУЗА";
				}
				else
				{
					pause = false;
					error.innerHTML = "ИГРАЙ";
					setTimeout(GameProcess, speedGameProcess);
				}
			}
			function parseGetScore(request)
			{
				fieldScore = new FieldScore(JSON.parse(request));
				score.appendChild(fieldScore.FieldTable);
				fieldScore.FirstRefresh();
				setTimeout(GameProcess, speedGameProcess);
			}
			function parseCompleteStep(request)
			{
				if(request == "gameover" || request == "givemename")
				{
					let error = document.getElementById("error");
					error.innerHTML = "ИГРА ОКОНЧЕНА";
					
					PrototypeParse = parseGameOver;
					if(request == "givemename")
					{
						let nameToTop = prompt("Введите Имя :");
						if(nameToTop == null || nameToTop == "")
						{
							parseGameOver("no");
							return;
						}
						SendData("request/SendName.php?name=" + nameToTop);
					}
					else
						SendData("request/GetScore.php");
					return;
				}
				let result = JSON.parse(request);
				
				if(result[1][0] == "do")
					actionKeyForSend[0] = "none"
				if(result[1][1] == "do")
					actionKeyForSend[1] = "none";
				if(result[1][2] == "do")
					actionKeyForSend[2] = "none";
					
				ShowGameArea(result[0]);
				fieldScore.SetScore(result[2]);
				
				if(result[3] != fieldNextFigure.Type)
				{
					fieldNextFigure.Create(result[3], result[4]);
				}
				if(pause == false)
					setTimeout(GameProcess, speedGameProcess);
			}
			function parseGameOver(request)
			{
				if(request != "no")
					alert("Вашы очки : "  + request);
				document.location.replace("index.php");
			}
			function ShowGameArea(field)
			{
				for(let i = 0; i < field.length - 1; i++)
				{
					for(let j = 1; j < field[i].length - 1; j++)
					{
						switch(field[i][j])
						{
							case 0:
								table.rows[i].cells[j - 1].className = "empty_cell";
								break;
							case 1:
								table.rows[i].cells[j - 1].className = "fill_cell";
								break;
							case 2:
								table.rows[i].cells[j - 1].className = "figure_cell";
								break;
							case 4:
								table.rows[i].cells[j - 1].className = "del_cell";
								break;
							default:
								table.rows[i].cells[j - 1].className = "full_cell";
								break;
						}
					}
				}
			}
    	</script>
    </body>
</html>