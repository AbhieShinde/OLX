@echo off

title AbhieShinde OLX Database Configurations

COLOR 1F

echo                   Abhishek Shinde
echo *********************************************************
echo    Importing PostgreSQL Database for AbhieShinde-OLX System
echo *********************************************************
echo.
echo.

set /p host= Your PostgreSQL Host :
set /p port= Your PostgreSQL Port :
set /p username= Your PostgreSQL Username :
set /p pass= Your PostgreSQL Password :
set /p dbname= New Database Name :

REM Changing directory where PostgreSQL is installated
cd C:\Program Files\PostgreSQL\

echo.
echo Enter your PostgreSql server's Password once again and Wait for few seconds . . .
echo.

createdb -h %host% -p %port% -U %username% %dbname%

echo.
echo *********************************************************
echo             Database %dbname% is created!
echo *********************************************************
echo.

REM Changing directory back to batch sripts directory
SET dirpath=%~dp0
cd %dirpath:~0,-1%


REM Setting .ini file
cd ../src/config

(
echo host='%host%'
echo dbname='%dbname%'
) > dbconfig.ini

(
echo user='%username%' 
echo pass='%pass%'
echo salt='xento123'
) > .env

echo.
echo ***********************
echo Database Configurations has been added at src/config/
echo ***********************
echo.

REM Changing directory back to batch sripts directory
SET dirpath=%~dp0
cd %dirpath:~0,-1%

echo.
echo Importing Tables into %dbname% . . .
echo.

psql -U %username% %dbname% < database_olx.sql

echo.
echo.
echo *********************************************************
echo         Database Tables are Imported into
echo                      %dbname% 
echo			        Successfully
echo *********************************************************
echo.
echo.

REM "Batch Script by Abhishek Sunil Shinde."
pause
