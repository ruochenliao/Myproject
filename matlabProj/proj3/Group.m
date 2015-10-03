function [ Pt ] = Group( G, Input, sita, N, g_length )
    n= length(Input);
    Input_length = n;
    G_ = zeros(Input_length,Input_length);
    I = ones(Input_length, 1);
    a = 0; b = 0;
    for i = 1:g_length
        if( ismember(i,Input)>0 )
            b = b+1;
            a = 0;
        end
        for j = 1:g_length
            if( ismember(i,Input)>0&&ismember(j,Input)>0 )
                a = a+1;
                G_(b,a) = G(i,j);
                %G(i,j) = -sita* g(j,i);
            else
                %G(i,j) = g(i,i);
            end
        end
    end
    Pt = (G_)\(sita*N*I)
    Gains = csvread('pow.csv');
    test = G_ * Gains'
    %test = G_*Pt
    
end

