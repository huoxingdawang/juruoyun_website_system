#include <iostream>
#include <cstdio>
#include <cmath>
#include <algorithm>
#include<cstring>
#include<string>
#include<cctype>
#include<cstdlib>
#include<map>
#include<ctime>
#include <io.h>
#include <fstream>
#define debuglog(x) cerr<<"\tdebug:"<<#x<<endl
#define debug(x) cerr<<"\tdebug:"<<#x<<"="<<(x)<<endl
#define debugg(x,y) cerr<<"\tdebug;"<<(x)<<":"<<#y<<"="<<(y)<<endl
#define debugzu(x,a,b) 	cerr<<"\tdebug:"<<#x<<"=\n\t";for(int i=a;i<b;i++)cerr<<x[i]<<" ";fprintf(stderr,"\n");
#define debugerzu(x,a,b,c,d) 	cerr<<"\tdebug:"<<#x<<"=\n\t";for(int i=a;i<b;i++,fprintf(stderr,"\n\t"))for(int j=c;j<d;j++)cerr<<x[i][j]<<" ";fprintf(stderr,"\n");
#define R register
#define L long
#define LL long long
#define I inline
#define U unsigned
#define START clock_t __start=clock();
#define STOP fprintf(stderr,"\n\nUse Time:%fs\n",((double)(clock()-__start)/CLOCKS_PER_SEC));
using namespace std;

void listfiles(const char * dir,void(*callback)(char*),const char * type);
void parsecode(char *dir1);
char s[1024*1024];
char dir[200]={"D:\\mydocument\\science\\juruoyun\\jry_webserve_dev\\dev\\js\\general_js"};
char out_dir[200]={"D:\\mydocument\\science\\juruoyun\\jry_webserve_dev\\push_dev\\jry_wb\\jry_wb_js\\jry_wb_core_js.js.php"};
char cmd[200];
U LL yuan_total,xin_total;
int main()
{
//	cout << "Enter a directory: ";
//	cin.getline(dir, 200);
//	cout << "Enter a out directory: ";
//	cin.getline(out_dir, 200);
	printf("Welcome to juruoyun develop tool V1.0.\nHave a good day.\n>");
	for(;;)
	{
		cin.getline(cmd, 200);
		if(strcmp(cmd,"exit")==0)
		{
			printf("Bye. \n\n\n");
			break;
		}
		else if(strcmp(cmd,"?")==0||strcmp(cmd,"help")==0)
		{
			printf("exit							   :\t\texit.\n");
			printf("parsecode						  :\t\tparsecode.Can parse space enter /**/ tab,keep \' \".But without//.\n");
			printf("help							   :\t\thelp.\n");
			printf("?								  :\t\thelp.\n");
			printf("message							:\t\tget message.\n");
			printf(">");
		}
		else if(strcmp(cmd,"parsecode")==0)
		{
			yuan_total=xin_total=0;
			FILE *out=fopen(out_dir,"w");
			fprintf(out,"<?php $etag=%d;?>",clock());
			fclose(out);
			listfiles(dir,parsecode,"*");

			fstream f(out_dir);
			string line;
			getline(f,line);
			f.close();
			int pos=0;
			try
			{
				cout<<'\t'<<"<?php if(false){ ?><script><?php } ?>"<<endl;
				while ((pos=line.find("<?php if(false){ ?><script><?php } ?>"))!=-1)
					line.erase(pos,37),xin_total-=37;
				cout<<'\t'<<"<?php if(false){ ?></script><?php } ?>"<<endl;
				while ((pos=line.find("<?php if(false){ ?></script><?php } ?>"))!=-1)
					line.erase(pos,38),xin_total-=38;
			}
			catch (std::out_of_range & exc)
			{
				std::cerr<<exc.what()<<endl<<pos<<'\t'<<line.length()<<endl;
				return 0;
			}
			ofstream f2(out_dir);
			f2<<line;
			f2.close();			
			printf("OK old:%lld byte,now:%lld byte,lose:%lld byte,parse ratio:%lf%%\n>",yuan_total,xin_total,yuan_total-xin_total,(double)(yuan_total-xin_total)/yuan_total*100);
		}
		else if(strcmp(cmd,"exit")==0)
			return 0;
		else if(strcmp(cmd,"synctest0")==0)
		{
			system("FreeFileSync.exe D:\\mydocument\\Documents\\sync_set\\sync_with_test0.juruoyun.top.ffs_batch");
			printf("finish......\n>");
		}
		else if(strcmp(cmd,"syncdev")==0)
		{
			
			system("FreeFileSync.exe D:\\mydocument\\Documents\\sync_set\\sync_with_dev.juruoyun.top.ffs_batch");
			printf("finish......\n>");
		}
		else if(strcmp(cmd,"sync")==0)
		{
			
			system("FreeFileSync.exe D:\\mydocument\\Documents\\sync_set\\sync_with_test0.juruoyun.top.ffs_batch");
			system("FreeFileSync.exe D:\\mydocument\\Documents\\sync_set\\sync_with_dev.juruoyun.top.ffs_batch");
			printf("finish......\n>");
		}		
		else if(strcmp(cmd,""))
			printf(">");
		else
			printf("Use 'help' to get help.\n>");
	}
	return 0;
}
void parsecode(char *dir1)
{
	FILE *out=fopen(out_dir,"a");
	FILE *fp=fopen(dir1,"r");
	bool yasuo=false;
	bool lastkong=false;
	bool befor_yasuo=false;
	bool string_1=false;
	bool string_2=false;
	bool zhushi_add=false;
	bool zhushi_del=false;
	bool zhushi=false;
	bool zhushi2_add=false;
	bool zhushi2=false;
	char c;
	while(fscanf(fp,"%c",&c)!=EOF)
	{
		yuan_total++;
		//判断字符串
		if(c=='"')
			if(string_2)
				string_2=false;
			else
				if(!string_1)
					string_2=true;
		if(c=='\'')
			if(string_1)
				string_1=false;
			else
				if(!string_2)
					string_1=true;
		if(!(zhushi||zhushi2))
			if(string_1||string_2)
			{
				fprintf(out,"%c",c);
				xin_total++;
				continue;
			}		
		//判断注释/**/
		if(c=='/')
		{
			if(zhushi)
			{
				if(zhushi_del)
				{
					zhushi=false,zhushi_del=false,zhushi_add=false;
					continue;
				}
			}
			else
			{
				zhushi_add=true;
			}
		}
		else
		{
			if(zhushi_add&&c!='*')
			{
				fprintf(out,"/");
				xin_total++;
				zhushi_add=false;
			}	
		}
		if(c=='*')
		{
			if(zhushi_add)
				zhushi=true,zhushi_add=false,zhushi_del=false;
			else
				if(zhushi)
					zhushi_del=true;
		}
		else
		{
			if(zhushi_del&&c!='/')
			{
				fprintf(out,"*");
				xin_total++;
				zhushi_del=false;
			}
		}
		if(zhushi||zhushi_add||zhushi_del)
			continue;
		//处理换行
		if(c==' ')
		{
			if(lastkong==false)
			{
				fprintf(out," ");
				lastkong=true;
				continue;
			}
			else
			{
				lastkong=false;
				yasuo=true;
				continue;
			}	
		}
		else
			lastkong=false;
		if(c=='\n'||c=='\t')
		{
			yasuo=true;
			continue;
		}
		if(yasuo&&befor_yasuo)
		{
			if((c!='!'&&c!='@'&&c!='#'&&c!='$'&&c!='%'&&c!='&'&&c!='*'&&c!='('&&c!=')'&&c!='+'&&c!='-'&&c!='='&&c!='{'&&c!='}'&&c!='['&&c!=']'&&c!=';'&&c!=':'&&c!='|'&&c!='\\'&&c!='<'&&c!='>'&&c!='/'&&c!=','&&c!='.'&&c!='?'))
			{
				fprintf(out," ");
				xin_total++;
			}
		}
		yasuo=false;
		befor_yasuo=(c!='!'&&c!='@'&&c!='#'&&c!='$'&&c!='%'&&c!='&'&&c!='*'&&c!='('&&c!=')'&&c!='+'&&c!='-'&&c!='='&&c!='{'&&c!='}'&&c!='['&&c!=']'&&c!=';'&&c!=':'&&c!='|'&&c!='\\'&&c!='<'&&c!='>'&&c!='/'&&c!=','&&c!='.'&&c!='?');
		fprintf(out,"%c",c);
		xin_total++;
	}
	fclose(fp);
	fclose(out);
}
void listfiles(const char * dir,void(*callback)(char*),const char * type)
{
	char dirNew[200];
	strcpy(dirNew, dir);
	strcat(dirNew, "\\*.");
	strcat(dirNew, type);
	intptr_t handle;
	_finddata_t findData;
	handle = _findfirst(dirNew, &findData);
	if (handle == -1)
		return;
	do
	{
		if (findData.attrib & _A_SUBDIR)
		{
			if (strcmp(findData.name, ".") == 0 || strcmp(findData.name, "..") == 0)
				continue;
			cout <<"\t"<< findData.name << "\t<dir>\n";
			strcpy(dirNew, dir);
			strcat(dirNew, "\\");
			strcat(dirNew, findData.name);
			listfiles(dirNew,callback,type);
		}
		else
		{
			cout<<"\tparsing:"<<findData.name<<"...\n";
			char dir1[200];
			strcpy(dir1, dir);
			strcat(dir1, "\\");
			strcat(dir1,findData.name);
			(*callback)(dir1);
		}
	} while (_findnext(handle, &findData) == 0);
	_findclose(handle);
}
