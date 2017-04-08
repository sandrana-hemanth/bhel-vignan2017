
function DownloadRooster(){
	this.employees={};
	this.total=0;
	this.startDate;this.endDate;
	this.complete=false;
};
DownloadRooster.prototype.addData=function(response)
	{
		
		var json=JSON.parse(response);
		console.log("length: "+json.length);
		for(var i=0;i<json.length;i++)
		{
			
			var data=json[i];
			var id=""+data['eid'];
			if(this.employees[id] == undefined)
			{
				this.employees[id]={};
			}
			var emp=this.employees[id];
			var mon=parseInt(""+data['mon'])-1;
			var yr=parseInt(""+data['yr']);
			var dateHelper=new DateHelper(new Date((mon+1)+"/1/"+yr));
			for(var d=1;d<=dateHelper.resolveDays();d++)
			{
				this.employees[id][dateString(d,mon+1,yr)]=data[""+d];
			//	console.log(emp[dateString(d,mon+1,yr)]);
			}
			console.log("adding.. "+this.employees);
			//console.log(this.employees);
		}
		
	};
DownloadRooster.prototype.processData=function()
	{
		var st=this.startDate;
		var IDs=Object.keys(this.employees);
		console.log(this.employees);
		var table=$("<table></table>");
		var dateStrings=[];
		var sd=this.startDate;
		var mon,yr,day;
		console.log("heree.."+IDs.length);
		while(true)
		{
			mon=sd.getMonth()+1;
			yr=sd.getUTCFullYear();
			day=sd.getUTCDate();
			dateStrings.push(dateString(day,mon,yr));
			if(day==this.endDate.getUTCDate() && mon==(this.endDate.getMonth()+1) && yr==this.endDate.getUTCFullYear())
				break;
			sd=new Date(sd.getTime()+86400000);
		}
		var tr=$("<tr></tr>");
		tr.append($("<th>Employee</th>"));
		var th;
		for(var i=0;i<dateStrings.length;i++)
		{
			th=$("<th></th>").text(dateStrings[i]);
			tr.append(th);
		}
		table.append(tr);
		for(i=0;i<IDs.length;i++)
		{
			console.log("HEREEEEE");
			tr=$("<tr></tr>");
			var td=$("<td></td>").text(IDs[i]);
			tr.append(td);
			for(var j=0;j<dateStrings.length;j++)
			{
				td=$("<td></td>");
				var map=this.employees[IDs[i]];
				if(map[dateStrings[j]]==undefined)
				{
					td.text("-");
				}
				else
				{
					td.text(map[dateStrings[j]])
				}
				tr.append(td);
			}
			table.append(tr);
		}
		$("#roosterHolder").append(table);
		console.log(table);
		$(table).table2excel({
			exclude: ".noExl",
			name: "Roster Document",
			filename: "Rooster",
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});
	};
	function dateString(day,mon,yr)
	{
		return ""+day+"/"+mon+"/"+yr;
	};
	DownloadRooster.prototype.initialize=function(){
		var obj=this;
		$("#getRoosterButton").on('click',function(){
			obj.startDate=new Date($("#roosterFrom").val());
			obj.endDate=new Date($("#roosterTo").val());
			console.log(obj.startDate);
			var startMonth=obj.startDate.getMonth();
			var startYear=obj.startDate.getUTCFullYear();
			var endMonth=obj.endDate.getMonth();
			var endYear=obj.endDate.getUTCFullYear();
			var self=obj;
			while(true)
			{
				self.total++;
				$.ajax({
						  url: "/bhel/timeoffice/downloadRooster.php",
						  type: "post", 
						  data:{startMonth:startMonth,startYear:startYear},
						  success: function(response) {
							//console.log(response);
							self.addData(response);
							self.total--;
							console.log("total:: "+self.total);
						  },
						  error: function(xhr) {
							alert("got Error "+xhr);
							self.total--;
						  }
						});
				if(startMonth==endMonth && startYear==endYear)
				{
					complete=true;
					self.startPhase();
					break;
				}
					
				startMonth++;
				if(startMonth==12)
				{
					startMonth=0;
					startYear++;
				}
			}
		});
	};
	DownloadRooster.prototype.startPhase=function(){
		console.log("called phase");
		recheck(this);
	};
	
function recheck(obj)
{
	//console.log("time is .. : "+obj.total);
	if(obj.total==0)
	{
		obj.processData();
	}
	else
		setTimeout(function(){
			recheck(obj);
		},1000);
}
function DateHelper(date){
	this.date=date;
	this.mon=date.getMonth();
	this.yr=date.getFullYear();
	
};

DateHelper.prototype.resolveDays=function(){
	var days=[31, (this.isLeapYear() ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	return days[this.mon];
};
DateHelper.prototype.isLeapYear=function(){
	return ((this.yr % 4 == 0) && (this.yr % 100 != 0)) || (this.yr % 400 == 0);
};
DateHelper.prototype.getDayNum=function(day)
{
	var thisDate=new Date(this.yr+"-"+(this.mon+1)+"-"+day);
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
