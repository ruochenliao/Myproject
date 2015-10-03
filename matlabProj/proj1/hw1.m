clear all;
wifiexp7=importdata('wifiExp7.csv');%1st experiment
wifiexp8=importdata('wifiExp8.csv');%2st experiment
wifiexp9=importdata('wifiExp9.csv');%3st experiment
wifiexp10=importdata('wifiExp10.csv');%4st experiment
wifiexp11=importdata('wifiExp11.csv');%5st experiment
wifiexp12=importdata('wifiExp12.csv');%6st experiment
wifiexp13=importdata('wifiExp13.csv');%7st experiment
wifiexp14=importdata('wifiExp14.csv');%8st experiment
wifiexp15=importdata('wifiExp15.csv');%9st experiment
wifiexp16=importdata('wifiExp16.csv');%10st experiment
wifiexp17=importdata('wifiExp17.csv');%11st experiment
wifiexp18=importdata('wifiExp18.csv');%12st experiment
receiverXY=importdata('receiverXY.csv');%calculate distance between the transmitter(j) and the 8 receivers
transmitterXY=importdata('transmitterXY.csv');
distance=zeros(12,8);
i=1;
j=1;
for j=1:12
    for i=1:8
        distance(j,i)=((transmitterXY(j,1)-receiverXY(i,1))^2+(transmitterXY(j,2)-receiverXY(i,2))^2)^0.5;
    end
end
ave=zeros(12,8);
num_500=zeros(12,8);
%%%%exp1
intern=0;
row=31;
for column=1:8
    for m=1:row
        if wifiexp7(m,column+1)~= 500
            wifiexp7(m,column+1)
            intern=wifiexp7(m,column+1)+intern;
        else
            num_500(1,column)=num_500(1,column)+1;
        end
    end
    ave(1,column)=intern/(row-num_500(1,column));
    intern=0;
end

%%%%exp2
intern=0;
row=34;
for column=1:8
for m=1:row
if wifiexp8(m,column+1)~= 500
   intern=wifiexp8(m,column+1)+intern;
else
num_500(2,column)=num_500(2,column)+1;
end
end
ave(2,column)=intern/(row-num_500(2,column));
intern=0;
end

%%%%exp3
intern=0;
row=34;
for column=1:8
for m=1:row
if wifiexp9(m,column+1)~= 500
intern=wifiexp9(m,column+1)+intern;
else
num_500(3,column)=num_500(3,column)+1;
end
end
ave(3,column)=intern/(row-num_500(3,column));
intern=0;
end

%%%%exp4
intern=0;
row=37;
for column=1:8
for m=1:row
if wifiexp10(m,column+1)~= 500
intern=wifiexp10(m,column+1)+intern;
else
num_500(4,column)=num_500(4,column)+1;
end
end
ave(4,column)=intern/(row-num_500(4,column));
intern=0;
end

%%%%exp5
intern=0;
row=41;
for column=1:8
for m=1:row
if wifiexp11(m,column+1)~= 500
intern=wifiexp11(m,column+1)+intern;
else
num_500(5,column)=num_500(5,column)+1;
end
end
ave(5,column)=intern/(row-num_500(5,column));
intern=0;

end
%%%%exp6
intern=0;
row=34;
for column=1:8
for m=1:row
if wifiexp12(m,column+1)~= 500
intern=wifiexp12(m,column+1)+intern;
else
num_500(6,column)=num_500(6,column)+1;
end
end
ave(6,column)=intern/(row-num_500(6,column));
intern=0;
end

%%%%exp7
intern=0;
row=37;
for column=1:8
for m=1:row
if wifiexp13(m,column+1)~= 500
intern=wifiexp13(m,column+1)+intern;
else
num_500(7,column)=num_500(7,column)+1;
end
end
ave(7,column)=intern/(row-num_500(7,column));
intern=0;
end

%%%%exp8
intern=0;
row=61;
for column=1:8
for m=1:row
if wifiexp14(m,column+1)~= 500
intern=wifiexp14(m,column+1)+intern;
else
num_500(8,column)=num_500(8,column)+1;
end
end
ave(8,column)=intern/(row-num_500(8,column));
intern=0;
end
%%%%exp9
intern=0;
row=37;
for column=1:8
for m=1:row
if wifiexp15(m,column+1)~= 500
    intern=wifiexp15(m,column+1)+intern;
else
num_500(9,column)=num_500(9,column)+1;
end
end
ave(9,column)=intern/(row-num_500(9,column));
intern=0;
end
    

%%%%exp10
intern=0;
row=48;
for column=1:8
for m=1:row
if wifiexp16(m,column+1)~= 500
intern=wifiexp16(m,column+1)+intern;
else
num_500(10,column)=num_500(10,column)+1;
end
end
ave(10,column)=intern/(row-num_500(10,column));
intern=0;
end


%%%%exp11
intern=0;
row=34;
for column=1:8
for m=1:row
if wifiexp17(m,column+1)~= 500
intern=wifiexp17(m,column+1)+intern;
else
num_500(11,column)=num_500(11,column)+1;
end
end
ave(11,column)=intern/(row-num_500(11,column));
intern=0;
end

%%%%exp12
intern=0;
row=39;
for column=1:8
for m=1:row
if wifiexp18(m,column+1)~= 500
intern=wifiexp18(m,column+1)+intern;
else
num_500(12,column)=num_500(12,column)+1;
end
end
ave(12,column)=intern/(row-num_500(12,column));
intern=0;
end
x=zeros(1,8);
y=zeros(1,8);
k=1;
l=12;
for i=1:8
x(l,k)=-log10(distance(l,i))*10;
k=k+1;
end
k=1;
for i=1:8
y(l,k)=-ave(l,i)+27;
k=k+1;
end
plot(x(l,:),y(l,:),'r*')

%%begin to calculate the standard deviation
deviation=zeros(8,8);
intern=0;
%for the 1 transmitter
row=31;
l=1;
for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp7(i,column)~=500
intern=(wifiexp7(i,column)-ave(l,j))^2+intern;
count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;
end
%for the 2 transmitter
row=34;
l=2;
for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp8(i,column)~=500
intern=(wifiexp8(i,column)-ave(l,j))^2+intern;


count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;
end
%for the 3 transmitter
row=34;
l=3;
for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp9(i,column)~=500
intern=(wifiexp9(i,column)-ave(l,j))^2+intern;
count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;
end
%for the 4 transmitter
row=37;
l=4;
for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp10(i,column)~=500
intern=(wifiexp10(i,column)-ave(l,j))^2+intern;
count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;
end
%for the 5 transmitter
row=41;
l=5;
for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp11(i,column)~=500
intern=(wifiexp11(i,column)-ave(l,j))^2+intern;
count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;


end
%for the 6 transmitter
row=34;
l=6;
for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp12(i,column)~=500
intern=(wifiexp12(i,column)-ave(l,j))^2+intern;
count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;
end
%for the 7 transmitter
row=37;
l=7;
for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp13(i,column)~=500
intern=(wifiexp13(i,column)-ave(l,j))^2+intern;
count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;
end
%for the 8 transmitter
row=61;
l=8;
for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp14(i,column)~=500
intern=(wifiexp14(i,column)-ave(l,j))^2+intern;
count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;
end
%for the 9 transmitter
row=37;
l=9;

for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp15(i,column)~=500
intern=(wifiexp15(i,column)-ave(l,j))^2+intern;
count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;
end
%for the 10 transmitter
row=48;
l=10;
for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp16(i,column)~=500
intern=(wifiexp16(i,column)-ave(l,j))^2+intern;
count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;
end
%for the 11 transmitter
row=34;
l=11;
for j=1:8
count=0;
intern=0;
column=j+1;
for i=1:row
if wifiexp17(i,column)~=500
intern=(wifiexp17(i,column)-ave(l,j))^2+intern;
count=count+1;
end
end
deviation(l,j)=(intern/count)^0.5;
end
%for the 12 transmitter
row=39;
l=12;
for j=1:8
    count=0;
    intern=0;
    column=j+1;
    for i=1:row
        if wifiexp18(i,column)~=500
            intern=(wifiexp18(i,column)-ave(l,j))^2+intern;
            count=count+1;
        end
    end
    deviation(l,j)=(intern/count)^0.5;
end

m=0;
temp=0;
for i=1:8
    for j=1:12
        if ~isnan(deviation(j,i))
            temp=temp+deviation(j,i);
            m=m+1;
        end
    end
end
result=temp/m