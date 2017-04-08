function Employee(response)
{
	this.respone=response;
	console.log("\nResponse:: "+response+"\n");
	try{
		this.emp=JSON.parse(response);
	}
	catch(e){
		this.emp=null;
		console.log(response);
	}
}
Employee.prototype.display=function(){
	
	document.getElementById("empSearchTable").innerHTML="";
	if(this.emp==null)
	{
			console.log("error");
			alert("No such Employee under current Supervisor");
			return;
	}
	$("#employeeSearchData").show();
	document.getElementById("Searchempid").innerHTML=""+this.emp.id;
	this.fillTable();
}
Employee.prototype.fillTable=function(){
	var tr=$("<tr></tr>");
	var leaves=['CL','EL','HPL','SL','EOL','UAB','OH'];
	for(var i=0;i<leaves.length;i++)
	{
		var th=$("<th></th>").text(leaves[i]);
		tr.append(th);
	}
	$("#empSearchTable").append(tr);
	tr=$("<tr></tr>");
	tr.append($("<td></td>").text(this.emp.CL));
	tr.append($("<td></td>").text(this.emp.EL));
	tr.append($("<td></td>").text(this.emp.HPL));
	tr.append($("<td></td>").text(this.emp.SL));
	tr.append($("<td></td>").text(this.emp.EOL));
	tr.append($("<td></td>").text(this.emp.UAB));
	tr.append($("<td></td>").text(this.emp.OH));
	$("#empSearchTable").append(tr);
	
}