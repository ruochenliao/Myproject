clear all;
receiver=importdata('receiverXY.csv');
transmitter=importdata('transmitterXY.csv');
exp7=importdata('wifiExp7.csv');%1st experiment
exp8=importdata('wifiExp8.csv');%2st experiment
exp9=importdata('wifiExp9.csv');%3st experiment
exp10=importdata('wifiExp10.csv');%4st experiment
exp11=importdata('wifiExp11.csv');%5st experiment
exp12=importdata('wifiExp12.csv');%6st experiment
exp13=importdata('wifiExp13.csv');%7st experiment
exp14=importdata('wifiExp14.csv');%8st experiment
exp15=importdata('wifiExp15.csv');%9st experiment
exp16=importdata('wifiExp16.csv');%10st experiment
exp17=importdata('wifiExp17.csv');%11st experiment
exp18=importdata('wifiExp18.csv');%12st experiment

distance=zeros(12,8);
for i=1:12
    for j=1:8
        %distance(i,j) = log(((transmitter(i,1)-receiver(j,1))^2+(transmitter(i,2)-receiver(j,2))^2)^0.5);
        distance(i,j)= log10(sqrt((transmitter(i,1)-receiver(j,1))^2+(transmitter(i,2)-receiver(j,2))^2));
    end
end
x = [];
y = [];
for i=1:8
    for j=1:31
       %A[j,i] = ( distance(1,i), exp7(j,1) );
       if( exp7(j,i+1)==500 )
        continue;
       end 
       x = [x,distance(1,i)];
       y = [y,-exp7(j,i+1)];
    end
end

plot( x,y,'.');
%{
a = []
b = []
for i = transmitterX'
    for j = receiverX'
        a = [a, (i -j)^2 ]
    end
end
for i = transmitterY'
    for j = receiverY'
        b = [b, (i -j)^2 ] 
    end
end

c = a - b
x = log(c.^2)
x7 = x(1:8)

A7 = [recv72 recv73 recv74 recv75 recv76 recv77 recv78 recv79]
[h,w] = size(A7)
y7 = A7(1,:)

for i = 1:h
    y7=A7(i,:)
    plot(x7,y7,'r*')
end
%}