# problems - need to learn how to search for things that DON'T have certain patterns

import re, util

# this creates a tab-delimited file
delim = "\t"

# base url
base = "http://www.census.gov"

# go to the first page
url = base + "/econ/census02/data/al/AL000_71.HTM"

# use cs171-util to get a soup object that represents a webpage
soup = util.mysoupopen(url)

# states holds the url code that has all the states extentions
states = soup.findAll("select", {"name":"Location"})


# If we find some states, then find the urls to each song's lyrics
if(len(states) != 0):

	# states is a soup object
	# however, we'd like it to be a string, so we can use reg exps to
	# search through it! states_str will be that string
	states_str = str(states[0])
	states_str = states_str.replace("\n", "")
	# cut down our states_str to only the state links
	m = re.findall('<select name="Location">(.*?)</select>', states_str)
	
			
	# now find make a list of urls of each state
	m = re.findall('value="(.*?)">', m[0])
	
# now, go to each of the urls and get the relevant information
# make sure to skip the first two urls, which are irrelevant.
if (len(m) != 0):
	
	print "State\tarts\tsports\tparks"
	statenum = 0;
	
	for i in range(len(m)):
		
		#break for the first three, as well as states that spend outrageous amounts
		if (i < 3 or i == 5 + 2 or i == 10 + 2 or i == 33 + 2):
			continue		
		else:
			statenum += 1
			
			# otherwise, go to the link and get a soupobject
			state_url = base + m[i]
			state_soup = util.mysoupopen(state_url)
			state_info = state_soup.findAll("body")
			stateXX = re.findall('data/(.*?)/', m[i])
			stateXX[0].strip()
			stateXX = statenum
			
			# if we found the html, process it
			if (len(state_info) != 0):
				state_info_str = str(state_info[0])
				state_info_str = state_info_str.replace("\n", "")
				
				# get arts spending
				arts = re.findall('name="N7111">(.*?)</tr', state_info_str)
				if (len(arts) != 0):
					arts = re.findall('<td>(.*?)</td>', arts[0]) 
					arts = arts[2].strip()
					arts  = arts.replace(",", "")
				else:
					arts ="D"
				
				# get sports info
				sports = re.findall('name="N7112">(.*?)</tr>', state_info_str)
				if (len(sports) != 0):
					sports = re.findall('<td>(.*?)</td>', sports[0])
					sports = sports[2].strip()
					sports = sports.replace(",", "")
				else:
					sports = "D"
				
				# get parks info
				parks = re.findall('name="N7131">(.*?)</tr>', state_info_str)
				if (len(parks) != 0):
					parks = re.findall('<td>(.*?)</td>', parks[0])
					parks = parks[2].strip()
					parks = parks.replace(",", "")
				else:
					parks = "D"
				
				print str(stateXX) + "\t" + arts + "\t" + sports + "\t" + parks
