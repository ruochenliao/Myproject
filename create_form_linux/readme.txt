Documentation for Warmup Assignment 1
=====================================

+-------+
| BUILD |
+-------+

Comments:  1. in the Plus Points part(A) just follow the part(A) command, my code can work well
           2. in the plus points part(B), firstly, have to use the command "make" to compile the file and
	      then copy and paste the test command to nunki. There is no error in part B
           3. Both part(A) and part(B) can work well for the test.

+---------+
| GRADING |
+---------+

(A) Doubly-linked Circular List : 40 out of 40 pts

In part(B), please use "make" to compile first, then run the test command

(B.1) Sort (file) : 30 out of 30 pts              
(B.2) Sort (stdin) : 30 out of 30 pts

Missing required section(s) in README file : (comments: no section is missed)
Cannot compile :   (comments: the code can be compiled well)
Compiler warnings : (comments: no warning when compiling)
"make clean" : ( comments: all *.o and excutable file will be moved after the commend "make clean")
Segmentation faults : (Comments: the code doesn't have segmentation fault) 
Separate compilation : 
		(Comments : the code is not compiled with a signle line, 
			    executable is not in a single module 
			    generating other execcutable in line one.
			    there are only 2 *.h file)
Malformed input : (Comments : the code will respond accordingly as follow
		  cat $srcdir/f101 | ./warmup1 sort: the time input is greater than current time
		  cat $srcdir/f102 | ./warmup1 sort: there is malformed input for the first character
		  cat $srcdir/f103 | ./warmup1 sort: amount input has format error
		  cat $srcdir/f104 | ./warmup1 sort: input error, some data didn't input
		  cat $srcdir/f105 | ./warmup1 sort: too many filed for input
		  cat $srcdir/f106 | ./warmup1 sort: the input data is too large
		  cat $srcdir/f107 | ./warmup1 sort: error: input shouldn't have the same time stamp)
Too slow : (Comments: the code runs very fast)
Bad commandline : (Comments: the code reports the error according to the malformed command)
Bad behavior for random input : (Comments:there will be standard output)
Did not use My402List and My402ListElem to implement "sort" in (B) : (Comments: I used My402List and My402ListElem)

+------+
| BUGS |
+------+

Comments: no bugs

+-------+
| OTHER |
+-------+

Comments on design decisions: hope the effort design makes sense
Comments on deviation from spec: no deviation with the standard
