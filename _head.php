<?php
		session_start();
		date_default_timezone_set('America/Mexico_City');
		if($titulo=="incursionespokemongo.tk")
		    $subedir="";
		else
		    $subedir="../";
		$dirPokebola = $subedir."img/Honorball_icon-icons.com_67450.png";
		$libs = $subedir;
?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<link rel="icon" type="image/png" href="<?php echo $dirPokebola;?>" />
	<link rel="stylesheet" href="<?echo$libs;?>PoGO-Awesome-master/css/PoGO-Awesome.1.0.0.css" type="text/css">
	<link rel="stylesheet" href="<?echo$libs;?>css/estilos.css">
	<link rel="stylesheet" href="<?echo$libs;?>css/fonts.css">
	<link rel="stylesheet" href="<?echo$libs;?>css/grids.css">
	<script src="<?echo$libs;?>js/main.js"></script>
	<script src="<?echo$libs;?>js/w3data.js"></script>
	<script>
		function nombreImagen(idPokemon,levelEgg) {
			if(idPokemon>0)
				return "img/Pokemon/$idPokemon.ico.png";
			else if(idPokemon==0){
				if(levelEgg==1||levelEgg==2)
					return "img/raidEgg_Starter.png";
				if(levelEgg==3||levelEgg==4)
					return "img/raidEgg_Rare.png";
				if(levelEgg==5)
					return "img/raidEgg_Legendary.png";
			}
		}
	</script>
<?php
	function imagen($idPokemon,$levelEgg){
		if($idPokemon>0)
			return "img/Pokemon/$idPokemon.ico.png";
		else if($idPokemon==0){
			if($levelEgg==1||$levelEgg==2)
				return "img/raidEgg_Starter.png";
			if($levelEgg==3||$levelEgg==4)
				return "img/raidEgg_Rare.png";
			if($levelEgg==5)
				return "img/raidEgg_Legendary.png";
		}
	}
	/*
	function listaGimnasios($conectar,$idgym){
		$gimnasios = $conectar->query("SELECT * FROM gym ORDER BY name");
        if ($gimnasios->num_rows > 0)
            while($row = $gimnasios->fetch_assoc()) {
                if($idgym != $row[idgym])
                    echo "<option value='$row[idgym]'>$row[name] ( $row[lat] , $row[lon] )</option>";
                }
                else
                    echo "¡ERROR consultando gimnasios!<hr>";
	}
	*/
?>
