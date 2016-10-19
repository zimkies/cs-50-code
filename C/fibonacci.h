# include <stdio.h>
#define MAX_INT 65536
typedef int Matrix[4]; 
int fib_recursive(int n);
int fib_iterative(int n);
int fib_matrix(int n);
void matrix_mult(Matrix A, Matrix B);


// Recursively solves the n'th fibonnaci number.
// Returns 0 on invalid n.
int fib_recursive(int n)
{
	// base case and error check;
	if (n <= 0)
		return 0;
	else if (n == 1)
		return 1; 
	else 
		return (fib_recursive(n-1) + fib_recursive(n-2))%MAX_INT;
}

// Iteratively solves the n'th Fibonnaci nubmer.
// Returns 0 on invalid n. 
int fib_iterative(int n)
{
	int n2 = n;
	// base case and error check
	if (n <= 0)
		return 0; 
		
	// consecutive terms in the sequence. 
	int a = 0;
	int b = 1;
	int c = 1;
	while (n-- > 1)
	{
		c = (a + b)%MAX_INT;
		a = b;
		b = c;
		// helps to find integer overflow when mod2^16 is not used.
		//if (c < 0)
		//	printf("Integer overflow reached at n = %d\n", n2-n + 1);
	}
	return c;
}

// Uses Matrices to solve the n'th Fibonnaci nubmer.
// Returns 0 on invalid n. 
int fib_matrix(int n)
{
	int f0 = 0;
	int f1 = 1;
	
	// base case and error check;
	if (n <= 0)
		return f0;
	else if (n == 1)
		return f1; 
	
	// Calculate the matrix
	Matrix A = {0, 1, 1, 1};
	Matrix B = {0, 1, 1, 1};	
	while (n-- > 1)
		matrix_mult(A, B);
		
	return (B[0]*f0 + B[1]*f1)%MAX_INT;
}

// Replaces B with A*B for 2x2 matrices;
void matrix_mult(Matrix A, Matrix B)
{
	Matrix C = {0, 0, 0, 0};	
	C[0] = A[0]*B[0] + A[1]*B[2];
	C[1] = A[0]*B[1] + A[1]*B[3];
	C[2] = A[2]*B[0] + A[3]*B[2];
	C[3] = A[2]*B[1] + A[3]*B[3];
	
	// Copy B to C
	B[0] = C[0]%MAX_INT;
	B[1] = C[1]%MAX_INT;
	B[2] = C[2]%MAX_INT;
	B[3] = C[3]%MAX_INT;
}
