<!DOCTYPE html>
<html>
<head>
	<title><?php echo $titulo=" "; ?></title>
	<?php include("_head.php"); ?>
</head>
<body>
<?php
	include("_menu.php");
	include("conectar_Usuario.php");
	$redirige="index.php";
	$playerRegistrant = "";
	$idPokemon = $_POST["idPokemon"];
	$idgym = $_POST["idgym"];
	$playerRegistrant = $_POST["playerRegistrant"];
	$enHuevo = $_POST["status"];
	
	$fechaHoraActual = date("Y-m-d H:i:s",time());
	if($enHuevo==2){
		$idPokemon = 150;	//TEMPORALMENTE ES CONSTANTE
		$levelEgg = 6;
		$fechaHora = date("Y-m-d", strtotime($_POST["fecha"]))." ".date("H:i:s",strtotime($_POST["hora"]) );
		$fechaHora = strtotime("+45 minutes",strtotime($fechaHora));
	}else{
		$minutos = $_POST["minutos"];//(int)date("i", strtotime($_POST["hora"]));
		if($enHuevo==1){
			$levelEgg = $_POST["levelEgg"];
			$minutos = $minutos + 45;
			$idPokemon = 0;
		}else if($enHuevo==0){
			$levelEgg = 0;
		}
		$fechaHora = strtotime("+$minutos minutes",strtotime($fechaHoraActual));
	}
	$fechaHora = date("Y-m-d H:i:s", $fechaHora);
	
	$sql = "SELECT * FROM raids WHERE raidTime>'$fechaHoraActual' AND idgym=$idgym AND idPokemon!=150 ORDER BY raidTime";
	$incursionAbierta = $conectar->query($sql);
	if ($incursionAbierta->num_rows == 0) {
    	$sql = "INSERT INTO raids(raidTime,idPokemon,idGym,playerRegistrant,levelEgg) VALUES ('$fechaHora','$idPokemon','$idgym','$playerRegistrant','$levelEgg')";
		if ($conectar->query($sql) === TRUE){
    		$sql = "SELECT idRaid FROM raids WHERE raidTime='$fechaHora' AND idPokemon=$idPokemon AND idgym=$idgym";
    		$incursionCreada = $conectar->query($sql);
    		if ($incursionCreada->num_rows > 0) {
				while($row = $incursionCreada->fetch_assoc()){
    				$idRaid = $row[idRaid];
    			}
    			if (isset($_POST["incursionar"]))
    	            $redirige="registrar/Agenda.php?idRaid=$idRaid";	//Redirigir a la agenda de incursion
    		}
			echo "<font color='green'>Se ha registrado exitosamente el Raid para atacarlo del ".date("Y-m-d",strtotime($_POST["fecha"]))." ".date("H:i:s",strtotime($_POST["hora"]))." al $fechaHora</font>";
    	}else
    		echo "<font color='red'>Es posible que NO se haya registrado el Raid, revisa lo que hiciste y/o reporta irregularidades del sistema</font>";
	}else
		echo "<font color='red'>Es posible que el gimnasio en el que intentaste registrar un Raid ya se encuentre ocupado, revisa lo que hiciste y/o reporta irregularidades del sistema</font>";
	$conectar->close();
?>
<p>Te estamos redireccionando a <?php echo $redirige; ?>, si no funciona puedes hacer <a href="<?php echo $redirige; ?>">click AQUI</a></p>
</body>
	<meta http-equiv="Refresh" content="3;url=<?php echo $redirige; ?>">
</html>
