
<?php 
\OCP\Util::addScript("ocmessage", "angular");
\OCP\Util::addScript('appframework', 'public/app');
\OCP\Util::addScript("ocmessage", "messages");
//\OCP\Util::addScript("ocmessage", "ocmessage");
\OCP\Util::addStyle("ocmessage", "style");
?>

<div>
</div>


<div ng-app="message" ng-controller="tabCtrl" id="tabs">
	<div class="tab_button_wrapper">
		<div ng-click="setTab('read')" ng-class="activeTab('read')" class="tab_button left">Nachrichten lesen</div>
		<div ng-click="setTab('write')" ng-class="activeTab('write')" class="tab_button middle">Nachricht verfassen</div>
		<div ng-click="setTab('archiv')" ng-class="activeTab('archiv')" class="tab_button right">Archiv</div>
	</div>
	
	<div ng-show="isTab('read')">
  		<div class="message_wrapper"  ng-controller="msgReadCtrl" ng-class="{loading: loading}">	
  			<div ng-repeat="message in messages">
  				<span class="message">
  					<h2 class="msg_subject" ng-click="markRead(message.message_id); msg_message.message_id = 'true'" ng-init="msg_message.message_id = 'false'">{{ message.message_subject }}</h2>
  					<span class="msg_time">{{ humanTime(message.message_timestamp) }}</span>
  					<p class="msg_content" ng-show="msg_message.message_id == 'true'" ng-bind-html="message.message_content"></p> 
  					<button ng-click="deleteMessage(message.message_id)">Löschen</button>
  				</span>
  			</div>
		</div>
  	</div>
  	
  	<div ng-show="isTab('write')"> 
  		<div class="message_wrapper">
  			<div ng-controller="msgSendCtrl">
  				<span class="message">
  					To: <input type="text" ng-model="msg_to" ng-value="getUsers()" /><br>
  					Subject: <input type="text" ng-model="msg_subject" /><br>
  					Content: <textarea rows="" cols="" ng-model="msg_content"></textarea><br>
  					<button ng-click="send()">Nachricht senden</button>
  				</span>
  				<span ng-init="users=[ <?php 
  				for($i = 0; $i < sizeof($_["users"]); $i++){
  				 	if (sizeof($_["users"])-1 == $i){
  				 		p("'".$_["users"][$i]."'");
  				 	} else {
  						p("'".$_["users"][$i]."',");
  				 	}
  				} ?>]" ><ul>{{ users }}
  					<li ng-repeat="user in users">{{ user }}</li>
  				</ul>
  				
  				</span>
  			</div>
  		</div>
  	</div>

  	<div ng-show="isTab('archiv')" class="message_wrapper">Der Sinn bleibt mir noch verborgen ;)</div> 
 </div>