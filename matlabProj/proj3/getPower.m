function [ pt ] = getPower( X )
    pt = 0;
    x_length = length(X);
    for i = 1:x_length
        if( X(i,1)<0 )
            X(i,1)=100000;
        end
        pt = pt + X(i,1);
    end
end

