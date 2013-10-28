<?php require_once("server-settings.php"); ?>

<script type="text/javascript">
  //<![CDATA[
  window.fbAsyncInit = function() {
    FB.init({ appId: jumzu_fbAppId, status: true, cookie: true, xfbml: true, oauth: true });
	FB.Event.subscribe('auth.login', function(response) { window.location = "http://<?php echo $server_domain; ?>/signin-facebook.php"; });
//	FB.Event.subscribe('auth.logout', function(response) { window.location.reload(); });
  };
  
  function facebookLogin() {
    FB.login(function(response) {
      if (response.authResponse) { //session && response.scope
        //popupateFields();
        window.location = "http://<?php echo $server_domain; ?>/signup-facebook.php"
      } else {
		  alert("connection error");
		//not connected
      }
    }, {scope: jumzu_fbPerms});
  }  

  function facebookConnect() {
    FB.login(function(response) {
      if (response.authResponse) {
        //popupateFields();
        window.location = "http://<?php echo $server_domain; ?>/facebook-connect.php"
      } else {
		//not connected
      }
    }, {scope: jumzu_fbPerms});
  }  

  function facebookSignin() {
    FB.login(function(response) {
      if (response.authResponse) {
        //popupateFields();
        window.location = "http://<?php echo $server_domain; ?>/signin-facebook.php"
      } else {
		//not connected
      }
    }, {scope: jumzu_fbPerms});
  }  

  function fb_publish() {
     FB.ui(
       {
         method: 'stream.publish',
         message: 'Message here.',
		 auto_publish: 'true',
         attachment: {
           name: 'Name here',
           caption: 'Caption here.',
           description: (
             'description here'
           ),
           href: 'url here'
         },
         action_links: [
           { text: 'Code', href: 'action url here' }
         ],
         user_prompt_message: 'Personal message here'
       },
       function(response) {
         if (response && response.post_id) {
           alert('Post was published.');
         } else {
           alert('Post was not published.');
         }
       }
     );  
  }



function publishPost(message1,listento1,listentolink1,description1,picture1) {

   var publish = {
     method: 'stream.publish',
     message: message1,
     picture : picture1,
     link : listentolink1,
     name: listento1,
     caption: '',
     description: description1,
     actions : { name : 'Listen & Speak Now', link : 'http://<?php echo $server_domain; ?>'}
   };

   FB.api('/me/feed', 'POST', publish, function(response) {  
       alert('<?php echo $PostedOnFacebook; ?>');
   });
}

  //]]>
</script>

<div id="fb-root"></div>

<script type="text/javascript">
	var jumzu_fbAppId = '261798160586392';
	var jumzu_fbPerms =	'email,offline_access,user_location,user_about_me,user_likes,user_status,user_website,user_interests,user_online_presence';

	(function() {
		var e = document.createElement('script');
		e.async = true;
		e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		document.getElementById('fb-root').appendChild(e);
	}());
</script>
