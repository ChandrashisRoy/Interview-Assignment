<html>
 	<head>
  		<title>Url Shortner</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<!--Style Sheet-->
		<link rel="stylesheet" href="css/style.css">
		<meta charset='UTF-8'>
		<meta name="urlshortner" content="Free URL Shortner">
		<link rel="shortcut icon" type="image/x-icon" href="" />
		<style>
			input[type=text]{
			width:auto;
			height: 60px;
			font-size: 18px;
			font-weight: 300;
			padding: 14px 23px;
			color: #7f898e;
			border-radius: 3px;
			border:none;
			box-shadow: 0px 1px 2px #fff;
			text-align: center;
			}
			input[type=submit]{
			width:180px;
			height: 60px;
			border-radius: 0 3px 3px 0;
			padding: 20px 28px 16px 28px;
			font-size: 16px;
			font-weight:bold;
			color: #fff;
			background: #ee6123;
			cursor: pointer;
			text-align: center;
			font-family: brandon-grotesque,"Helvetica Neue",Helvetica,Arial,sans-serif;
			box-shadow: 0px 2px 4px #ee6123;
			text-rendering: optimizelegibility;
			border:none;
			}
			input[type=submit]:hover {
			    opacity: 0.8;
			}
		</style>
 	</head>
 	<body style="background-color: #006987; color: #fff">
 		<div class="container-fluid">
 			<div class="row" style="margin-top: 5%; text-align: center;">
 				<div class="col-lg-3 col-md-3 hidden-sm hidden-xs"></div>
 				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
 					<!--Entry Form-->
 					<form action="submit.php" method="post">
						<label for="url" style="font-size:22px; font-weight: bold">Enter URL (http://example.com):</label>
						<br>
					   	<input type="text" name="url" id="url">
					   	<input type="submit" value="Shorten">
		  			</form>
 				</div>
 				<div class="col-lg-3 col-md-3 hidden-sm hidden-xs"></div>
			</div>

			<div class="row">
				<div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
				<!--Searched URL-->
 				<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
 					<?php
						echo "<div style='text-align: center'>";
						echo "<div style='font-size:22px; font-weight: bold'>Most Searched URL</div>";

						class TableRows extends RecursiveIteratorIterator { 
						    function __construct($it) { 
						        parent::__construct($it, self::LEAVES_ONLY); 
						    }

						    function current() {
						        return "<div style='border-top: 1px solid white; margin-top: 3%'>" . parent::current(). "</div>";
						    }

						    function beginChildren() { 
						        echo "<tr>"; 
						    } 

						    function endChildren() { 
						        echo "</tr>" . "\n";
						    } 
						} 

						/*Database Connection*/
						$servername = "localhost";
						$username = "root";
						$password = "";
						$dbname = "test";

						try {
						    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
						    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						    $stmt = $conn->prepare("SELECT long_url FROM short_url ORDER BY counter DESC"); 
						    $stmt->execute();

						    // set the resulting array to associative
						    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 

						    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
						        echo $v;
						    }
						}
						catch(PDOException $e) {
						    echo "Error: " . $e->getMessage();
						}
						$conn = null;
						echo "</div>";
					?>
 				</div>
 				<div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
			</div>
		</div>
 	</body>
</html>
