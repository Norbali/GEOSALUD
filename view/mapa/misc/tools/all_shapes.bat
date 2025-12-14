@echo *************************
@echo all_shapes to pgsql v0.1
@echo .
@echo fandresherrera(at)hotmail(dot)com
@echo .
@echo *************************
@echo off
color 20
for %%x in (*.shp) do shp2pgsql %%~nx %%~nx > %%~nx.sql
pause
exit