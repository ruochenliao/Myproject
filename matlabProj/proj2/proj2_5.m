clc;
clear all;
G = [1 1 0 1 0 0 1;0 1 0 1 0 1 0;1 1 1 0 0 0 0; 1 0 0 1 1 0 0];
%G = [1 1 1 0 0 0 0;1 0 0 1 1 0 0; 0 1 0 1 0 1 0; 1 1 0 1 0 0 1];
G = G';
%A = [0 0 0 0; 1 0 0 0; 0 1 0 0; 1 1 0 0; 0 0 1 0; 1 0 1 0; 0 1 1 0; 1 1 1 0; 0 0 0 1; 1 0 0 1; 0 1 0 1; 1 1 0 1; 0 0 1 1; 1 0 1 1; 0 1 1 1; 1 1 1 1];
A = [0 0 0 0; 0 0 0 1; 0 0 1 0; 0 0 1 1; 0 1 0 0; 0 1 0 1; 0 1 1 0; 0 1 1 1; 1 0 0 0; 1 0 0 1; 1 0 1 0;1 0 1 1; 1 1 0 0; 1 1 0 1; 1 1 1 0; 1 1 1 1];
A= A';
x = G*A;
disp('1 hamming (7,4) original 4 bits code has 16 possible format from 0000 to 1111, so ');
A= A'
disp('x = G'' * A, x''=  ');
x= x'
disp('so change odd number to 1 and change even number to 0 codeword = ');
x = [0 0 0 0 0 0 0; 
    1 0 0 1 1 0 0; 
    1 1 1 0 0 0 0;
    0 1 1 1 1 0 0; 
    0 1 0 1 0 1 0; 
    1 1 0 0 1 1 0; 
    1 0 1 1 0 1 0; 
    0 0 1 0 1 1 0; 
    1 1 0 1 0 0 1;
    0 1 0 0 1 0 1;
    0 0 1 1 0 0 1;
    1 0 1 0 1 0 1;
    1 0 0 0 0 1 1;
    0 0 0 1 1 1 1;
    0 1 1 0 0 1 1;
    1 1 1 1 1 1 1];
x= x
%h1 = hammgen(G);
ZeroMatrix = zeros(3,1);
%H = ZeroMatrix/ (x(2,:)')
disp('3 systematic form of G is [I P]');
G = [1 0 0 0 1 1 1; 0 1 0 0 1 1 0; 0 0 1 0 0 1 1; 0 0 0 1 1 0 1]
disp('G =[I(k)|P]; => H = [P''|I(n-k)]'); 
H =[[1 1 1; 1 1 0; 0 1 1; 1 0 1]' eye(3)]
disp('4 suppose the original');
data = [1 0 1 0]
disp('generater matrix');
G = G'
disp('transmit vector v = G * data');
v = G*data';
v = [1 0 1 0 1 0 0]'
disp('erro occurs at the 6th bit and change the v to v_ = [1 1 1 0 1 0 0]''');
v_ = [ 1 1 1 0 1 0 0]';
disp('then z is H*v');
z = H*v_
disp('z = [3 3 2]=[1 1 0] means the 6th bit was corrupted, so the error is detected and can be v_ can be changed back to v');

disp('5 unable to detect error when 4 bits were corrupted');
disp('v = [1 0 1 0 1 0 0] is changed to v_ = [0 1 0 1 1 0 0]');
v_ = [0 1 0 1 1 0 0]';
disp('z= H*v_');
z=H*v_
disp('z = [3 1 1]= [1 1 1], it can not point to the 4 errors because it is imppossible for hamming(7,4) to point out 4 positions error by only giving the matrix z'); 