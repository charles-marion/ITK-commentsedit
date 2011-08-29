#!/bin/bash
eval `ssh-agent`
ssh-add server.key
if test -f /projects/ITK-commentsedit/ITKTMP/todo.txt
then
  for line in $(cat /projects/ITK-commentsedit/ITKTMP/todo.txt)
    do
      cd "`echo $line |cut -d';' -f2`" && git checkout `echo $line |cut -d';' -f1`
      cd "`echo $line |cut -d';' -f2`" && git gerrit-push
      cd "`echo $line |cut -d';' -f2`" && git checkout master
      cd "`echo $line |cut -d';' -f2`" && git branch -D `echo $line |cut -d';' -f1`
    done
 cat /projects/ITK-commentsedit/ITKTMP/todo.txt >> /projects/ITK-commentsedit/ITKTMP/todo-old.txt
 rm -f /projects/ITK-commentsedit/ITKTMP/todo.txt
fi
