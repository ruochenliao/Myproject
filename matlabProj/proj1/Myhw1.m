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
distance0=zeros(12,8);
for i=1:12
    for j=1:8
        %distance(i,j) = log(((transmitter(i,1)-receiver(j,1))^2+(transmitter(i,2)-receiver(j,2))^2)^0.5);
        distance0(i,j)= sqrt((transmitter(i,1)-receiver(j,1))^2+(transmitter(i,2)-receiver(j,2))^2);
       
    end
end
x0 = [];
x = [];
y = [];
length(exp8)
%exp 7
distance = log10(distance0);
for i=1:8
    for j=1:length(exp7)
       if( exp7(j,i+1)~=500 )
           x0 = [x0,distance0(1,i)];
           x = [x,distance(1,i)];
           y = [y,-exp7(j,i+1)];
       end
    end
end
% exp 8 
for i=1:8
    for j=1:length(exp8)
       if( exp8(j,i+1)~=500 )
           x0 = [x0,distance0(2,i)];
           x = [x,distance(2,i)];
           y = [y,-exp8(j,i+1)];
       end
    end
end
%exp 9
for i=1:8
    for j=1:length(exp9)
       if( exp9(j,i+1)~=500 )
           x0 = [x0,distance0(3,i)];
           x = [x,distance(3,i)];
           y = [y,-exp9(j,i+1)];
       end
    end
end

%exp 10
for i=1:8
    for j=1:length(exp10)
       if( exp10(j,i+1)~=500 )
           x0 = [x0,distance0(4,i)];
           x = [x,distance(4,i)];
           y = [y,-exp10(j,i+1)];
       end
    end
end
%exp 11
for i=1:8
    for j=1:length(exp11)
       if( exp11(j,i+1)~=500 )
           x0 = [x0,distance0(5,i)];
           x = [x,distance(5,i)];
           y = [y,-exp11(j,i+1)];
       end
    end
end
%exp 12
for i=1:8
    for j=1:length(exp12)
       if( exp12(j,i+1)~=500 )
           x0 = [x0,distance0(6,i)];
           x = [x,distance(6,i)];
           y = [y,-exp12(j,i+1)];
       end
    end
end
%exp 13
for i=1:8
    for j=1:length(exp13)
       if( exp13(j,i+1)~=500 )
           x0 = [x0,distance0(7,i)];
           x = [x,distance(7,i)];
           y = [y,-exp13(j,i+1)];
       end
    end
end
%exp 14
for i=1:8
    for j=1:length(exp14)
       if( exp14(j,i+1)~=500 )
           x0 = [x0,distance0(8,i)];
           x = [x,distance(8,i)];
           y = [y,-exp14(j,i+1)];
       end
    end
end
%exp 15
for i=1:8
    for j=1:length(exp15)
       if( exp15(j,i+1)~=500 )
           x0 = [x0,distance0(9,i)];
           x = [x,distance(9,i)];
           y = [y,-exp15(j,i+1)];
       end
    end
end
%exp 16
for i=1:8
    for j=1:length(exp16)
       if( exp16(j,i+1)~=500 )
           x0 = [x0,distance0(10,i)];
           x = [x,distance(10,i)];
           y = [y,-exp16(j,i+1)];
       end
    end
end
%exp 17
for i=1:8
    for j=1:length(exp17)
       if( exp17(j,i+1)~=500 )
           x0 = [x0,distance0(11,i)];
           x = [x,distance(11,i)];
           y = [y,-exp17(j,i+1)];
       end
    end
end
%exp 18
for i=1:8
    for j=1:length(exp18)
       if( exp18(j,i+1)~=500 )
           x0 = [x0,distance0(12,i)];
           x = [x,distance(12,i)];
           y = [y,-exp18(j,i+1)];
       end
    end
end

p = polyfit(x,y,1);
x1 = linspace(min(x),max(x));
y1 = polyval(p,x1);
plot( x,y,'.');
hold on 
plot(x1,y1);

hold off
eta = -p(1)/10
K = p(2) + 27
standard_diviation = std(x0)
