# problems - need to learn how to search for things that DON'T have certain patterns

import re, util

# this creates a tab-delimited file
delim = "\t"

# base url
base = "http://www.elyrics.net"

# find all in flames songs by going to the following url
url = base + "/song/i/in-flames-lyrics.html"

# use cs171-util to get a soup object that represents a webpage
soup = util.mysoupopen(url)

# songs holds the html code that holds the names of the songs
songs = soup.findAll("table", {"class":"songs"})

# If we find some songs, then find the urls to each song's lyrics
if(len(songs) != 0):

# songs is a soup object
# however, we'd like it to be a string, so we can use reg exps to
# search through it! songs_str will be that string
	songs_str = str(songs[0])
	
	# find links for every song listed.
	m = re.findall('<a href="(.*?)">.*?</a', songs_str)

# Now go to each url and get the lyrics
if(m != None):
	lines = []
	for i in range(len(m)):
		
		# make the complete url
		song_url = base + m[i]
		
		soup = util.mysoupopen(song_url)
		
		if (soup):
			lyrics = soup.findAll("div", {"class":"ly"})
			
		if(len(lyrics) != 0):
		
			#convert lyrics to string
			lyrics_str = str(lyrics[0])
			lyrics_str = lyrics_str.replace("<br />\n", " ")
			lyrics_str = lyrics_str.replace('"', "")
			
	
			# find links for every song listed.
			temp = re.findall('verdana>(.*?)</pre', lyrics_str)
			
			if (not temp):
				continue;
			print temp[0]
