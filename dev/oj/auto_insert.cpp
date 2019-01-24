#include <iostream>
#include <cstdio>
#include <cmath>
#include <algorithm>
#include<cstring>
#include<string>
#include<cctype>
#include<cstdlib>
#include<map>
#define debug(x) cerr<<"\tdebug:"<<#x<<"="<<x<<endl
#define debugg(x,y) cerr<<"\tdebug;"<<x<<":"<<#y<<"="<<y<<endl
#define debugzu(x,a,b) 	cerr<<"\tdebug:"<<#x<<"=\n\t";for(int i=a;i<b;i++)cerr<<x[i]<<" ";fprintf(stderr,"\n");
#define debugerzu(x,a,b,c,d) 	cerr<<"\tdebug:"<<#x<<"=\n\t";for(int i=a;i<b;i++,fprintf(stderr,"\n\t"))for(int j=c;j<d;j++)cerr<<x[i][j]<<" ";fprintf(stderr,"\n");
using namespace std;
char chinese[1000],english[1000],come[1000];
int n;



int main()
{
 	freopen("out.sql","w",stdout);
	fprintf(stderr,"请输入数量\n");
	scanf("%d",&n);
	fprintf(stderr,"请输入来源\n");
	scanf("%s",&come);
	printf("INSERT INTO `bdm264887590_db`.`oj_questionlist` (`ojquestionaddid`, `questiontype`, `question`, `ans`, `source`, `lasttime`) VALUES\n");
	for(int i=0;i<n;i++)
	{
		scanf("%s%s",chinese,english);
		printf("('1', '2', '%s', '%s', '%s', '2018-07-14 18:29:02')",chinese,english,come);
		if(i!=n-1)
			putchar(',');
		putchar('\n');
	}
	return 0;
}
