@echo off

set SH=%CYGWIN_HOME%\bin\zsh.exe

"%SH%" -c "/bin/ansible-playbook %*"