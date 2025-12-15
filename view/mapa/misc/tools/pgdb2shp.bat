rem configura los parametros de conexion a la base de datos, y ubica el script en donde quieres que se generen los shapes
@echo *************************
@echo pgdb2shp to pgsql v0.1
@echo .
@echo fandresherrera(at)hotmail(dot)com
@echo .
@echo *************************
@echo off
color 20
ogr2ogr -f "ESRI Shapefile" puntos.shp PG:"dbname=prueba user=postgres password=1234567 host=localhost" -sql "select * from puntos"
pause
exit