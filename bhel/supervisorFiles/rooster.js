function Rooster(date){
	this.currentDate=date;
	this.data=null;
	this.mon=date.getMonth();
	this.yr=date.getFullYear();
	this.dateHelper=new DateHelper(date);
	this.selff=this;
	this.selectTags=null;
}
Rooster.prototype.fillTable=function(){
				var table=$("#roosterTableBody");
				table.empty();
				var dateHelper=new DateHelper(this.currentDate);
				$(table).empty();
				if(this.data.length==0)
				{
					this.createTable(table);
					return;
				}
				var l=this.data.length;
				
				var d;
				
				//heading..
				var row=$("<tr></tr>");
				var th=$("<th ></th>").text("Emp ID");
				row.append(th);
				className="week1";
				for(var i=1;i<=dateHelper.resolveDays();i++)
				{
					if(dateHelper.getDayNum(i)==0)
					{
						//alert("got day num 0");
						className=(className=="week1")?"week2":"week1";
					}
					var th=$("<th></th>").text(""+this.toDateString(i,this.mon+1,this.yr));
					$(th).addClass(className);
					th.append($("<br>"));
					th.append($("<span></span>").text(dateHelper.getDayName(i)));
					row.append(th);
				}
				table.append(row);
				this.selectTags=new Array();
				for(var i=0;i<l;i++)
				{
					className="week1";
					var row=$("<tr></tr>");
					var td=$("<td class='tEmpID'></td>").text(""+this.data[i]["eid"]);
					row.append(td);
					var selectTagsRow=new Array();
					for(var j=1;j<=dateHelper.resolveDays();j++)
					{	
						
						if(dateHelper.getDayNum(j)==0)
						{
							className=(className=="week1")?"week2":"week1";
						}
						var td=$("<td class='rc'></td>");
						$(td).addClass(className);
						//console.log();
						if(this.canChangable(""+this.data[i][j]))
						{
							console.log(this.data[i][j]);
							selectTag=$(".shifts")[0];
							selectTag=$(selectTag).clone();
							$(selectTag).data("i",i);
							$(selectTag).data("j",j);
							var selff=this;
							$(selectTag).on('change',function(){
								var i,j;
								i=$(this).data("i");
								j=$(this).data("j");
								
								selff.data[i][j]=(""+$(this).val());
								selff.autoFill(i,j);
							});
							selectTag.val(""+this.data[i][j]);
							selectTag.css("display","inline");
							td.append(selectTag);
							row.append(td);
							selectTagsRow.push(selectTag);
						}
						else
						{
							var span=$("<span></span>").text(""+this.data[i][j]);
							td.append(span);
							row.append(td);
							selectTagsRow.push(null);
						}
					}
					this.selectTags.push(selectTagsRow);
					table.append(row);
				}
			/*	$("#commit").on('click',function(){
						//var selff=this;
						$.ajax({
						url: '/bhel/supervisorFiles/updateRooster.php',
						type: 'POST',
						data:{'emp': JSON.stringify(selff.data)},
						success: function(msg) {
							alert(msg);
						}
					});
				});*/
};

Rooster.prototype.createTable=function(table)
{
	var tr=$("<tr></tr>");
	var th1=$("<th></th>").text("NO DATA FOUND..");
	var th2=$("<th></th>");
	var but=$("<button id='createData' class='btn btn-primary'>DATA MUST BE GENERATED BY EXECUTIVE</button>");
	th2.append(but);
	tr.append(th1);
	tr.append(th2);
	table.append(tr);
};
Rooster.prototype.toDateString=function(day,month,year)
{
    var ds=day<=9?("0"+day):day;
    var ms=month<=9?("0"+month):month;
    var ys=""+year;
    return (""+ds+"_"+ms+"_"+ys);
};
Rooster.prototype.autoFill=function(i,j)
{
	if(this.dateHelper.getDayNum(j)==1)
	{
		var arr=this.selectTags[i];
		//alert(arr.length);
		var obj=arr[j-1];
		console.log(obj);
		var val=$(obj).val();
		if(val=="WO")
		{
			return;
		}
		//alert(j);
		for(var k=j;k<j+5;k++)
		{
			obj=arr[k];
			//alert($(obj).val());
			if(this.canChangable(this.data[i][k]) && this.data[i][k]!="WO")
			{
				$(obj).val(val);
				this.data[i][k]=val;
			}
		}
	}
};
Rooster.prototype.sendRoosterRequest=function(){
	var selff=this;
	$.ajax({
				  url: "/bhel/supervisorFiles/getRooster.php",
				  type: "get", 
				  data:{month:selff.mon,year:selff.yr },
				  success: function(response) {
					//alert(response);
					selff.data=JSON.parse(response);
					
					selff.fillTable();
				  },
				  error: function(xhr) {
					alert("got Error "+xhr);
				  }
				});
};

Rooster.prototype.canChangable=function(c){
	/*var changableList=["A","B","C","G","WO"];
	for(var i=0;i<changableList.length;i++)
	{
		if(this.streq(c.valueOf(),changableList[i].valueOf()))
		{
			return true;
		}
	}*/
	//AS slection is disabled no need of this function
	return true;
};
Rooster.prototype.streq=function(str1,str2)
{
	return str1==str2;
};
Rooster.prototype.downloadExcel=function()
{
	console.log("called..");
	var mtable=$("<table></table>");
	var table=$("<tbody></tbody>");
	var thead=$("<thead></thead>");
	var dateHelper=new DateHelper(this.currentDate);
	
	var l=this.data.length;
	
	var d;
	
	//heading..
	var row=$("<tr></tr>");
	var th=$("<th></th>").text("Empid");
	row.append(th);
				
	for(var i=1;i<=dateHelper.resolveDays();i++)
	{
		var th=$("<th></th>").text(""+this.toDateString(i,this.mon+1,this.yr));
		row.append(th);
	}
	thead.append(row);
	this.selectTags=new Array();
	for(var i=0;i<l;i++)
	{
		className="week1";
		var row=$("<tr></tr>");
		var td=$("<td></td>").text(""+this.data[i]["eid"]);
		row.append(td);
		var selectTagsRow=new Array();
		for(var j=1;j<=dateHelper.resolveDays();j++)
		{	
			
			var td=$("<td></td>").text(""+this.data[i][j]);
			row.append(td);
		}
		table.append(row);
		mtable.append(thead);
		mtable.append(table);
		
	}
		$(mtable).table2excel({
			exclude: ".noExl",
			name: "Roster Document",
			filename: this.mon+"_"+this.yr,
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});
}


function DateHelper(date){
	this.date=date;
	this.mon=date.getMonth();
	this.yr=date.getFullYear();
	
};

DateHelper.prototype.resolveDays=function(){
	//alert("date helper year: "+this.date);
	var days=[31, (this.isLeapYear() ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
	return days[this.mon];
};
DateHelper.prototype.isLeapYear=function(){
	return ((this.yr % 4 == 0) && (this.yr % 100 != 0)) || (this.yr % 400 == 0);
};
DateHelper.prototype.getDayNum=function(day)
{
	
	var thisDate=new Date(this.yr+"-"+(this.mon+1)+"-"+day);
	//alert(thisDate);
	return thisDate.getDay();
};
DateHelper.prototype.getDayName=function(day)
{
	var days=["SUN","MON","TUE","WED","THU","FRI","SAT"];
	return days[this.getDayNum(day)];
};
DateHelper.prototype.getMonthName=function(month){
	var mons=["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
	return mons[month];
};