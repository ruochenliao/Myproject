receiverX
receiverY
transmitterX
transmitterY
%{
for i = [24,18,17,23,28]
   disp(i)
end
%}
%{
a =[1]
b = [2]
a = [a,2]
%}

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

