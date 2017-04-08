
function Hierarchy(){
	this.executives={};
	this.tree=$("<ul></ul>");
	this.loadData();
	this.data;
	$("#addNewExecutiveButton").unbind('click');
	$("#addNewExecutiveButton").on('click',function(){
		//alert("clicked");
		$(".hid").hide();
		$("#hierarchyPanel").show();
	});
	var self=this;
	$("#HPanelAddButton").unbind('click');
	$("#HPanelAddButton").on('click',function(){
		var form=document.getElementById("HPanelAddNewExecutiveForm");
		if(form['id'].value.length==0)
			return;
		console.log($(form).serialize());
		//self.addExecutive($(form).serializeObject());
		$.ajax({	  url: "/bhel/admin/helper/addRecord.php",
					  type: "post", 
					  data: $(form).serialize(),
					  success: function(response) {
						  console.log(response);
						  if(response=="success")
						  {
							  self.addExecutive($(form).serializeObject());
						  }
					  },
					  error: function(xhr) {
						alert("failed to add supervisor");
					  }
					});
	});
};
//static varaibles
Hierarchy.executiveAssoc={};
Hierarchy.supervisorAssoc={};
Hierarchy.employeeAssoc={};

Hierarchy.prototype.loadData=function(){
	var self=this;
	$.ajax({		  url: "/bhel/admin/helper/getAllExecutives.php",
					  type: "post", 
					  data:{},
					  success: function(response) {
						self.parseData(response);
					  },
					  error: function(xhr) {
						alert("got Error "+xhr);
					  }
					});
};
Hierarchy.prototype.parseData=function(response){
	console.log(response);
	var data=JSON.parse(response);
	var self=this;
	$(this.tree).empty();
	for(var i=0;i<data.length;i++){
		this.addExecutive(data[i]);
	}
};
Hierarchy.prototype.addExecutive=function(data){
	this.executives[data.id]=new Executive(data);
	console.log(this.executives[data.id]);
	Hierarchy.executiveAssoc[data.id]=this.executives[data.id];
	this.registerExecutive(this.executives[data.id]);
};
Hierarchy.prototype.registerExecutive=function(executiveObj){
	console.log(executiveObj);
	var expand=$("<span class='glyphicon glyphicon-plus expandExecutive'></span>");
		var span=$("<span class='Texecutive'>"+executiveObj.id+"</span>");
		var li=$("<li></li>");
		li.append(expand);
		li.append(span);
		$(this.tree).append(li);
		$(li).append(executiveObj.tree);
		$(expand).data("obj",executiveObj);
		$(span).data("obj",executiveObj);
		$(expand).on('click',function(){
				var jobj=$(this);
				if(jobj.hasClass("glyphicon-plus"))
				{
					jobj.removeClass("glyphicon-plus");
					jobj.addClass("glyphicon-minus");
				}
				else
				{
					jobj.removeClass("glyphicon-minus");
					jobj.addClass("glyphicon-plus");
				}
				$(this).data("obj").toggleTree(false);
			});
		$(span).on('click',function(){
				var obj=$(this).data("obj");
				obj.panelExecutive();
			});
}
Hierarchy.prototype.refresh=function(){
	$(this.tree).empty();
	console.log("called here..");
	for(var id in this.executives)
	{
		if(this.executives[id]==undefined)
			continue;
		this.registerExecutive(this.executives[id]);
		if(this.executives[id]!=null)
			this.executives[id].refresh();
	}
}
Hierarchy.prototype.panelExecutive=function(executive)
{
	
};







function Executive(data){
	console.log(data);
	this.id=data.id;
	console.log("id is : "+data["id"])
	this.data=data;
	this.supervisors={};
	this.tree=$("<ul></ul>");
	$(this.tree).hide();
	this.isDataLoaded=false;
};
Executive.prototype.panelExecutive=function(){
	$(".hid").hide();
	var panel=$("#ExecutivePanel");
	panel.show();
	var self=this;
	var data=this.data;
	$("#EPanelID").text(data.id);
	$("#EPanelFirstName").text(data.firstname);
	$("#EPanelLastName").text(data.lastname);
	$("#EPanelChangePassword").on('click',function(){
			$(".innerHid").hide();
			$("#EPanelPasswordPanel").show();
			$("#EPanelPasswordInp").val("");
		});
	$("#EPanelPasswordSubmit").unbind('click');
	$("#EPanelPasswordSubmit").on('click',function(){
		var pass=$("#EPanelPasswordInp").val();
		var initialLength=pass.length;
		pass=pass.trim();
		if(pass.length!=initialLength)
		{
			alert("No Trailing or Leading Spaces Allowed");
			return;
		}
		if(pass.length<=3)
		{
			alert("minimum 6 characters required");
			return;
		}
		$.ajax({	  url: "/bhel/admin/helper/changePassword.php",
					  type: "post", 
					  data:{password:pass,id:data.id,to:"executive"},
					  success: function(response) {
						  alert(response);
						//alert("password succesfully changed");
					  },
					  error: function(xhr) {
						alert("Failed to change password");
					  }
					});
	});
	$("#EPanelAddNewSuperVisor").unbind('click');
	$("#EPanelAddNewSuperVisor").on('click',function(){
		$(".innerHid").hide();
		$("#EPanelAddNewSupervisorDiv").show();
		
	});
	$("#EPanelAddButton").unbind('click');
	$("#EPanelAddButton").on('click',function(){
		$("#EPanelExeID").val(""+data.id);
		var form=document.getElementById("EPanelAddNewSupervisorForm");
		if(form['id'].value.length==0)
			return;
		console.log($(form).serialize());
		$.ajax({	  url: "/bhel/admin/helper/addRecord.php",
					  type: "post", 
					  data: $(form).serialize(),
					  success: function(response) {
						  if(response=="success")
						  {
							  self.addSupervisor($(form).serializeObject());
							  showConfirmBox("Supervisor Added Successfully");
						  }
						  else
						  {
							showConfirmBox("failed to add supervisor");
							$("input").val("");
						  }
					  },
					  error: function(xhr) {
						showConfirmBox("failed to add supervisor");
						$("input").val("");
					  }
					});
	});
	$("#EPanelDeleteExecutive").unbind('click');
	$("#EPanelDeleteExecutive").on('click',function(){
		$(".innerHid").hide();
		var myId=data.id;
		$("#ExecutiveDeletePanel").show();
		var selfSelf=self;
		
		$("#ExecutiveDeleteButton").on('click',function(){
			var exe_reason=$("#executiveDeleteReason").val();
		console.log("reason is :"+exe_reason); 
			$.ajax({	  url: "/bhel/admin/helper/removeNode.php",
					  type: "post", 
					  data: {executiveID:myId,option:'executive',reason:exe_reason},
					  success: function(response) {
						  if(response=="success")
						  {
							  selfSelf.removeExecutive();
							  console.log("removed hehe");
						  }
						  else
							  console.log(response);
					  },
					  error: function(xhr) {
						alert("failed to add supervisor");
					  }
					});
		});
		
	});
};

Executive.prototype.loadData=function(slideToggle){
	var self=this;
	$.ajax({		  url: "/bhel/admin/helper/getAllSupervisors.php",
					  type: "post", 
					  data:{id:self.id},
					  success: function(response) {
						self.parseData(response,slideToggle);
					  },
					  error: function(xhr) {
						alert("got Error "+xhr);
					  }
					  
					});
		
};
Executive.prototype.registerSupervisor=function(supervisorObj){
		var expand=$("<span class='glyphicon glyphicon-plus expandExecutive'></span>");
		var span=$("<span class='Tsupervisor'>"+supervisorObj.id+"</span>")
		var li=$("<li></li>");
		li.append(expand);
		li.append(span);
		$(li).append(supervisorObj.tree);
		$(expand).data("obj",supervisorObj);
		$(span).data("obj",supervisorObj);
		$(expand).on('click',function(){
				var jobj=$(this);
				if(jobj.hasClass("glyphicon-plus"))
				{
					jobj.removeClass("glyphicon-plus");
					jobj.addClass("glyphicon-minus");
				}
				else
				{
					jobj.removeClass("glyphicon-minus");
					jobj.addClass("glyphicon-plus");
				}
				$(this).data("obj").toggleTree(false);
			});
		$(span).on('click',function(){
			var supervisor=$(this).data("obj");
			supervisor.panelSupervisor();
			});
		$(this.tree).append(li);
}
Executive.prototype.addSupervisor=function(data){
		var self=this;
		this.supervisors[data.id]=new Supervisor(data);
		Hierarchy.supervisorAssoc[data.id]=this.supervisors[data.id];
		this.registerSupervisor(this.supervisors[data.id]);
};
Executive.prototype.parseData=function(response,slideToggle){
	console.log(response);
	var data=JSON.parse(response);
	var self=this;
	$(this.tree).empty();
	for(var i=0;i<data.length;i++)
	{
		this.addSupervisor(data[i]);
	}
	this.isDataLoaded=true;
	if(slideToggle)
		$(this.tree).slideToggle();
};
Executive.prototype.toggleTree=function(refresh){
	if(refresh || (!this.isDataLoaded) )
	{
		this.loadData(true);
	}
	else
		$(this.tree).slideToggle();
};
Executive.prototype.refresh=function()
{
	$(this.tree).empty();
	for(k in this.supervisors)
	{
		if(this.supervisors[k]!=null){
			this.registerSupervisor(this.supervisors[k]);
			this.supervisors[k].refresh();
		}
		
	}
};
Executive.prototype.removeExecutive=function(){
	console.log("refreshing executive");
	Hierarchy.executiveAssoc[this.id]=window.hierarchy.executives[this.id]=undefined;
	window.hierarchy.refresh();
	$(".hid").hide();
}
function Supervisor(data){
	this.id=data.id;
	this.data=data;
	this.employees={};
	this.tree=$("<ul></ul>");
	$(this.tree).hide();
	this.isDataLoaded=false;
};

Supervisor.prototype.loadData=function(slideToggle){
	var self=this;
	$.ajax({		  url: "/bhel/admin/helper/getAllEmployees.php",
					  type: "post", 
					  data:{id:self.id},
					  success: function(response) {
						self.parseData(response,slideToggle);
					  },
					  error: function(xhr) {
						alert("got Error "+xhr);
					  }
					});
		
};
Supervisor.prototype.parseData=function(response,slideToggle){
	//alert(response);
	var data=JSON.parse(response);
	$(this.tree).empty();
	for(var i=0;i<data.length;i++)
	{
		this.addEmployee(data[i]);
		
	}
	this.isDataLoaded=true;
	if(slideToggle)
		$(this.tree).slideToggle();
};
Supervisor.prototype.toggleTree=function(refresh){
	if(refresh || (!this.isDataLoaded) )
	{
		this.loadData(true);
	}
	else
		$(this.tree).slideToggle();
};

Supervisor.prototype.addEmployee=function(data){
	var emp=new Employee(data);
	this.employees[emp.id]=emp;
	Hierarchy.employeeAssoc[emp.id]=emp;
	this.registerEmployee(emp);
};
Supervisor.prototype.registerEmployee=function(employeeObj){
	var span=$("<span class='Temployee'>"+employeeObj.id+"</span>");
	$(span).data("obj",employeeObj);
	var li=$("<li></li>");
	li.append(span);
	$(this.tree).append(li);
	$(span).on('click',function(){
			var employee=$(this).data("obj");
			employee.panelEmployee();
			});
};
Supervisor.prototype.panelSupervisor=function(){
	var self=this;
	$(".hid").hide();
	$("#SupervisorPanel").show();
	var data=this.data;
	$("#SPanelID").text(data.id);
	$("#SPanelExeID").text(data.exeid);
	$("#SPanelFirstName").text(data.firstname);
	$("#SPanelLastName").text(data.lastname);
	$("#SPanelChangePasswordButton").on('click',function(){
		$(".innerHid").hide();
		$("#SPanelPasswordPanel").show();
	});
	$("#SPanelPasswordSubmit").unbind('click');
	$("#SPanelPasswordSubmit").on('click',function(){
		var pass=$("#SPanelPasswordInp").val();
		var initialLength=pass.length;
		pass=pass.trim();
		if(pass.length!=initialLength)
		{
			alert("No Trailing or Leading Spaces Allowed");
			return;
		}
		if(pass.length<=3)
		{
			alert("minimum 6 characters required");
			return;
		}
		$.ajax({	  url: "/bhel/admin/helper/changePassword.php",
					  type: "post", 
					  data:{password:pass,id:data.id,to:"supervisor"},
					  success: function(response) {
						  alert(response);
						//alert("password succesfully changed");
					  },
					  error: function(xhr) {
						alert("Failed to change password");
					  }
					});
	});
	
	$("#SPanelChangeExecutiveButton").on('click',function(){
		$(".innerHid").hide();
		$("#SPanelChangeExecutivePanel").show();
	});
	$("#SPanelAddNewEmployeeButton").on('click',function(){
		$(".innerHid").hide();
		$("#SPanelAddNewEmployeePanel").show();
	});
	$("#SPanelExecutiveSubmit").unbind('click');
	$("#SPanelExecutiveSubmit").on('click',function(){
		alert("clicked");
		var changeExecutiveId=$("#SPanelExecutiveInp").val();
		if(changeExecutiveId.length==0)
			return;
		var prevExeId=data.exeid;
		var myId=data.id;
		$.ajax({	  url: "/bhel/admin/helper/changeHierarchy.php",
					  type: "post", 
					  data: {supervisorID:myId,executiveID:changeExecutiveId,option:'supervisor'},
					  success: function(response) {
						  if(response=="success")
						  {
							  self.changeExecutive(prevExeId,changeExecutiveId);
						  }
						  else
							  console.log(response);
					  },
					  error: function(xhr) {
						alert("failed to add supervisor");
					  }
					});
	});
	$("#SPanelAddButton").unbind('click');
	$("#SPanelAddButton").on('click',function(){
		$("#SPanelsID").val(""+data.id);
		var form=document.getElementById("SPanelAddNewEmployeeForm");
		if(form['id'].value.length==0)
			return;
		console.log($(form).serialize());
		$.ajax({	  url: "/bhel/admin/helper/addRecord.php",
					  type: "post", 
					  data: $(form).serialize(),
					  success: function(response) {
						  if(response=="success")
						  {
							  self.addEmployee($(form).serializeObject());
							  showConfirmBox("Employee Added Successfully");
						  }
						  else{
							  showConfirmBox("failed to add employee");
							  console.log(response);
						  }  
					  },
					  error: function(xhr) {
						showConfirmBox("failed to add employee");
					  }
					});
	});
	$("#SPanelDeleteSupervisorButton").unbind('click');
	$("#SPanelDeleteSupervisorButton").on('click',function(){
		$(".innerHid").hide();
		var myId=data.id;
		$("#SupervisorDeletePanel").show();
		var selfSelf=self;
		
		$("#SupervisorDeleteButton").on('click',function(){
			var sup_reason=$("#supervisorDeleteReason").val();
			$.ajax({	  url: "/bhel/admin/helper/removeNode.php",
					  type: "post", 
					  data: {supervisorID:myId,option:'supervisor',reason:sup_reason},
					  success: function(response) {
						  if(response=="success")
						  {
							  selfSelf.removeSupervisor();
							  console.log("removed");
						  }
						  else
							  console.log(response);
					  },
					  error: function(xhr) {
						alert("failed to add supervisor");
					  }
					});
		});
		
	});
};
Supervisor.prototype.changeExecutive=function(prevExeId,changeExecutiveId){
	var prevExecutive=Hierarchy.executiveAssoc[prevExeId];
	prevExecutive.supervisors[this.id]=undefined;
	this.data.exeid=changeExecutiveId;
	var executive=Hierarchy.executiveAssoc[changeExecutiveId];
	if(executive!=null){
		executive.supervisors[this.id]=this;
		executive.refresh();
	}
	prevExecutive.refresh();
	this.panelSupervisor();
};
Supervisor.prototype.refresh=function(){
	$(this.tree).empty();
	for(k in this.employees){
		if(this.employees[k]!=null)
			this.registerEmployee(this.employees[k]);
	}
}
Supervisor.prototype.removeSupervisor=function(){
	var executive=Hierarchy.executiveAssoc[this.data.exeid];
	executive.supervisors[this.id]=undefined;
	executive.refresh();
	$(".hid").hide();
}
function Employee(data){
	this.id=data.id;
	this.data=data;
};
Employee.prototype.panelEmployee=function(){
	var self=this;
	$(".hid").hide();
	$("#EmployeePanel").show();
	var data=this.data;
	$("#EmpPanelID").text(data.id);
	$("#EmpPanelSupID").text(data.sid);
	$("#EmpPanelFirstName").text(data.firstname);
	$("#EmpPanelLastName").text(data.lastname);
	$("#EmpPanelChangeSupervisorButton").on('click',function(){
		$(".innerHid").hide();
		$("#EmpPanelChangeSupervisorPanel").show();
	});
	$("#EmpPanelSupervisorSubmit").unbind('click');
	$("#EmpPanelSupervisorSubmit").on('click',function(){
		//alert("clicked");
		var changeSupervisorId=$("#EmpPanelSupervisorInp").val();
		if(changeSupervisorId.length==0)
			return;
		var prevSupId=data.sid;
		var myId=data.id;
		$.ajax({	  url: "/bhel/admin/helper/changeHierarchy.php",
					  type: "post", 
					  data: {employeeID:myId,supervisorID:changeSupervisorId,option:'employee'},
					  success: function(response) {
						  if(response=="success")
						  {
							  self.changeSupervisor(prevSupId,changeSupervisorId);
						  }
						  else
							  console.log(response);
					  },
					  error: function(xhr) {
						alert("failed to add supervisor");
					  }
					});
	});
	$("#EmpPanelDeleteEmployeeButton").unbind('click');
	$("#EmpPanelDeleteEmployeeButton").on('click',function(){
		$(".innerHid").hide();
		var myId=data.id;
		$("#EmpDeletePanel").show();
		var selfSelf=self;
		
		$("#EmpDeleteButton").on('click',function(){
			var emp_reason=$("#employeeDeleteReason").val();
		console.log("reason is: "+emp_reason);
			$.ajax({	  url: "/bhel/admin/helper/removeNode.php",
					  type: "post", 
					  data: {employeeID:myId,option:'employee',reason:emp_reason},
					  success: function(response) {
						  if(response=="success")
						  {
							  selfSelf.removeEmployee();
							  console.log("removed");
						  }
						  else
							  console.log(response);
					  },
					  error: function(xhr) {
						alert("failed to add supervisor");
					  }
					});
		});
		
	});
};

Employee.prototype.changeSupervisor=function(prevSupId,changeSupervisorId){
	var prevSupervisor=Hierarchy.supervisorAssoc[prevSupId];
	prevSupervisor.employees[this.id]=undefined;
	this.data.sid=changeSupervisorId;
	var supervisor=Hierarchy.supervisorAssoc[changeSupervisorId];
	if(supervisor!=null){
		supervisor.employees[this.id]=this;
		supervisor.refresh();
	}
	prevSupervisor.refresh();
	this.panelEmployee();
};
Employee.prototype.removeEmployee=function()
{
	var supervisor=Hierarchy.supervisorAssoc[this.data.sid];
	supervisor.employees[this.data.id]=undefined;
	supervisor.refresh();
	$(".hid").hide();
}

//serialize object
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};