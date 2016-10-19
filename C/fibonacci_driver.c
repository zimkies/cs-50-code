#include <stdio.h>
#include <sys/types.h>
#include <time.h>
#include <unistd.h>
#include <math.h>
#include "fibonacci.h"

void stopclock(int (*f)(int), int fib);

int main (int argc, char * argv[])
{
	int (*fib_functions[3])(int) = {fib_recursive, fib_iterative, fib_matrix};
	char *fib_function_names[3] = {"recursive", "iterative", "matrix"};
	int n,d;
	while (1)
	{
		printf("\nInput a number to begin:\n");
		printf("0: exit\n");
		printf("1: Run %s algorithm\n", fib_function_names[0]);
		printf("2: Run %s algorithm\n", fib_function_names[1]);
		printf("3: Run %s algorithm\n", fib_function_names[2]);
		printf("4: Run all algorithms\n");
		scanf("%d", &d);
		
		// exit if 0
		if (d <= 0 || d >= 5)
			break; 
		
		printf("Which Fibonacci number would you like?\n");
		scanf("%d", &n);
		
		// run individual
		if (d <= 3)
		{
			printf("%s\n", fib_function_names[d-1]);
			stopclock(fib_functions[d-1], n);
			continue;
		}
		
		// run all together
		int i;
		for (i = 0; i < 3; i++)
		{
			printf("%s\n", fib_function_names[i]);
			stopclock(fib_functions[i], n);
		}
	}
		
	return 0; 
}

void stopclock(int (*f)(int), int fib)
{
	time_t  t0, t1; /* time_t is defined on <time.h> and <sys/types.h> as long */
	clock_t c0, c1; /* clock_t is defined on <time.h> and <sys/types.h> as int */
	long count;
	double a, b, c;
	
	printf ("Starting clock\n");

	t0 = time(NULL);
	c0 = clock();
	printf("\tsolution:                %d\n", f(fib));

	t1 = time(NULL);
	c1 = clock();
	printf ("\telapsed wall clock time: %ld\n", (long) (t1 - t0));
	printf ("\telapsed CPU time:        %f\n", (float) (c1 - c0)/CLOCKS_PER_SEC);
}
