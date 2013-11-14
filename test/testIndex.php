<?php
getRoute()->get('/tests', array('Tests', 'home'));
getRoute()->get('/tests/addEntity', array('Tests', 'addEntity'));
getRoute()->get('/tests/viewEntity/(.+)/(.+)', array('Tests', 'viewEntity'));
getRoute()->get('/tests/listEntities', array('Tests', 'listEntities'));

class Tests{
	public static function show_login(){
		echo "<script src='/scripts/jquery-1.10.2.min.js'></script>";
		echo "<script src='/scripts/cwrc-api.js'></script>";
		echo "<script type='text/javascript'>";
		echo "var cwrcApi = new CwrcApi('http://localhost', $)";
		echo "</script>";
		
		if(count(get_login_cookie()) > 0){
			echo "<h4>Logged in as " . $_SESSION['username'] . "</h4>";
			echo "<button onclick='cwrcApi.logout();location.reload();' value='Logout'>Logout</button>";
			echo "</form></br>";
		}else{
			echo "<p>Please login:</p>";
			echo "Username: <input type='text' id='loginUsername' name='username'/>";
			echo "Password: <input type='password' id='loginPassword' name='password'/>";
			echo "<button onclick='cwrcApi.initializeWithLogin($(\"#loginUsername\").val(), $(\"#loginPassword\").val());location.reload();'>Login</button>";
			echo "</br>";
		}
	}
	
	public static function logout(){
		cwrc_logout();
		
		$_SESSION['username'] = null;
		header('location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	public static function login(){
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		// Run login function
		cwrc_login($username, $password);
		
		$_SESSION['username'] = $username;
		header('location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	public static function home(){
		self::show_login();
		echo "<h1>Cwrc API Tests</h1>";
		echo "<ul>";
		echo "<li><a href='/tests/listEntities'>List Entities</a></li>";
		echo "<li><a href='/tests/addEntity'>Add Entity</a></li>";
		echo "</ul>";
	}
	
	public static function viewEntity($type, $pid){
		self::show_login();
		
		echo "<h1>View Entity</h1>";
		
		echo "<h2>Type: " . htmlspecialchars($type) . "</h2>";
		echo "<h2>PID: " . htmlspecialchars($pid) . "</h2>";
		
		echo "<h2>Content</h2>";
		echo "<textarea id='entityContent' name='data'></textarea>";
		echo "<div>";
		echo "<button onclick='return updateEntity();'>Update</button>";
		echo "<button onclick='deleteEntity();'>Delete</button>";
		echo "</div>";
		
		echo "<script type='text/javascript'>
			function deleteEntity(){
				if(confirm('Are you sure you wish to delete this entity?')){
					var result = cwrcApi['" . $type . "'].deleteEntity('" . $pid . "');
					
					if(result.isDeleted){
						alert('Entity Successfully deleted.');
					}else{
						alert(result.error);
					}
				}	
			}
			
			function updateEntity(){
				var result = cwrcApi['" . $type . "'].modifyEntity('" . $pid . "', $('#entityContent').val());
				
				if(result.error){
					alert(result.error);
				}else{
					alert('Entity modified successfully.')
				}
				
				return false;
			}
			
			$(document).ready(function(){
				var val = cwrcApi['" . $type . "'].getEntity('" . $pid . "');
				$('#entityContent').val(val);
			});
		</script>";
	}
	
	public static function listEntities(){
		self::show_login();
		echo "<h1>List Entities</h1>";
		
		echo "<h2>Type:</h2>";
		echo "<select id='entityType' onchange='selectChanged()'>";
		echo "<option></option>";
		echo "<option value='person'>Person</option>";
		echo "</select>";
		
		echo "<br/>";
		
		echo "<h2>Entities</h2>";
		echo "<table>";
		echo "<tr>
			<th>PID</th>
			<th>Action</th>
		<tr>";
		echo "</table>";
		
		echo "<script type='text/javascript'>
			function selectChanged(){
				alert('changed');
			}
		</script>";
	}
	
	public static function addEntity(){
		self::show_login();
		echo "<h1>Add Entity</h1>";
		
		echo "<div>";
		echo "<span class='label'>Entity Type</span>";
		echo "<select id='entityType'>";
		echo "<option value='person'>Person</option>";
		echo "</select>";
		echo "</div>";
		
		echo "<div>";
		echo "<textarea id='entityData' name='data'></textarea>";
		echo "</div>";
		
		echo "<button onclick='submitEntity();'>Submit</button>";
		
		echo "<script type='text/javascript'>
			function submitEntity(){
				var type = $('#entityType').val();
				var val = $('#entityData').val();
				var result = cwrcApi[type].newEntity(val);
			
				if(result.error){
					alert(result.error);
				}else{
					window.location.href = '/tests/viewEntity/' + encodeURIComponent(type) + '/' + encodeURIComponent(result.pid);
				}
			}
		</script>";
	}
}
