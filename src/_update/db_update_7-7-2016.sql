/*
appinfo is a db list that has perameters for the appinfo
when new settings perameters are entered into the system 
they may be added here as a new row

Made INFO_request unique so duplicate values could not be used
*/

ALTER TABLE  `appinfo` ADD UNIQUE (

`INFO_request`

);