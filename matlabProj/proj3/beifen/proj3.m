clc;
clear all;

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
G=G
Input = [1 2 3];
[m,n] = size(Input);
Input_length = n;
G_ = zeros(Input_length,Input_length);
I = ones(1,Input_length);
I = I';
a = 0; b = 0;
for i = 1:g_length
    if( ismember(i,Input)>0 )
        b = b+1;
        a = 0;
    end
    for j = 1:g_length
        if( ismember(i,Input)>0 && ismember(j,Input)>0 )
           a = a+1;
           G_(b,a) = G(i,j);
        end
    end
end
X = (G_^-1)*G_
(G_^-1)*sita*N*I
