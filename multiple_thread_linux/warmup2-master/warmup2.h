#ifndef _WARMUP_H_
#define _WARMUP_H_

struct settings {
    double lambda; /* Packets arriving rate per second */
    double mu;     /* Packets leaving rate per second */
    double r;      /* Tokens arriving rate per second */
    int B;         /* Bucket depth */
    int P;         /* The number of tokens needed for a packet */
    int n;         /* Total number of packets */
    int mode;      /* Mode to show if -t is used */
    char *t;       /* File name */
};

typedef struct tagPacket {
    int num;        /* Packets No. */
    int P;         /* The number of tokens needed for this packet */
    double interval_time;     /* Interval time */ 
    double service_time;     /* Service time */
    double arrival_time;     /* Arrival time */
    double interarrival_time;    /* Inter-arrival time */
    double enter_q1_time;  /* Entering Q1 time */
    double leave_q1_time;  /* Leaving Q1 time */
    double enter_q2_time;  /* Entering Q2 time */
    double leave_q2_time;  /* Leaving Q2 time */
//    double enter_server_time;    /* Entering Server time */
    double leave_server_time;    /* Leaving Server time */
}Packet;

void InitSettings();
void ParseOptions(int argc, char **argv);
void Usage();
void CheckSettings();
void Display();
Packet *CreatePacket();
double DiffTime();
void EnQ1(Packet *);
void EnQ2();
void Statistics();
void ParseLine(char *, Packet *);
void Handler();
void Handler1();
void Handler2();

void *Arrive(void *);
void *Token(void *);
void *Server(void *);
void *Signal(void *); 
#endif /*_WARMUP_H_*/
