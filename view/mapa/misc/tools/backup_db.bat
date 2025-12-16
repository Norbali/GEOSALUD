rem configura los parametros de conexion
@echo *************************
@echo backup_db v0.1
@echo .
@echo fandresherrera(at)hotmail(dot)com
@echo .
@echo *************************
@echo off
color 20
pg_dump prueba -h localhost -p 5432 -U postgres  > backup_prueba.sql
pause
exit

