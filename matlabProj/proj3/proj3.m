clc;
clear all;
x = [1 2 3 4 5 6 7];
%combntns()
nchoosek([1 2 3], 2)
g= importdata('gains.csv');
g_length = length(g);
P = importdata('params.csv');
n = P(1); N = P(2); sita = P(3); C = P(4);
G= zeros(g_length,g_length);
for i = 1:g_length
    for j = 1:g_length
        if( i~=j )
           G(i,j) = -sita* g(j,i);
        else
           G(i,j) = g(i,i);
        end
    end
end
%G=G
Input = [6 15 30 33 57 64];
X = Group(G,Input,sita,N,g_length);
output = X'
dlmwrite('pow.csv', output, 'precision', 100);
%csvwrite('pow.csv',output);
%Gains = csvread('pow.csv');
%Gains *
power = getPower(X);


