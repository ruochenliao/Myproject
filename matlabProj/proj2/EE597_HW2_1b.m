mu = 0;
sigma1 = 3;
sigma2 = 5;
sigma3 = 7;

eta = 3;
pout = 10;

x1 = 0:0.1:10000;
y1 = normcdf(pout+10*eta*log10(x1)-100,mu,sigma1);

x2 = 0:0.1:10000;
y2 = normcdf(pout+10*eta*log10(x2)-100,mu,sigma2);

x3 = 0:0.1:10000;
y3 = normcdf(pout+10*eta*log10(x3)-100,mu,sigma3);


plot(x1,y1,'r','lineWidth',2)
hold on
plot(x2,y2,'b','lineWidth',2)
hold on
plot(x3,y3,'k','lineWidth',2)


