<?php 
    include_once('../../lib/helpers.php');
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Mapa</title>

        <link rel="stylesheet" href="/GEOSALUD/view/mapa/misc/img/dc.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="/GEOSALUD/view/mapa/misc/lib/mscross-1.1.9.js"></script>
        <link rel="stylesheet" href="diseñomapa.css">


        <style>
            html, body {
                margin: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
            }
            
           /* Contenedor del mapa grande */
            #dc_main {
                width: 90%;
                height: 700px;
                margin: 0 auto; /* centra el mapa */
            }

            /* Mini mapa */
            #Layer1 {
                position: absolute;
                top: 15px;
                right: 20px;
                z-index: 1000;
            }

            /* Capas debajo del mini mapa */
            #Layer2 {
                position: absolute;
                top: 170px; /* debajo del mini mapa */
                right: 20px;
                background: rgba(255,255,255,0.9);
                padding: 10px;
                border-radius: 6px;
                z-index: 1000;
            }
        </style>
    </head>

    <body>

        <div class="mscross"
                style="width: 870px; height:1000px; margin:0 auto;" id="dc_main">
        </div>

            <div id="Layer2" >
                <form name="select_layers">
                    <p align="left">
                        <input checked onclick="chgLayers()" type="checkbox" name="layer[0]" value="Cali">
                        <Strong>Poligono</Strong>
                    </p>

                    <p align="left">
                        <input checked onclick="chgLayers()" type="checkbox" name="layer[1]" value="Comunas">
                        <Strong>Comuna</Strong>
                    </p>
                    <p align="left">
                        <input checked onclick="chgLayers()" type="checkbox" name="layer[2]" value="Barrios">
                        <Strong>Barrios</Strong>
                    </p>
                    <p align="left">
                        <input checked onclick="chgLayers()" type="checkbox" name="layer[3]" value="Malla_Vial">
                        <Strong>Malla Vial</Strong>
                    </p>
                    <p align="left">
                        <input checked onclick="chgLayers()" type="checkbox" name="layer[4]" value="Puntos">
                        <Strong>Puntos Guardados</Strong>
                    </p>


                </form>
            </div>
            <div id="Layer1">
                <div style="overflow: auto; width: 140px; height: 140px; -moz-user-select:none; position:relative; z-index:100;"
                    id="dc_main2">
                </div>
            </div>

            <!-- MODALES --> 
        <!-- Aqui pueden colocar todas las modales pero tiene que id diferente a cada modal-->
        
        <div class="modal fade" id="modalNombre" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ingresar nombre</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                        <div class="form-group">
                            <label for="id_zoocriadero">ID Zoocriadero</label>
                            <input type="number" class="form-control" id="id_zoocriadero" name="id_zoocriadero" required>
                        </div>

                        <div class="form-group">
                            <label for="nombre_zoocriadero">Nombre del Zoocriadero</label>
                            <input type="text" class="form-control" id="nombre_zoocriadero" name="nombre_zoocriadero" maxlength="150" required>
                        </div>

                        <div class="form-group">
                            <label for="direccion_zoocriadero">Dirección</label>
                            <input type="text" class="form-control" id="direccion_zoocriadero" name="direccion_zoocriadero" maxlength="200" required>
                        </div>

                        <div class="form-group">
                            <label for="telefono_zoocriadero">Teléfono</label>
                            <input type="number" class="form-control" id="telefono_zoocriadero" name="telefono_zoocriadero" required>
                        </div>

                        <div class="form-group">
                            <label for="correo_zoocriadero">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo_zoocriadero" name="correo_zoocriadero" maxlength="100" required>
                        </div>

                        <div class="form-group">
                            <label for="barrio">Barrio</label>
                            <input type="text" class="form-control" id="barrio" name="barrio" maxlength="100" required>
                        </div>

                        <div class="form-group">
                            <label for="comuna">Comuna</label>
                            <input type="text" class="form-control" id="comuna" name="comuna" maxlength="50" required>
                        </div>

                        <div class="form-group">
                            <label for="documento_responsable">Documento del Responsable</label>
                            <input type="text" class="form-control" id="documento_responsable" name="documento_responsable" maxlength="20" required>
                        </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" id="btnGuardarNombre">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
        myMap1 = new msMap(document.getElementById("dc_main"), "standardLeft");
        myMap1.setCgi("/cgi-bin/mapserv.exe");
        myMap1.setMapFile("/ms4w/Apache/htdocs/GEOSALUD/view/mapa/caliregistrar.map");
        myMap1.setFullExtent(1053867, 1068491,  860190, 879411);
        myMap1.setLayers("Cali Comunas Barrios Malla_Vial Puntos");

        myMap2 = new msMap(document.getElementById("dc_main2"));
        myMap2.setActionNone();
        myMap2.setFullExtent(1053867, 1068491, 860190, 879411);
        myMap2.setMapFile("/ms4w/Apache/htdocs/GEOSALUD/view/mapa/caliregistrar.map");
        myMap2.setLayers("Cali");
        myMap1.setReferenceMap(myMap2);
        myMap1.redraw();
        myMap2.redraw();

        var $issertZoo = new msTool('InsertarCoordenadas', insertZ, '/GEOSALUD/view/mapa/misc/img/ubicacionZoocriadero.png', queryI);
        var $consultarZoo = new msTool('Obtener Informacion', consultaZ, '/GEOSALUD/view/mapa/misc/img/obtenerInformacion.png', queryII);
        //aqui se agregan los botones al toolbar y se les asigna la funcion


        myMap1.getToolbar(0).addMapTool($issertZoo);
        myMap1.getToolbar(0).addMapTool($consultarZoo);

        chgLayers();
        var selectlayer = -1;

        function chgLayers() {

            var list = "Layers ";

            var objForm = document.forms[0];

            for (let i = 0; i < document.forms[0].length; i++) {
                if (objForm.elements["layer[" + i + "]"].checked) {
                    list = list + objForm.elements["layer[" + i + "]"].value + " ";
                }
            }
            myMap1.setLayers(list);
            myMap1.redraw();
        }
        function objetoAjax() {
            var xmlhttp = false;
            try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (E) {
                    xmlhttp = false;
                }
            }
            if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
                xmlhttp = new XMLHttpRequest();
            }
            return xmlhttp;
        }

        var seleccionado = false;

        function insertZ(e, map) {
            map.getTagMap().style.cursor = "crosshair";
            seleccionado = true;
        }
        function consultaZ(e, map) {
            map.getTagMap().style.cursor = "crosshair";
            seleccionado = true;
        }

        //funcion para insertar zoocriadero
        function queryI(event, map, x, y, xx, yy) {
            if (seleccionado) {

                //alert("Coordenadas mapa: x: " + x + " y: " + y + " reales: x " + xx + " y: " + yy);
                // Mostrar modal
                var modal = new bootstrap.Modal(document.getElementById('modalNombre'));
                modal.show();

                // Boton guardar dentro del modal
                document.getElementById("btnGuardarNombre").onclick = function () {
                    //recoger valor de input
                    let id_zoocriadero = document.getElementById("id_zoocriadero").value;
                    let nombre_zoocriadero = document.getElementById("nombre_zoocriadero").value;
                    let direccion_zoocriadero = document.getElementById("direccion_zoocriadero").value;
                    let telefono_zoocriadero = document.getElementById("telefono_zoocriadero").value;
                    let correo_zoocriadero = document.getElementById("correo_zoocriadero").value;
                    let barrio = document.getElementById("barrio").value;
                    let comuna = document.getElementById("comuna").value;
                    let documento_responsable = document.getElementById("documento_responsable").value;

                    consulta1 = objetoAjax();
                    consulta1.open(
                        "GET",
                        "<?php echo getUrl('Mapa','Mapa','registrarZoocriadero', false, 'ajaxMapa'); ?>"
                        + "?x=" + xx
                        + "&y=" + yy
                        + "&id_zoocriadero=" + id_zoocriadero
                        + "&nombre_zoocriadero=" + nombre_zoocriadero
                        + "&direccion_zoocriadero=" + direccion_zoocriadero
                        + "&telefono_zoocriadero=" + telefono_zoocriadero
                        + "&correo_zoocriadero=" + correo_zoocriadero
                        + "&barrio=" + barrio
                        + "&comuna=" + comuna
                        + "&documento_responsable=" + documento_responsable,
                        true
                    );

                    consulta1.onreadystatechange = function () {
                        if (consulta1.readyState == 4) {
                            alert(consulta1.responseText);
                        }
                    };

                    consulta1.send(null);
                    modal.hide();
                };

                seleccionado = false;
                map.getTagMap().style.cursor = "default";
            }
        }

        //funcion para obtener informacion del zoocriadero
        function queryII(event, map, x, y, xx, yy) {
            if (seleccionado) {
                alert("Coordenadas mapa: x: " + x + " y: " + y + "reales : x " + xx + " y: " + yy);
                consulta2 = objetoAjax();
                consulta2.open("GET", "consulta.php?x=" + xx + "&y=" + yy, true);
                consulta2.onreadystatechange = function () {
                    if (consulta2.readyState == 4) {
                        var result = consulta2.responseText;
                        alert(result);
                        //myMap1.redraw();
                    }
                }
                consulta2.send(null);
                seleccionado = false;
                map.getTagMap().style.cursor = "default";
            }
        }

        //demas funciones javascript para el mapa(actualizar, inhalitar)

        // Inicializar mapas
        myMap1.redraw();
        myMap2.redraw();
    </script>

    </body>
</html>
