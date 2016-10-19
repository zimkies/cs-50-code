// My program in python

x = float(raw_input("Give me a starting value"))
y = int(raw_input("How many iterations?"))

for iter in range(y):
	if (x <= .5):
		x = 2*x
	elif (x > .5):
		x = 2 * (1-x)
	else:
		print "error"
		break
	print x
	

// output
Give me a starting valueHow many iterations?0.6
0.8
0.4
0.8
0.4
0.8
0.4
0.8
0.4
0.8
0.4
0.8
0.4
0.8
0.4
0.799999999999
0.400000000001
0.800000000003
0.399999999994
0.799999999988
0.400000000023
0.800000000047
0.399999999907
0.799999999814
0.400000000373
0.800000000745
0.39999999851
0.79999999702
0.40000000596
0.800000011921
0.399999976158
0.799999952316
0.400000095367
0.800000190735
0.39999961853
0.799999237061
0.400001525879
0.800003051758
0.399993896484
0.799987792969
0.400024414062
0.800048828125
0.39990234375
0.7998046875
0.400390625
0.80078125
0.3984375
0.796875
0.40625
0.8125
0.375
0.75
0.5
1.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0
0.0

By the previous parts of this question, we know that x is not eventually fixed, nor is it periodic, so it must be eventually periodic. This program did cause x = 0.3 to become eventually periodc between .4 and .8. however, due to the inherent inaccuracies of storing floating point numbers, and the fact that this cycle of period 2 is highly unstable, the series suddenly diverges to 0. This just illustrates the dangers of Newton's method.