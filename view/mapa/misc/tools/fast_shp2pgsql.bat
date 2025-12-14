rem configura los parametros de conexion a la base de datos, y ubica el script en la ubicacion donde se encuentren los archivos .shp
@echo *************************
@echo fast_shp2pgsql to pgsql v0.1
@echo .
@echo fandresherrera(at)hotmail(dot)com
@echo .
@echo *************************
@echo off
color 20
for %%x in (*.shp) do ogr2ogr -f "PostgreSQL" PG:"host=localhost user=postgres dbname=ginebra password=1234567" %%~nx.shp
pause
exit