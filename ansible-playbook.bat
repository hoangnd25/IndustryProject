@echo off

set SH=%~dp0%\cygwin\bin\zsh.exe

"%SH%" -c "/bin/ansible-playbook %*"