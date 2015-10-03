#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <sys/time.h>
#include <pthread.h>
#include <unistd.h>
#include <signal.h>
#include <errno.h>
#include <math.h>
#include "my402list.h"
#include "cs402.h"
#include "warmup2.h"


pthread_t thr[3];
int g_flag = 0;
int g_num = 0;
int g_drop = 0;
int g_token = 0;
int g_tokennum = 0;
int g_tokendrop = 0;
double total_inter_time = 0.0;
int g_served = 0;
double total_service_time = 0.0;
double total_time = 0.0;
double total_time_square = 0.0;
double total_q1 = 0.0;
double total_q2 = 0.0;
double total_s = 0.0;
My402List Q1, Q2;
FILE *fp;
char buf[1026];

struct settings settings;

pthread_mutex_t m = PTHREAD_MUTEX_INITIALIZER;
pthread_cond_t con = PTHREAD_COND_INITIALIZER;

struct timeval starttime, endtime;
struct sigaction act, sig;
sigset_t new;


/* Settings initialization */
void InitSettings() {
  
    settings.lambda = 0.5;
    settings.mu = 0.35;
    settings.r = 1.5;
    settings.B = 10;
    settings.P = 3;
    settings.n = 20;
    settings.mode = 0;
    settings.t = NULL;
}

/* Parse the command line options */
void ParseOptions(int argc, char **argv) {
    int i;
    int last = argc - 1;
    
    for (i = 1; i < argc; i++) {
        if (!strcmp(argv[i], "-lambda") && (i != last)) {
            settings.lambda = atof(argv[++i]);
        }
        else if (!strcmp(argv[i], "-mu") && (i != last)) {
            settings.mu = atof(argv[++i]);
        }
        else if (!strcmp(argv[i], "-r") && (i != last)) {
            settings.r = atof(argv[++i]);
        }
        else if (!strcmp(argv[i], "-B") && (i != last)) {
            settings.B = atoi(argv[++i]);
        }
        else if (!strcmp(argv[i], "-P") && (i != last)) {
            settings.P = atof(argv[++i]);
        }
        else if (!strcmp(argv[i], "-n") && (i != last)) {
            settings.n = atoi(argv[++i]);
        }
        else if (!strcmp(argv[i], "-t") && (i != last)) {
            settings.t = strdup(argv[++i]);
            settings.mode = 1;
        }
        else {
            fprintf(stderr, "!!!***Invalid option or missing option argument***!!!\n");
            Usage();
            exit(1);
        }
    }
}

            
void Usage() {
    printf("usage: warmup2 [-lambda lambda] [-mu mu] [-r r] [-B B] [-P P] [-n num] [-t tsfile]\n\n"); 
    printf(
" -lambda <lambda>  Packets arriving rate per second  (positive real number, default 0.5)\n"
" -mu <mu>          Packets leaving rate per second (positive real number, default 0.35)\n"
" -r <r>            Tokens arriving rate per second (positive real number, default 1.5)\n"
" -B <B>            Bucket depth (positive integer, maximum 2147483647, default 10)\n"
" -P <P>            Number of tokens needed (positive integer, maximum 2147483647, default 3)\n"
" -n <num>          Total number of packets (positive integer, maximum 2147483647, default 20)\n"
" -t <tsfile>       tsfile is a trace specification file, ignoring the -lambda, -mu, -P, and -num commandline options\n"
    );
}

/* Check the if B, P, and num <= 2147483647. lambda, mu, and r > 0; */      
void CheckSettings() {
    if (settings.mode) {
        if ((fp = fopen(settings.t, "r")) == NULL)
        {
            fprintf(stderr, "Can't not open file '%s'\n", settings.t);
            exit(1);
        }
        
        fgets(buf, 1026, fp); 
        settings.n = atoi(buf);
        
    }

    if (settings.lambda <= 0||settings.mu <= 0||settings.r <= 0) {
        fprintf(stderr, "Invalid input number! Make sure they are positive!\n");
        exit(1);
    }
    if (settings.B <= 0||settings.B > 2147483647||settings.P <= 0||settings.P > 2147483647||settings.n <= 0||settings.n > 2147483647) {
        fprintf(stderr, "Invalid input number! Make sure they are positive and less than 2147483647\n");
        exit(1);
    }
    if (settings.r < 0.1) {
        settings.r = 0.1;
    }
/*
    if (settings.lambda < 0.1) {
        settings.lambda = 0.1;
    }
    if (settings.mu < 0.1) {
        settings.mu = 0.1;
    }
*/
}


void Display() {
    printf("Emulation Parameters:\n");
    if (settings.mode) {
        printf("    r = %g\n", settings.r);
        printf("    B = %d\n", settings.B);
        printf("    tfile = %s\n", settings.t);
        printf("    number to arrive = %d\n", settings.n);
    }
    else {
        printf("    lambda = %g\n", settings.lambda);
        printf("    mu = %g\n", settings.mu);
        printf("    r = %g\n", settings.r);
        printf("    B = %d\n", settings.B);
        printf("    P = %d\n", settings.P);
        printf("    number to arrive = %d\n", settings.n);
        if (settings.lambda < 0.1) {
            settings.lambda = 0.1;
        }
        if (settings.mu < 0.1) {
            settings.mu = 0.1;
        }
    }
}

Packet *CreatePacket() {
    Packet *packet;
    packet = (Packet *)malloc((sizeof(Packet)));
    if (packet == NULL) {
        printf("Out of space!\n");
        exit(1);
    }
    else {
        if (settings.mode) {
            //printf("Creat Packet according to tfile!\n");
            fgets(buf, 1026, fp);
            ParseLine(buf, packet);
        }
        else {
            packet->P = settings.P;
            packet->interval_time = 1000.0/settings.lambda;
            packet->service_time = 1000.0/settings.mu;
        }
        return packet; 
    } 
       // if(My402ListAppend(list, (void*)entry))
}

void ParseLine(char *line, Packet *packet) {
    char *tab, *p[3];
    p[0] = tab = line;
    
    int i = 0;
    while ((tab = strchr(line, '\t')) != NULL)
    {
        *tab = ' ';
    }
    while ((tab = strchr(p[i], ' ')) != NULL) {
        *tab++ = '\0';
        while (*tab == ' ') {
            tab++;
        }
        p[++i] = tab;

    }

    tab = strchr(p[2], '\n');
    *tab = '\0';

    packet->interval_time = atoi(p[0]);
    packet->P = atoi(p[1]);
    packet->service_time = atoi(p[2]);
}


double DiffTime(struct timeval start, struct timeval end) {
    return ((end.tv_sec-start.tv_sec)*1000.0 + (end.tv_usec-start.tv_usec)/1000.0);
}

void EnQ1(Packet *packet) {
    if (My402ListAppend(&Q1, (void*)packet)) {
        struct timeval enq1;
        gettimeofday(&enq1, NULL);
        packet->enter_q1_time = DiffTime(starttime, enq1);
        printf("%012.3fms: p%d enters Q1\n", packet->enter_q1_time, packet->num);        
    }
    else {
        fprintf(stderr, "EnQ1 FAIL!!!\n");
        exit(1);
    }
}

void EnQ2() {
    struct timeval deq1;
    Packet *packet = (Packet *)(My402ListFirst(&Q1)->obj);
    g_token -= packet->P;
    My402ListUnlink(&Q1, My402ListFirst(&Q1));
    gettimeofday(&deq1, NULL);
    packet->leave_q1_time = DiffTime(starttime,deq1);
    printf("%012.3fms: p%d leaves Q1, time in Q1 = %.3fms, token bucket now has %d tokens\n", packet->leave_q1_time, packet->num, packet->leave_q1_time-packet->enter_q1_time, g_token);
    
    if (My402ListAppend(&Q2, (void*)packet)) {
        struct timeval enq2;
        gettimeofday(&enq2, NULL);
        packet->enter_q2_time = DiffTime(starttime, enq2);
        printf("%012.3fms: p%d enters Q2\n", packet->enter_q2_time, packet->num);        
        if (My402ListLength(&Q2) == 1) {
            pthread_cond_broadcast(&con);
        }
    }
    else {
        fprintf(stderr, "EnQ2 FAIL!!!\n");
        exit(1);
    }
}


void Statistics() {
    gettimeofday(&endtime, NULL);
    double total = DiffTime(starttime, endtime);
    double aver_inter = total_inter_time/g_num/1000.0; 
    double aver_service = total_service_time/g_served/1000.0;
    double aver_time = total_time/g_served/1000.0;
    double aver_time_square = total_time_square/g_served/1000000.0;
    double aver_q1 = total_q1/total;
    double aver_q2 = total_q2/total;
    double aver_s = total_service_time/total;
    double deviation;
    if ((aver_time_square - aver_time * aver_time) < 0.000000001) {
        deviation = 0.0; 
    }
    else {
        deviation = sqrt(aver_time_square - aver_time * aver_time);
    }

    printf("\nStatistics:\n");
    if (g_num == 0) {
        printf("    average packet inter-arrival time = N/A (no packet arrived at this facility)\n");
    }
    else {
        printf("    average packet inter-arrival time = %.6gs\n", aver_inter);
    }

    if (g_served == 0) {
        printf("    average packet service time = N/A (no packet arrived at server)\n");
    }
    else {
        printf("    average packet service time = %.6gs\n\n", aver_service);
    }
  
    printf("    average number of packets in Q1 = %.6g\n", aver_q1);
    printf("    average number of packets in Q2 = %.6g\n", aver_q2);
    printf("    average number of packets at S = %.6g\n", aver_s);
    if (g_served == 0) {
        printf("    average time a package spent in system = N/A (no packet arrived at server)\n");
        printf("    standard deviation for time spent in system = N/A (no packet arrived at server)\n");
    }
    else {
        printf("    average time a package spent in system = %.6gs\n", aver_time);
        printf("    standard deviation for time spent in system = %.6g\n\n", deviation);
    }

    if (g_tokennum == 0) {
        printf("    token drop probability = N/A (no token arrived at this facility)\n");
    }
    else {
        printf("    token drop probability =  %.6g\n", (double)g_tokendrop/g_tokennum);
    }

    if (g_num == 0) {
        printf("    packet drop probability = N/A (no packet arrived)\n");
    }
    else {
        printf("    packet drop probability = %.6g\n\n", (double)g_drop/g_num);
    }
}


void *Arrive(void *arg) {
	sig.sa_handler = Handler1;
//	sigemptyset(&act.sa_mask);
//	act.sa_flags=0;
	sigaction(SIGUSR1, &sig, NULL);

        double sleep_time;
        struct timeval lastarrival, arrival, end;
        lastarrival = starttime;
        end = starttime;
        while(g_num < settings.n) {
            //usleep(sleep_time); 
            Packet *packet = CreatePacket();
            if (packet->interval_time < DiffTime(lastarrival, end)) {
                sleep_time = 0;
            }
            else {
                sleep_time = 1000*(packet->interval_time - DiffTime(lastarrival, end)); 
            }
            
            usleep(sleep_time);

            pthread_mutex_lock(&m);
            
            gettimeofday(&arrival, NULL);
            packet->arrival_time = DiffTime(starttime, arrival);
            packet->interarrival_time = DiffTime(lastarrival,arrival); 
            lastarrival = arrival; 

            packet->num = ++g_num;
            if (packet->P <= settings.B) {
                printf("%012.3fms: p%d arrives, needs %d tokens, inter-arrival time = %.3fms\n", packet->arrival_time, packet->num, packet->P, packet->interarrival_time);
                EnQ1(packet);
                if (!My402ListEmpty(&Q1)) {
                    if(g_token >= ((Packet *)(My402ListFirst(&Q1)->obj))->P) {
                        EnQ2();
                    }
                }
            } 
            else {
                printf("%012.3fms: p%d arrives, needs %d tokens, dropped\n", packet->arrival_time, packet->num, packet->P);
                g_drop++;
                if (g_drop == settings.n) {
                    pthread_cond_broadcast(&con);
                } 
            }
            
            total_inter_time += packet->interarrival_time;
            pthread_mutex_unlock(&m); 
            
            //total_inter_time += packet->interarrival_time;
            gettimeofday(&end, NULL); 
        }
    //pthread_cond_broadcast(&con);
    while (pthread_kill(thr[2], 0) != ESRCH) {
        usleep(100000);
    }
    return 0;
}


void *Token(void *arg) {
	act.sa_handler = Handler;
//	sigemptyset(&act.sa_mask);
//	act.sa_flags=0;
	sigaction(SIGINT, &act, NULL);
	pthread_sigmask(SIG_UNBLOCK, &new, NULL);

    double sleep_time;
    double token_interval_time = 1000.0/settings.r;
    struct timeval lastarrival, arrival, end;
    lastarrival = starttime;
    end = starttime;   
    while (1) {
        if (g_num == settings.n && My402ListEmpty(&Q1)) {
            break;
        }

        if (token_interval_time < DiffTime(lastarrival, end)) {
            sleep_time = 0;
        }
        else {
            sleep_time = 1000*(token_interval_time - DiffTime(lastarrival, end)); 
        }
          
        usleep(sleep_time);
        pthread_mutex_lock(&m);
            
        if (g_num == settings.n && My402ListEmpty(&Q1)) {
            pthread_mutex_unlock(&m);
            break;
        }
        gettimeofday(&arrival, NULL);
        lastarrival = arrival; 
        g_tokennum++;

        if (g_token < settings.B) {
            g_token++;
            printf("%012.3fms: token t%d arrives, token bucket now has %d tokens\n", DiffTime(starttime, arrival), g_tokennum, g_token);
        }
        else {
            printf("%012.3fms: token t%d arrives, dropped\n", DiffTime(starttime, arrival), g_tokennum); 
            g_tokendrop++;
        }
        
        if (!My402ListEmpty(&Q1)) {
            if(g_token >= ((Packet *)(My402ListFirst(&Q1)->obj))->P) {
                EnQ2();
            }
        }
        pthread_mutex_unlock(&m); 
        gettimeofday(&end, NULL); 
    }
    while (pthread_kill(thr[2], 0) != ESRCH) {
        usleep(100000);
    }
    return 0;
}

void *Server(void *arg) {
    struct timeval deq2, deserver; 
    while(1) {
        pthread_mutex_lock(&m);
        if (My402ListEmpty(&Q2)) {
            pthread_cond_wait(&con, &m);
        }
        
        if (My402ListEmpty(&Q2)) { 
            pthread_mutex_unlock(&m);
            break;
        }
        Packet *packet = (Packet *)(My402ListFirst(&Q2)->obj);
        My402ListUnlink(&Q2, My402ListFirst(&Q2));
        gettimeofday(&deq2, NULL);
        packet->leave_q2_time = DiffTime(starttime, deq2);
        printf("%012.3fms: p%d begin service at S, time in Q2 = %.3fms\n", packet->leave_q2_time, packet->num, packet->leave_q2_time-packet->enter_q2_time);
        pthread_mutex_unlock(&m);

        usleep(1000.0*packet->service_time);
        gettimeofday(&deserver, NULL);
        packet->leave_server_time = DiffTime(starttime, deserver);
        printf("%012.3fms: p%d departs from S, service time = %.3fms, time in system = %.3fms\n", packet->leave_server_time, packet->num, DiffTime(deq2, deserver), packet->leave_server_time-packet->arrival_time);
        g_served++;
        total_q1 += (packet->leave_q1_time - packet->enter_q1_time);
        total_q2 += (packet->leave_q2_time - packet->enter_q2_time);
        total_service_time += DiffTime(deq2, deserver);
        total_time += (packet->leave_server_time-packet->arrival_time);
        total_time_square += (packet->leave_server_time-packet->arrival_time)*(packet->leave_server_time-packet->arrival_time);
        endtime = deserver;
        if (g_flag||(g_num == settings.n && My402ListEmpty(&Q1) && My402ListEmpty(&Q2))) {
            endtime = deserver;
            //Statistics();
                break;
        }
    } 

    Statistics();
    return 0;
}

void Handler1() {

    //int err = 
    pthread_mutex_trylock(&m);
/*
    if (err == EDEADLK) {
        printf("Locked by TOken!!!\n");
    }
    else if (err == EBUSY) {
        printf("Locked by Others!!!\n");
    }
    else {
        printf("Arrive exit!!!\na");
    }
*/
    pthread_mutex_unlock(&m);
    pthread_mutex_lock(&m);
    g_flag = 1;
    pthread_mutex_unlock(&m);
    pthread_exit(0);
}

void Handler() {
    //int err = 
    pthread_mutex_trylock(&m);
/*
    if (err == EDEADLK) {
        printf("Locked by TOken!!!\n");
    }
    else if (err == EBUSY) {
        printf("Locked by Others!!!\n");
    }
    else {
        printf("Ha!!!I get the lock!!!\na");
    }
*/
    pthread_mutex_unlock(&m);
    pthread_mutex_lock(&m);
    pthread_kill(thr[0],SIGUSR1);
    pthread_cond_broadcast(&con);
    pthread_mutex_unlock(&m); 
    pthread_exit(0);
}

int main(int argc, char **argv) {
    InitSettings();    
    ParseOptions(argc, argv);
    CheckSettings();
    Display(); 
    int error, i;


    sigemptyset(&new);
    sigaddset(&new, SIGINT);    
    pthread_sigmask(SIG_BLOCK, &new, NULL);
    
    gettimeofday(&starttime, NULL);
    printf("%012.3fms: emulation begins\n",DiffTime(starttime,starttime));
    (void)My402ListInit(&Q1);
    (void)My402ListInit(&Q2);
    
    
    if ((error = pthread_create(&thr[0], 0, Arrive, (void *)1))) { 
        fprintf(stderr, "pthread_create: %s", strerror(error));
        exit(1);
    }
    if ((error = pthread_create(&thr[1], 0, Token, (void *)2))) { 
        fprintf(stderr, "pthread_create: %s", strerror(error));
        exit(1);
    }
    if ((error = pthread_create(&thr[2], 0, Server, (void *)3))) { 
        fprintf(stderr, "pthread_create: %s", strerror(error));
        exit(1);
    }
   
    for(i = 0; i < 3; i++) {
        pthread_join(thr[i], 0);
}
    return 0;
}

