%ZF
H = rand(2);
H_ = (det(H)*inv(H));
Wzf = ((H_ * H)^-1)* H_

%MMSE
Wmmse = ((H_ * H + 1/10* eye(2) )^-1)* H_
p = 1/x;