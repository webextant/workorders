@echo off
:: DESC: Executes tests in a Microsoft Windows environment.

:: SET ENV VARIABLES AS NEEDED
::set PATH=%PATH%;C:\php

:: START TESTS
echo .
echo - - - - - - - - - - - - - - APPROVER TESTS - - - - - - - - - - - - - - - - -
php.exe phpunit-5.2.9.phar approvertest.php
echo .
echo .
echo - - - - - - - - - - - - - - EMAIL TESTS - - - - - - - - - - - - - - - - - -
php.exe phpunit-5.2.9.phar emailtest.php
echo .
echo .
echo - - - - - - - - - - - - - - WORKFLOW TESTS - - - - - - - - - - - - - - - - - -
php.exe phpunit-5.2.9.phar workflowtest.php
echo .
echo .
echo - - - - - - - - - - - - - - WORKORDER TESTS - - - - - - - - - - - - - - - - - -
php.exe phpunit-5.2.9.phar workordertest.php
echo .
echo .
echo - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -