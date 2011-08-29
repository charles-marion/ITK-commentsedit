#!/bin/bash
eval `ssh-agent`
ssh-add server.key
if test -f /home/charles/ITKTMP/todo.txt
then
  for line in $(cat /home/charles/ITKTMP/todo.txt)
    do
      cd "`echo $line |cut -d';' -f2`" && git checkout `echo $line |cut -d';' -f1`
      cd "`echo $line |cut -d';' -f2`" && git gerrit-push
      cd "`echo $line |cut -d';' -f2`" && git checkout master
      cd "`echo $line |cut -d';' -f2`" && git branch -D `echo $line |cut -d';' -f1`
    done
 cat /home/charles/ITKTMP/todo.txt >> /home/charles/ITKTMP/todo-old.txt
 rm -f /home/charles/ITKTMP/todo.txt
fi
