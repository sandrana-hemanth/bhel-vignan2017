function Leave(emp)
{
	this.emp=emp;
	this.dates=null;
	this.dateinp=null;
}
Leave.prototype.attach=function(leaveId){
	var div=$(leaveId);
	$(div).empty();
	var table=$("<table></table>");
	var tr=$("<tr></tr>");
	var headings=['EID','CL','EL','HPL','SL','EOL','UAB','OH'];
	for(var i=0;i<headings.length;i++)
	{
		var th=$("<th></th>").text(""+headings[i]);
		$(tr).append(th);
	}
	$(table).append(tr);
	tr=$("<tr></tr>");
	
	var td=$("<td></td>").text(""+this.getEmpID());
	$(table).append(td);
	td=$("<td></td>").text(""+this.emp['CL']);
	$(table).append(td);
	td=$("<td></td>").text(""+this.emp['EL']);
	$(table).append(td);
	td=$("<td></td>").text(""+this.emp['HPL']);
	$(table).append(td);
	td=$("<td></td>").text(""+this.emp['SL']);
	$(table).append(td);
	td=$("<td></td>").text(""+this.emp['EOL']);
	$(table).append(td);
	td=$("<td></td>").text(""+this.emp['UAB']);
	$(table).append(td);
	td=$("<td></td>").text(""+this.emp['OH']);
	$(table).append(td);
	$(table).addClass("table");
	$(div).append(table);
	//this.addNumOfLeavesBar(div);
};

Leave.prototype.getEmpID=function(){
	return this.emp['id'];
}
Leave.prototype.getCasual=function(){
	return this.emp['casual'];
};
Leave.prototype.getMedical=function(){
	return this.emp['sick'];
};
Leave.prototype.getEarned=function(){
	return this.emp['earned'];
};

Leave.prototype.addNumOfLeavesBar=function(div)
{
	var label=$("<label></label>").text("Choose Dates::");
	var date=$($(".datePicker")[0]).clone();
	var inp=$($(".dateinp")[0]).clone();
	var btn=$($(".datebtn")[0]).clone();
	$(date).css('display','block');	
	$(inp).css('display','inline');	
	$(inp).attr('id','abcd');
	$(btn).css('display','inline');	
	$(date).append(inp);
	$(date).append(btn);
	
	$(div).append(label);
	$(div).append(date);
	$("#abcd").multiDatesPicker();
	this.datesInp=$(inp);
	var selff=this;
	$(btn).on('click',function(){
		
		selff.displayLeaveRows();
	});
};
Leave.prototype.displayLeaveRows=function(){
	alert($("#abcd").multiDatesPicker('getDates'));
	this.dates=$(this.dateinp).multiDatesPicker('getDates');
	var dates=this.dates;
	alert(dates.length);
};