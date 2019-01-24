#include <iostream>
#include <cstdio>
#include <cmath>
#include <algorithm>
#include<cstring>
#include<string>
#include<cctype>
#include<cstdlib>
#include<map>
#include<sys/socket.h>
#include<arpa/inet.h>

#define debuglog(x) cerr<<"\tdebug:"<<#x<<endl
#define debug(x) cerr<<"\tdebug:"<<#x<<"="<<x<<endl
#define debugg(x,y) cerr<<"\tdebug;"<<x<<":"<<#y<<"="<<y<<endl
#define debugzu(x,a,b) 	cerr<<"\tdebug:"<<#x<<"=\n\t";for(int i=a;i<b;i++)cerr<<x[i]<<" ";fprintf(stderr,"\n");
#define debugerzu(x,a,b,c,d) 	cerr<<"\tdebug:"<<#x<<"=\n\t";for(int i=a;i<b;i++,fprintf(stderr,"\n\t"))for(int j=c;j<d;j++)cerr<<x[i][j]<<" ";fprintf(stderr,"\n");
#define R register
#define LL long long
#define I inline
using namespace std;
const char jry_wb_host_ip[]={"127.0.0.1"};
const int jry_wb_host_port=10000;
const char send_message[]={'{','"','l','o','g','i','d','"',':','1','}'};
char buf[2048];
int main()
{
 	printf("Hello word!\nDesigned by lijunyan for teencode\n\n");
	printf("I will start test machine server!\n");
	printf("It will be at:\nIP:%s\nPort:%d\n\n",jry_wb_host_ip,jry_wb_host_port);
	int sockfd;
	struct sockaddr_in jry_wb_host_socket;
	if(sockfd = socket(AF_INET, SOCK_STREAM, 0)==-1)
	{
		printf("Fail to creat socket!\n\n\n");
		return 0;
	}
	int mw_optval = 1;
	if(setsockopt(sockfd,SOL_SOCKET,SO_REUSEADDR,(char *)&mw_optval,sizeof(mw_optval))==-1)
	{
		printf("Fail to set reuse!\n\n\n");
		return 0;
	}
	jry_wb_host_socket.sin_family = AF_INET;
	jry_wb_host_socket.sin_port = (in_port_t)htons(jry_wb_host_port);
	jry_wb_host_socket.sin_addr.s_addr = inet_addr(jry_wb_host_ip); 
	bzero(&(jry_wb_host_socket.sin_zero),8);
	if(bind(sockfd, (struct sockaddr *)&jry_wb_host_socket, sizeof(struct sockaddr))==-1)
	{
		printf("Fail to bind port!\n\n\n");
		return 0;
	}
	printf("Waiting...\n");
	while(1)
	{
		struct sockaddr_storage client_socket;
		unsigned int client_socket_size=sizeof(client_socket);
		int connect_d=accept(sockfd,(struct sockaddr *)&client_socket,&client_socket_size);
		printf("New\n");
		printf("Sending:\t\t%s\n",send_message);
		if(send(connect_d,send_message,strlen(send_message),0)==-1)
		{
			printf("Fail to send!\n\n\n");
			return 0;			
		}
		if(recv(connect_d,buf,2048,0)==-1)
		{
			printf("Fail to read!\n\n\n");
			return 0;				
		}
		printf("Read:\t\t%s\n",buf);
	}
	
	
	printf("\n\n");
 	return 0;
}
