<?php
	include("conectar_Usuario.php");
	
	function masMinutos($minutos,$var){
		return date("Y-m-d H:i:s",strtotime("$minutos minutes",strtotime($var)));
	}
	
	$sql = "SELECT idPokemon,nameAmerica,raidLevel FROM Pokemon WHERE raidLevel BETWEEN 1 AND 5 ORDER BY raidLevel DESC, nameAmerica";
	$pokemon = $conectar->query($sql);
	if ($pokemon->num_rows > 0) {
		for($n=0;$n<=6;$n++)
			$levelPokemonCount[ $n ] = 0;
		while($row = $pokemon->fetch_assoc()) {
			$idPokemon[] = $row[idPokemon];
			$nameAmerica[] = $row[nameAmerica];
			$levelPokemon[] = $row[raidLevel];
			$levelPokemonCount[ $row[raidLevel] ] = $levelPokemonCount[ $row[raidLevel] ] +1;
		}
	}else
		echo "¡ERROR consultando Pokemon!<hr>";
	
	$gimnasios = $conectar->query("SELECT idgym,name,lat,lon FROM gym ORDER BY name");
	if ($gimnasios->num_rows > 0) {
		while($row = $gimnasios->fetch_assoc()) {
			$idgym[] = $row[idgym];
			$name[] = $row[name];
			$lat[] = $row[lat];
			$lon[] = $row[lon];
		}
	}else
		echo "¡ERROR consultando gimnasios!<hr>";
	
	$sql = "SELECT * FROM Pokemon,gym,raids WHERE Pokemon.idPokemon=raids.idPokemon AND gym.idgym=raids.idGym AND raidTime>'".date("Y-m-d H:i:s",time())."' ORDER BY raidTime";
	$incursiones = $conectar->query($sql);
	if ($incursiones->num_rows > 0) {
		while($row = $incursiones->fetch_assoc()) {
			$raidLevel = $row[levelEgg];
			$raidTime = $row[raidTime];
			if($raidLevel>0 && $row[idPokemon]==0){
				$raidTimeTest = masMinutos(-45,$raidTime);
				if( strtotime($raidTimeTest) < date(time()) ){
					$consultar = $conectar->query("SELECT idPokemon,nameAmerica FROM Pokemon WHERE raidLevel=$raidLevel");
					$x = $consultar->num_rows;
					if($x == 1) while($row2 = $consultar->fetch_assoc()) {
						$sqlUpdate = "UPDATE raids SET idPokemon=$row2[idPokemon] WHERE idRaid=$row[idRaid] AND idPokemon=0";
						if ($conectar->query($sqlUpdate) === TRUE){
							$namePokemon = "$row2[nameAmerica]";
						}
					} else
						$namePokemon = "Nivel $raidLevel Abierto!";
				}else{
					$namePokemon = "Huevo Nivel $raidLevel";
					$raidTime = $raidTimeTest;
				}
			}else{
				$namePokemon = $row[nameAmerica];
				$raidLevel = $row[raidLevel];
				if($raidLevel==6){
					$sqlEX="SELECT MAX(raidTime),count(*) FROM raids WHERE levelEgg=6 AND idGym=$row[idGym]";
					$incursionesEX = $conectar->query($sqlEX);
					if ($incursionesEX->num_rows > 0) {
						while($row2 = $incursionesEX->fetch_assoc()) {
							$lastEX = $row2['MAX(raidTime)'];
							$numeroEX = $row2['count(*)'];
						}
						if( strtotime($lastEX) > date(time()) ){
							$nextEX = $lastEX;
							
							$sqlEX="SELECT idRaid,raidTime FROM raids WHERE levelEgg=6 AND idGym=$row[idGym] ORDER BY raidTime DESC LIMIT 1,1";
							$incursionesEXlast = $conectar->query($sqlEX);
							if ($incursionesEXlast->num_rows > 0) {
								while($row3 = $incursionesEXlast->fetch_assoc()) {
									$lastEXid = $row3[idRaid];
									$lastEX = $row3[raidTime];
								}
							}else
								$lastEX = 0;
						}else
							$nextEX = 0;
					}
				}else
					$numeroEX = 0;
			}
			if($numeroEX>0)
				$raid_paseEX[] = 1;
			else
				$raid_paseEX[] = 0;
			$raid_id[] = $row[idRaid];
			$raid_linkGrupo[] = $row[linkGrupo];
			$raid_idPokemon[] = $row[idPokemon];
			$raid_namePokemon[] = $namePokemon;
			$raid_nameGym[] = $row[name];
			$raid_lat[] = $row[lat];
			$raid_lon[] = $row[lon];
			$raid_Time[] = $raidTime;
			$raid_Level[] = $raidLevel;
			$raid_lastEX[] = masMinutos(-45,$lastEX);
			$raid_lastEXid[] = $lastEXid;
			$raid_nextEX[] = masMinutos(-45,$nextEX);
			$raid_histEX[] = $numeroEX;
		}
	}else
		echo "¡ERROR consultando raids de incursiones!<hr>";
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $titulo="Avisar de Nuevas Incursiones"; ?></title>
	<?php include("_head.php"); ?>
	<style>
		p,select,input {
			margin-top: 5px;
			margin-bottom: 5px;
		}
	</style>
</head>
<body>
	<div id="map" style="position: absolute;width: 100%; height: 100%; top: 0;left: 0; right: 0;"></div>
	
	<div align="center" style="position: absolute; right: 12%; left: 12%; background-color: rgba(80,175,235,0.7);">
		<?php
			include("_menu.php");
		?>
		<button onclick="fverRegistraRaid()" style="width:50%"><h2 id="txtRegistraRaid">Registro de Raid</h2></button>
		
		<grid-dual id="verRegistraRaid" style="display:none; border-color:black; background-color: rgba(255,255,255);">
			<h2>Registrar Raid</h2>
			<p><small>Aquí puedes reportar que has visto un Pokemon Jefe defendiendo un Gimnasio contra el cual se puede luchar en incursiones.</small></p>
			<form name="frmRaid" method="post" action="Raid_Registrar.php" accept-charset="UTF-8">
				<select name="idgym" style="width:95%">
				<?php
					for($i=0;$i<count($idgym);$i++)
						echo "<option value='$idgym[$i]'>$name[$i] ( $lat[$i] , $lon[$i] )</option>";
				?>
				</select>
				
				<script>
				</script>
				<br>
				<input type="radio" name="status" value="2" onclick="statusP(2)" required>Pase EX <img src="img/mapa/paseEX.png" height="24">
				<input type="radio" name="status" value="1" onclick="statusP(1)" required>En Huevo
				<input type="radio" name="status" value="0" onclick="statusP(0)" required>Abierto
				
				<div id="verNivel" style="display:none">
					Nivel <select name="levelEgg">
	<?php
						for($i=5;$i>0;$i--)
							echo "<option value='$i' >$i </option>";
	?>
					</select>
				</div>
				
				<div id="verPokemon" style="display:none">
					<select name="idPokemon">
	<?php
						for($i=0,$f=true;$i<count($idPokemon);$i++){
							echo "<option value='$idPokemon[$i]' >$levelPokemon[$i] - $nameAmerica[$i] ($idPokemon[$i]) </option>";
							/*
							if( $levelPokemon[$i]==4 && $f ){
								echo "<option value='150' >EX - Mew Two (150) </option>";
								$f=false;
							}
							*/
						}
	?>
					</select>
				</div>
				
				<div id="verCalendario" style="display:none">
					<?php $enDosMinutos = date( "H:i:00",strtotime("+2 minutes",strtotime(date("H:i:00"))) ); ?>
					<input type="date" name="fecha" id="fechaF" step="1" min="<?php echo date("Y-m-d");?>" max="2019-12-31" value="<?php echo date("Y-m-d");?>" >
					<input type="time" name="hora" step="60" min="05:00:00" max="20:00:00" value="<?php echo $enDosMinutos;?>" >
				</div>
				
				<div id="verMinutos" style="display:none">
					<p>minutos para <u id="txtStatus"></u>: <input type="number" name="minutos" id="minutosF" min="0" max="59"></p>
				</div>
				
				<p><font color="green">Voy para Jugar</font> <input type="checkbox" name="incursionar"></p>
				<button type="submit" style="width:80%"><h2>Registro de Raid</h2></button>
			</form>
			<br>
			<p><a href="registrar/Gimnasio.php">¿No encuentras el gimnasio deseado? Registralo!</a></p>
			<br>
		</grid-dual>
	</div>

</body>
</html>
	<!-- ----------------------------------------------------------- -->
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(20.6745, -103.3555),
          zoom: 14,
          mapTypeId: 'roadmap'
        });
<?php
		for($i=0;$incursiones->num_rows > 0 && $i<sizeof($raid_id);$i++) {
			$nombreImagen[$i] = imagen($raid_idPokemon[$i],$raid_Level[$i]);
			$linkAgenda[] = "<a href='registrar/Agenda.php?idRaid=$raid_id[$i]'>";
 ?>
			var marker<?php echo $i;?> = new google.maps.Marker({
				position: {lat: <?php echo $raid_lat[$i];?>, lng: <?php echo $raid_lon[$i];?>},
				map: map,
				title: '<?php echo "<b>$raid_namePokemon[$i]</b> en $raid_nameGym[$i]";?>',
				icon: '<?php echo $nombreImagen[$i];?>'
			});
			marker<?php echo $i;?>.addListener('click', function() {
				  infowindow<?php echo $i;?>.open(map, marker<?php echo $i;?>);
			});
			var infowindow<?php echo $i;?> = new google.maps.InfoWindow({
				content: "<div align='center'><?php
					echo "<big><b>$linkAgenda[$i]$raid_namePokemon[$i]</a></b></big> en<br><b>$raid_nameGym[$i]</b>";
					if($raid_paseEX[$i]==1){
						echo "<img src='img/paseEX.png' height='25'>";
						if($raid_idPokemon[$i]==150 && $raid_linkGrupo[$i]!="")
							echo "<a href='$raid_linkGrupo[$i]'><img src='http://computaccion.com/img/whatsapp-logo-3.png' height='25'></a>";
					}
					echo "<br>($raid_lat[$i],$raid_lon[$i])<br><tiempo id='count$i'>count$i</tiempo><br>";
					if($raid_Level[$i]>0 && $raid_idPokemon[$i]==0)
						echo "Abre";
					else if($raid_idPokemon[$i]>0)
						echo "Finaliza";
					echo " el $raid_Time[$i]<br>";
					if($raid_paseEX[$i]==1){
						echo "<hr>";
						if($raid_nextEX[$i]!=0)
							echo "Siguiente Pase EX: $linkAgenda[$i] $raid_nextEX[$i]</a><br>";
						if($raid_lastEX[$i]!=0)
							echo "Ultimo Pase EX: <a href='registrar/Agenda.php?idRaid=$raid_lastEXid[$i]'> $raid_lastEX[$i]</a><br>";
						echo "Incursiones EX registradas: $raid_histEX[$i]";
					}
				?></div>",
				maxWidth: 300
			});
			
			var countDownDate<?php echo $i;?> = new Date("<?php echo date($raid_Time[$i]); ?>").getTime();
			
			var x<?php echo $i;?> = setInterval(function() {
				var distance = countDownDate<?php echo $i;?> - new Date().getTime();
				var days = Math.floor(distance / (1000 * 60 * 60 * 24));
				var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	<?php 
				if( $raid_Level[$i]<6 ) {
	 ?>
					var displaying = minutes+"m "+seconds+"s";
	<?php 
				} else {
	 ?>
					var displaying = days+"d "+hours+"h "+minutes+"m "+seconds+"s";
	<?php 
				}
	 ?>
				// Display the result in the element with id="count"
				marker<?php echo $i;?>.setLabel(displaying);
				if (distance < 0) {
	<?php 
					$restartCount[$i] = 0;
					if($raid_Level[$i]>0 && $raid_idPokemon[$i]==0){
						$raidTimeTest = masMinutos(45,$raid_Time[$i]);
						$txtLabel[$i] = "Nivel $raid_Level[$i] Abierto! $raidTimeTest";
						if( $raid_Time[$i] >= date(time()) ){
							$consultar = $conectar->query("SELECT idPokemon,nameAmerica FROM Pokemon WHERE raidLevel=$raid_Level[$i]");
							$x = $consultar->num_rows;
							if($x == 1) while($row2 = $consultar->fetch_assoc()) {
								$txtLabel[$i] = "$row2[nameAmerica]";
								$nombreImagen[$i] = imagen($row2[idPokemon],$raid_Level[$i]);
								$sqlUpdate = "UPDATE raids SET idPokemon=$row2[idPokemon] WHERE idRaid=$row[idRaid] AND idPokemon=0";
								if ($conectar->query($sqlUpdate) === TRUE){
									$restartCount[$i] = 1;
								}
							}
						}
					}else if($raid_idPokemon[$i]>0){
						$txtLabel[$i] = "¡FINALIZADO!";
					}
	 ?>
					marker<?php echo $i;?>.setLabel("<?php echo $txtLabel[$i]; ?>");
					marker<?php echo $i;?>.setIcon("<?php echo $nombreImagen[$i]; ?>");
					if( <?php echo $restartCount[$i]; ?> == 1 )
						distance = new Date("<?php echo date($raidTimeTest); ?>").getTime() - new Date().getTime();
					else
						clearInterval(x<?php echo $i;?>);
				}
				if (distance < 0)
					document.getElementById("count<?php echo $i;?>").innerHTML = "<?php echo $txtLabel[$i]; ?>";
				else
					document.getElementById("count<?php echo $i;?>").innerHTML = displaying;
				
			}, 1000);
<?php
		}
	$conectar->close();
 ?>
      }
    </script>
	<!-- ----------------------------------------------------------- -->
    <script async defer
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCM6oGvs1Ax22HKo9EfdDWbCfIAAloyy0U&callback=initMap">
    </script>

<script>
	function fverRegistraRaid(){
		if(document.getElementById('verRegistraRaid').style.display=="none"){
			document.getElementById('verRegistraRaid').style.display="block";
			document.getElementById('txtRegistraRaid').innerHTML = "Volver al mapa";
		}else if(document.getElementById('verRegistraRaid').style.display=="block"){
			document.getElementById('verRegistraRaid').style.display="none";
			document.getElementById('txtRegistraRaid').innerHTML = "Registro de Raid";
		}
	}
	function statusP(N){
		switch(N){
			case 0:
				document.getElementById('fechaF').value = "<?php echo date("Y-m-d");?>"
				document.getElementById('verPokemon').style.display="block";
				document.getElementById('verNivel').style.display="none";
				document.getElementById('verMinutos').style.display="block";
				document.getElementById('verCalendario').style.display="none";
				document.getElementById('minutosF').min = "2";
				document.getElementById('minutosF').max = "45";
				document.getElementById('txtStatus').innerHTML = "Terminar Raid";
				break;
			case 1:
				document.getElementById('fechaF').value = "<?php echo date("Y-m-d");?>"
				document.getElementById('verPokemon').style.display="none";
				document.getElementById('verNivel').style.display="block";
				document.getElementById('verMinutos').style.display="block";
				document.getElementById('verCalendario').style.display="none";
				document.getElementById('minutosF').min = "1";
				document.getElementById('minutosF').max = "59";
				document.getElementById('txtStatus').innerHTML = "Abrir Huevo";
				break;
			case 2:
				document.getElementById('fechaF').readonly = false;
				document.getElementById('verPokemon').style.display="none";
				document.getElementById('verNivel').style.display="none";
				document.getElementById('verMinutos').style.display="none";
				document.getElementById('verCalendario').style.display="block";
				break;
		}
	}
</script>
