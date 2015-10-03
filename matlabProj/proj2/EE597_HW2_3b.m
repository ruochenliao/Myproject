v = [0 5 10 15 20 25];
color = ['c','k','b','y','r','g'];
figure;
for j = 1:6
    eval(['state = state' num2str(v(j)) ';']);
    count01 = 0;
    count10 = 0;
    for i = 1:MK-1
        if state(i) == 0
            if state(i+1) == 1
                count01 = count01 + 1;
            else
            end;
        else
        end;
        if state(i) == 1
            if state(i+1) == 0
                count10 = count10 + 1;
            else
            end;
        else
        end;
    end;
    transition0 = MK - 1 - (sum(state) - state(MK));
    transition1 = sum(state) - state(MK);
    %question 1 answer estimate p01 and p10
    p01 = count01/transition0
    p10 = count10/transition1
    p = [1-p01 p01; p10 1-p10]
    for k = 1:10
        pk = p^k;
        p01k(k) = pk(1,2);
    end;
    hold on
    plot(p01k,'color',color(j));
end;

legend('v=0','v=5','v=10','v=15','v=20','v=25')



