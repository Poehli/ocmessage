var msg = angular.module("message",["OC"]);
var route = OC.Router;

msg.controller("tabCtrl", ["$scope", function ($scope){

	$scope.tabName = "read";
	
	$scope.isTab = function( name ){
		return $scope.tabName == name;
	};
	
	$scope.setTab = function( name ){
		$scope.tabName = name;
	}
	
	$scope.activeTab = function( name ){
		if ($scope.tabName == name){
			return "active";
		} else {
			return "inactive";
		}
	}
}]);

msg.controller("msgCtrl", ["$scope", "$http", function($scope, $http){
	$http(	{method:"post", 
				url: route.generate('ocmessage_getMessages', {user: ""})
			})
	.success(
			function (data){
				if (data.error !== ""){
					alert("Error: "+ data.error);
				} else {
					$scope.messages = data.return;
				}
			}
	)
	.error(function(data){
		alert("error:"+data.error);
	});
	
	$scope.markRead = function( id ){
		$http({method:"post", url: route.generate('ocmessage_setMessageRead', {msg_id: id})}).success(function (data){
			
		}).error(function(data){alert("error:"+data.error);});
	}
	
	$scope.deleteMessage = function ( id ){
		$http({method:"post", url: route.generate('ocmessage_deleteTo', {msg_id: id})}).success(function (data){
			
		}).error(function(data){alert("error:"+data.error);});
		
		for (var i = 0; i < $scope.messages.length; i++){
			if ($scope.messages[i].message_id == id){
				$scope.messages.splice(i, 1);
				break;
			}
		}
	}
	
	$scope.humanTime = function( phpTimestamp ){
		var timestamp = phpTimestamp*1000;
		
		var date = new Date(timestamp);
		var dayOfMonth = date.getDate();
		var month = date.getMonth();
		var fullYear = date.getFullYear();
		var hours = date.getHours();
		var minutes = date.getMinutes();
		var seconds = date.getSeconds();
		
		var now = new Date();
		var nowDayOfMonth = now.getDate();
		var nowMonth = now.getMonth();
		var nowFullYear = now.getFullYear();
		var nowHours = now.getHours();
		var nowMinutes = now.getMinutes();
		var nowSeconds = now.getSeconds();
		
		var returnTime = "";
		if (""+nowDayOfMonth+nowMonth+nowFullYear == ""+dayOfMonth+month+fullYear ){
			if (hours+2 >= nowHours ){
				returnTime = "heute um "+hours+":"+minutes;
			} else {
				if (hours + 1 >= nowHours){
					returnTime = "vor einer Stunde";
				} else {
					returnTime = "vor wenigen Minuten";
				}
			}
		} else {

			if (""+fullYear+month != ""+nowFullYear+nowMonth || dayOfMonth+2 > nowDayOfMonth){
				returnTime = "am "+dayOfMonth+"."+month+"."+fullYear+", um "+hours+":"+minutes;;
			} else if(dayOfMonth+1 >= nowDayOfMonth) {
				returnTime = "vorgestern um "+hours+":"+minutes;;
			} else {
				returnTime = "gestern um "+hours+":"+minutes;;
			}
		}
    	return returnTime;
	}
		
	$scope.getUsers = function (){
		if ($scope.msg_to == "alle" ||$scope.msg_to == "all"){
			$scope.msg_to = "";
			for (var i = 0; i < $scope.users.length; i++){
				if (i < 1)
					$scope.msg_to = $scope.users[i];
				else
					$scope.msg_to = $scope.msg_to+";"+$scope.users[i];
			}
		}
	}


	$scope.msg_subject = "Betreff";
	$scope.msg_content = "Nachricht";
	$scope.send = function (){
		$http({
			method:"post", 
			url: route.generate('ocmessage_sendMessage'),
			data: {msg_subject: $scope.msg_subject,
				msg_content: $scope.msg_content, 
				msg_to: $scope.msg_to}})
			.success(function(data){
				if (data !== ""){
					alert(data);
				}
			});
		
		// Testumgebung
		$http({method:"post", url: route.generate('ocmessage_getSendMessages', {user: ""})}).success(function (data){
			alert("success: "+data.return[0].message_to);
		}).error(function(data){alert("error:"+data.error);});
		// Ende Testumgebung
		
	};
}]);