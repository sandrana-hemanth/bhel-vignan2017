function Hierarchy(){
	this.executives={};
	this.tree=$("<ul></ul>");
	this.loadData();
	this.data;
};
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
	//alert(response);
	var data=JSON.parse(response);
	var self=this;
	$(this.tree).empty();
	for(var i=0;i<data.length;i++){
		var expand=$("<span class='glyphicon glyphicon-plus expandExecutive'></span>");
		var span=$("<span class='Texecutive'>"+data[i].id+"</span>");
		var li=$("<li></li>");
		li.append(expand);
		li.append(span);
		$(this.tree).append(li);
		this.executives[data[i].id]=new Executive(data[i]);
		$(li).append(this.executives[data[i].id].tree);
		$(expand).data("obj",this.executives[data[i].id]);
		$(span).data("obj",this.executives[data[i].id]);
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
};
Hierarchy.prototype.panelExecutive=function(executive)
{
	
};







function Executive(data){
	this.id=data.id;
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
		$("#EPanelAddNewSupervisorDiv").show();
		
	});
	$("#EPanelAddButton").unbind('click');
	$("#EPanelAddButton").on('click',function(){
		$("#EPanelExeID").val(""+data.id);
		var form=document.getElementById("EPanelAddNewSupervisorForm");
		if(form['id'].value.length==0)
			return;
		console.log($(form).serialize());
		$.ajax({	  url: "/bhel/admin/helper/AddRecord.php",
					  type: "post", 
					  data: $(form).serialize(),
					  success: function(response) {
						  if(response=="success")
						  {
							  self.addSupervisor($(form).serializeObject());
						  }
					  },
					  error: function(xhr) {
						alert("failed to add supervisor");
					  }
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
Executive.prototype.addSupervisor=function(data){
		var self=this;
		this.supervisors[data.id]=new Supervisor(data);
		var expand=$("<span class='glyphicon glyphicon-plus expandExecutive'></span>");
		var span=$("<span class='Tsupervisor'>"+data.id+"</span>")
		var li=$("<li></li>");
		li.append(expand);
		li.append(span);
		$(li).append(this.supervisors[data.id].tree);
		$(expand).data("obj",this.supervisors[data.id]);
		$(span).data("obj",this.supervisors[data.id]);
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
};
Executive.prototype.parseData=function(response,slideToggle){
	//alert(response);
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
	var span=$("<span class='Temployee'>"+data.id+"</span>");
	$(span).data("obj",emp);
	var li=$("<li></li>");
	li.append(span);
	$(this.tree).append(li);
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
	//$("#SPanelExecutiveSubmit").unbind('click');
	$("#SPanelExecutiveSubmit").on('click',function(){
		alert("clicked");
		var changeExecutiveId=$("#SPanelExecutiveInp").val();
		if(changeExecutiveId.length==0)
			return;
		var myId=data.id;
		$.ajax({	  url: "/bhel/admin/helper/changeHierarchy.php",
					  type: "post", 
					  data: {supervisorID:myId,executiveID:changeExecutiveId,option:'supervisor'},
					  success: function(response) {
						  if(response=="success")
						  {
							  console.log(response);
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
		$.ajax({	  url: "/bhel/admin/helper/AddRecord.php",
					  type: "post", 
					  data: $(form).serialize(),
					  success: function(response) {
						  if(response=="success")
						  {
							  self.addEmployee($(form).serializeObject());
						  }
						  else
							  console.log(response);
					  },
					  error: function(xhr) {
						alert("failed to add supervisor");
					  }
					});
	});
};

function Employee(data){
	this.id=data.id;
	this.data=data;
};



















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