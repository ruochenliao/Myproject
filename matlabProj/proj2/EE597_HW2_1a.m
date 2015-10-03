mu = 0;
sigma = 3;
eta1 = 2.5;
eta2 = 2.75;
eta3 = 3;

x1 = 0:0.1:10000;
y1 = normcdf(-90+10*eta1*log10(x1),mu,sigma);

x2 = 0:0.1:10000;
y2 = normcdf(-90+10*eta2*log10(x2),mu,sigma);

x3 = 0:0.1:10000;
y3 = normcdf(-90+10*eta3*log10(x3),mu,sigma);

plot(x1,y1,'r','lineWidth',2)
hold on
plot(x2,y2,'b','lineWidth',2)
hold on
plot(x3,y3,'k','lineWidth',2)
