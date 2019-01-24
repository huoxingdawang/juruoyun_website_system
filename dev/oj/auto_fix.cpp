#include <cstdio>
using namespace std;
int main()
{
	freopen("out.sql","w",stdout);
	for(int i=1;i<=644;i++)
		printf("update oj_questionlist set oj_questionlist.right = (select COUNT(1) from oj_logs where oj_logs.result='right' AND oj_logs.ojquestionid=%d) where oj_questionlist.ojquestionid=%d;\nupdate oj_questionlist set oj_questionlist.submit = (select COUNT(1) from oj_logs where oj_logs.ojquestionid=%d)where oj_questionlist.ojquestionid=%d;\n",i,i,i,i);
}