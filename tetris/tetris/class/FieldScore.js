class FieldScore
{
	constructor(top)
	{
		this.top = top;
		this.table = null;
		this.currentScore = 0;
		this.CreateFieldTable();
	}
	CreateFieldTable()
	{
		this.bodyField = document.createElement("div");
		this.bodyField.style.display = "inline-block";
		this.bodyField.style.position = "relative";
		this.bodyField.style.border = "1px solid green";
		//this.bodyField.style.spaccing = "0px";
		
		let table = document.createElement("table");
		table.border = 1;
		let td = document.createElement("td");
		td.style.width = "50%";
		td.style.textAlign = "center";
		let tr = document.createElement("tr");
		td.innerHTML = "ТОП";
		td.setAttribute("colspan", 2);
		tr.appendChild(td.cloneNode(true));
		td.setAttribute("colspan", 1);
		table.appendChild(tr.cloneNode(true));
		for(let i = 0; i < this.top.length; i++)
		{
			tr = document.createElement("tr");
			let name = this.top[i]["name"];
			td.innerHTML = name.replace(" ", "&nbsp;");
			td.id = "id" + i;
			tr.appendChild(td.cloneNode(true));
			td.innerHTML = this.top[i]["score"];
			td.id = "";
			tr.appendChild(td.cloneNode(true));
			table.appendChild(tr.cloneNode(true));
		}
		this.bodyField.appendChild(table);
		
		this.bodyCurrentScore = document.createElement("div");
		this.bodyCurrentScore.style.position = "absolute";
		this.bodyCurrentScore.innerHTML = "&larr;&nbsp;" + this.currentScore;
		//this.bodyCurrentScore.style.backgroundColor = "red";
		
		this.bodyField.appendChild(this.bodyCurrentScore);
	}
	get FieldTable()
	{
		return this.bodyField;
	}
	FirstRefresh()
	{
		this.bodyCurrentScore.style.left = this.bodyField.offsetWidth + "px";
		this.bodyCurrentScore.style.top = this.bodyField.offsetHeight - (this.bodyCurrentScore.offsetHeight / 2) + "px";
	}
	SetScore(value)
	{
		if(this.currentScore == value)
			return;
		this.currentScore = value;
		this.bodyCurrentScore.innerHTML = "&larr;&nbsp;" + this.currentScore;
		this.MoveArrow();
	}
	MoveArrow()
	{
		let sizeOneTop = this.bodyField.offsetHeight / (this.top.length + 1);
		let minValue = 0;
		for(let i = this.top.length - 1; i >= 0; i--)
		{
			if(this.currentScore > this.top[i]["score"])
			{
				if(i == 0)
					this.bodyCurrentScore.style.top = sizeOneTop - (this.bodyCurrentScore.offsetHeight / 2) + "px";
				let el = document.getElementById("id" + i);
				el.style.textDecoration = "line-through";
				el.style.color = "red";
				continue;
			}
			if(i < this.top.length - 1)
				minValue = this.top[i + 1]["score"];
			
			let dotInOneScore = sizeOneTop / (this.top[i]["score"] - minValue);//количество точек на одно очко
			let moveTo = (this.currentScore - minValue) * dotInOneScore;
			this.bodyCurrentScore.style.top = this.bodyField.offsetHeight - (((this.top.length - 1) - i) * sizeOneTop) - moveTo - (this.bodyCurrentScore.offsetHeight / 2) + "px";
			break;
		}
		
	}
}