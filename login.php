<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	if(!$mysqli){
		die("Connection failed: " . mysqli_connect_error());
	}

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
	$target_dir = "gallery/";
	//$_FILES["picToUpload"] = isset($_POST["picToUpload"]) ? $_POST["picToUpload"] : false;
    $uploadFile = isset($_FILES["picToUpload"]) ? $_FILES["picToUpload"] : false  ;
	// $_FILES["picToUpload"];
	
	if ($uploadFile) {
		$numFiles = count($uploadFile["name"]);//echo
		for ($i=0; $i < $numFiles ; $i++) { 
			# code...
			if ($uploadFile["type"][$i] == "image/jpeg" && $uploadFile["size"][$i] < 1048576) {
				
				if ($uploadFile["error"][$i] > 0) {
					
					echo 	'<div class="alert alert-danger mt-3" role="alert">
								Error: '. $uploadFile["error"][$i] .'
							</div>';
				}
				else {
					
						move_uploaded_file($uploadFile["tmp_name"][$i],"gallery/" .  $uploadFile["name"][$i]);
						$sqlSelect = "SELECT user_id FROM tbusers WHERE email = '" . $email."'";
						$res = $mysqli->query($sqlSelect);
						if ($row = mysqli_fetch_array($res)) {
							# code...
							// $row = $res->fetch_asoc();
							$userID = $row["user_id"];
						}
						
						$sqlSelect = "SELECT COUNT(*) FROM tbgallery";
						$res = $mysqli->query($sqlSelect);
						$row = mysqli_fetch_array($res);
						$row = $row[0]+1;
						$sqlInsert ="INSERT INTO tbgallery VALUES (". $row .",". $userID .",'gallery/". $uploadFile["name"][$i] ."')";
						if($mysqli->query($sqlInsert)){
							//echo 'success';
						}
						else {
							echo "Error: " . $sqlInsert . "<br>" . $mysqli->error;
						}

						//header("Refresh:0");

				}
			}
			else {
				
				echo 	'<div class="alert alert-danger mt-3" role="alert">
							Invalid File
						</div>';
			}
		}

	}



?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Phumudzo Vhulenda Ndou">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form method='POST' action='' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload[]' id='picToUpload' multiple='multiple' /><br/>
									<input type='hidden' class='form-control' name='loginEmail' value='". $row['email']  ."' /><br/>
									<input type='hidden' class='form-control' name='loginPass' value='". $row['password'] ."' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
							  </form>";
							  
					echo	"<h1>Image Gallery</h1>
							 <div class='row imageGallery'>";
							$sqlSelect = "SELECT filename FROM tbgallery WHERE user_id =" .  $row["user_id"] ;
							$res = $mysqli->query($sqlSelect);
							if ($res->num_rows > 0) {
								
								while ($row = mysqli_fetch_array($res)) {
									$filename = '"'.$row["filename"].'"';
									echo "<div class='col-3 ' style='background-image: url(". $filename ."')'>
									
									</div>";

								}
							}
					echo	"</div>";
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
	
</body>
</html>
<?php
		// if (isset($_POST["submit"])) {
		# code...
		
		
	// }
?>