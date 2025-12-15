
    <link rel="stylesheet" type="text/css" href="/GEOSALUD/view/mapa/misc/img/dc.css">
    <script src="/GEOSALUD/view/mapa/misc/lib/mscross-1.1.9.js" type="text/javascript"></script>

    <div class="content">
        <div class="page-inner">
            <div class="mscross"
                style="overflow:hidden; width: 700px; height:700px; -moz-user-select:none; position:relative; border-radius: 5%; margin:0 auto; display:block;"
                id="dc_main">


            </div>

            <div id="Layer2">
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
                        <Strong>barrios</Strong>
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

            <div class="modal fade" id="modalNombre" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ingresar nombre</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" id="nombrePunto" class="form-control" placeholder="Nombre del punto">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="btnGuardarNombre">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        myMap1 = new msMap(document.getElementById("dc_main"), "standardRight");
        myMap1.setCgi("/ms4w/cgi-bin/mapserv.exe");
        myMap1.setMapFile("/ms4w/Apache/htdocs/GEOSALUD/view/mapa/caliregistrar.map");
        myMap1.setFullExtent(1053867, 1068491, 860190);
        myMap1.setLayers("Cali Comunas Barrios Malla_Vial");

        myMap2 = new msMap(document.getElementById("dc_main2"));
        myMap2.setActionNone();
        myMap2.setFullExtent(1053867.55, 1068491.88, 860190.55);
        myMap2.setMapFile("/ms4w/Apache/htdocs/GEOSALUD/view/mapa/caliregistrar.map");
        myMap2.setLayers("Cali");
        myMap1.setReferenceMap(myMap2);
        myMap1.redraw();
        myMap2.redraw();

        var $issertZoo = new msTool('InsertarCoordenadas', insertZ, '/GEOSALUD/view/mapa/misc/img/ubicacionZoocriadero.png', queryI);
        var $consultarZoo = new msTool('Obtener Informacion', consultaZ, '/GEOSALUD/view/mapa/misc/img/obtenerInformacion.png', queryII);

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

        function queryI(event, map, x, y, xx, yy) {
            if (seleccionado) {

                alert("Coordenadas mapa: x: " + x + " y: " + y + " reales: x " + xx + " y: " + yy);

                // Mostrar modal
                var modal = new bootstrap.Modal(document.getElementById('modalNombre'));
                modal.show();

                // Boton guardar dentro del modal
                document.getElementById("btnGuardarNombre").onclick = function () {

                    let nombre = document.getElementById("nombrePunto").value;

                    // AJAX con consulta1 (como tú lo tienes)
                    consulta1 = objetoAjax();
                    consulta1.open(
                        "GET",
                        "insertar_punto.php?x=" + xx + "&y=" + yy + "&nombre=" + nombre,
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
        myMap1.redraw();
        myMap2.redraw();
    </script>