// gcc -fno-stack-protector
// set /proc/sys/kernel/randomize_va_space to 0
#include<stdio.h> 
#include<stdlib.h>
#include<string.h> 
int main(int argc, char *argv[]) 
{ 
  char buf[100]; 
  strcpy(buf,argv[1]); 
  printf("Input was: %s\n",buf); 
  return 0; 
}
