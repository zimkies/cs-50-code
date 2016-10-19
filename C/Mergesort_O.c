#include <stdio.h>
#include <math.h>

int solution(int n);
int recurrence(int n);
int base(int n);

int main(int c, char * argv[])
{
while (1)
	{
		int d,n;
		printf("\nInput a number to begin:\n");
		printf("0: exit\n");
		printf("1: Run algorithm\n");
		scanf("%d", &d);
		
		// exit if 0
		if (d <= 0 || d >= 5)
			break; 
		
		printf("Which number complexity would you like?\n");
		scanf("%d", &n);
		
		// run all together
		int i;
		for (i = 1; i <= n; i++)
		{	
			//solution(i);
			printf("%d: %d, %d, %d\n",i, base(i), solution(i), recurrence(i));
		}
	}
		
	return 0; 
}

int solution(int n)
{
	if (n == 1)
		return 0;
	if (n == 2)
		return 1;
	int bas = base(n);
	int j;
	int k = 1;
	for (j = 1; j<= bas + 1; j++)
		k *= 2;
	//printf("%d, %d, %d, %d\n",n, bas*n, 2^(bas + 1), n+1);
	return (bas*n - k + n + 1);
}

int base(int n)
{
	return (int)floor(log(n)/log(2));
}

int recurrence(int n)
{
	if (n == 1)
		return 0;
	else if (n == 2)
		return 1;
	else if (n%2)
		return recurrence((n/2)) + recurrence((n/2) +1) + n - 1;
	else 
		return 2*(recurrence(n/2)) + n -1;
}
