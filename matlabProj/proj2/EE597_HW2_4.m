clear all;
clc;
p01 = 0.1;
p11 = 0.9;
p = [1-p01 p01; 1-p11 p11];
prob=[];
for i = 1:11
    pk = p^i;
    prob(i) = pk(1,2);
end
prob

throuth=[];

for step = 1:11
    
    state = [];% states of slots
    slot= 1;% state[] index
    success = [];
    i=1;% success[] index
    state(slot) = 0;
    count = 1;
    kValue = step - 1;
    
    while(slot < 100000)
        slot=slot+1;
    
        if kValue ~= (step-1)
            kValue = kValue + 1;
            state(slot) = 2;
            continue;
        end
    
        if state(slot-1) == 0 || state(slot-1) == 2
            if rand(1)<=prob(step)
                state(slot) = 1;
                success(i) = count;
                i=i+1;
                count = 1;
            else
                state(slot) = 0;
                count=count+1;
                kValue = 0;
            end;
        else
            if rand(1) <= p11
                state(slot) = 1;
                success(i) = 1;
                i=i+1;
            else
                state(slot) = 0;
                count=count+1;
                kValue = 0;
            end;
        end
    end

    %state
    %success

    sum=0;
    for j = 1:length(success)
        sum=sum+success(j);
    end
    through(step) = sum/length(success);
end

x = 0:1:10;
plot(x,through)

