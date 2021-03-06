<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Agregar Producto</title>
        <link href="assets/css/main.css" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
        <script src="http://malsup.github.com/jquery.form.js"></script>
        <style>
            form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px }
            #progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
            #bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
            #percent { position:absolute; display:inline-block; top:3px; left:48%; }
        </style>
    </head>
    <body>
        <?php include_once 'Includes/header.php'; ?>
        <div class="contenedor">
            <?php
            include_once 'clases/db_connect.php';
            if (isset($_POST['submitted'])) {
                /* ------------------------------------BEGIN FILE UPLOADER-------------------------------------------- */
                $output_dir = "Images/productos/";
                $name_ok;
                if (isset($_FILES["myfile"])) {
                    //Filter the file types , if you want.
                    if ($_FILES["myfile"]["error"] > 0) {
                        echo "Error: " . $_FILES["file"]["error"] . "<br>";
                    } else {
                        //move the uploaded file to uploads folder;
                        $id_unico = uniqid();
                        move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $id_unico . $_FILES["myfile"]["name"]);
                        $name_ok = $output_dir . $id_unico . $_FILES["myfile"]["name"];
                    }
                }
                /* ------------------------------------END FILE UPLOADER-------------------------------------------- */
                foreach ($_POST AS $key => $value) {
                    $_POST[$key] = $value;
                }
                $sql = "INSERT INTO `producto` (`nombre_p` ,  `descripcion_p` ,  `categoria_p` ,  `precio_p` ,  `activo_p` ,  `imagen_p`,  `fecha_p`   ) VALUES( '{$_POST['nombre_p']}' ,  '{$_POST['descripcion_p']}' ,  '{$_POST['categoria_p']}' ,  '{$_POST['precio_p']}' ,  '{$_POST['activo_p']}' , '$name_ok' ,  '{$_POST['fecha_p']}' ) ";
                mysql_query($sql) or die(mysql_error());
                echo "<script>alert('$name_ok')</script>";
                echo "Registro Agregado.<br />";
                echo "<a href='verProductos.php'>Regresar</a>";
            }
            ?>

            <form action='#' id="myform" method='POST' enctype="multipart/form-data"> 
                <div><b>Nombre del Producto:</b><br /><input type='text' name='nombre_p' required/></div> 
                <div><b>Descripcion del Producto:</b><br /><input type='text' name='descripcion_p' required/></div> 
                <div><b>Categoria:</b><br><select name="categoria_p"><option>-seleccione-</option><?php include_once 'lstcategorias.php'; ?></select></div>
                <div><b>Precio del Producto:</b><br /><input type='text' name='precio_p' required/></div> 
                <div><b>Fecha:</b><br /><input type='date' name='fecha_p' required/></div> 
                <div><b>Activo:</b><br /><input type='text' name='activo_p' required/></div>  
                <input type="file" name="myfile">
                <div><input type='submit' value='Agregar' /><input type='hidden' value='1' name='submitted' /></div> 
            </form> 

            <div id="progress">
                <div id="bar"></div>
                <div id="percent">0%</div >
            </div>
            <br/>

            <div id="message"></div>


            <script>
                $(document).ready(function()
                {

                    var options = {
                        beforeSend: function()
                        {
                            $("#progress").show();
                            //clear everything
                            $("#bar").width('0%');
                            $("#message").html("");
                            $("#percent").html("0%");
                        },
                        uploadProgress: function(event, position, total, percentComplete)
                        {
                            $("#bar").width(percentComplete + '%');
                            $("#percent").html(percentComplete + '%');


                        },
                        success: function()
                        {
                            $("#bar").width('100%');
                            $("#percent").html('100%');

                        },
                        complete: function(response)
                        {
                            $("#message").html("<font color='green'>" + response.responseText + "</font>");
                        },
                        error: function()
                        {
                            $("#message").html("<font color='red'> ERROR: unable to upload files</font>");

                        }

                    };
                    $("#myForm").ajaxForm(options);

                });

            </script>
        </div>
    </body>
</html>
