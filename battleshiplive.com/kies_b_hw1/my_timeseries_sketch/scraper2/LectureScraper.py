import re, util

# this creates a tab-delimited file
delim = "\t"

# base url
base = "http://www.newegg.com/Product/ProductList.aspx?Submit=ENE&Category=334&N=2003340000&SpeTabStoreType=0"

# counter to keep track of which page we're on
ctr = 0

# keep going until we find a page that doesn't have any products
while(True):
    url = base + "&page=" + str(ctr)
    ctr += 1

    # use cs171-util to get a soup object that represents a webpage
    soup = util.mysoupopen(url)

    # infoCols holds the html code that holds the names of the products
    infoCols = soup.findAll("td", {"class":"midCol"})

    # priceCols holds the html code that holds the prices of the products
    priceCols = soup.findAll("ul", {"class":"priceCol"})

    # if we didn't find any products, break out of the loop and finish the
    # program!
    if(len(infoCols) == 0):
        break;

    # for each product...
    for i in range(len(priceCols)):
        # infoCols[i] is a soup object
        # however, we'd like it to be a string, so we can use reg exps to
        # search through it! mc will be that string
        mc = str(infoCols[i])
   
        # find the name of the product
        m = re.search("h3>.*?>(.*?)<", mc);
        if(m != None):
            print m.groups()[0].strip() + delim,
        else:
            print "ERROR None found!" + delim,

        # see above comment at beginning of for loop
        pc = str(priceCols[i])

        #let's find the full price
        m = re.search("Your Price:(.*?)<", pc);
        if(m != None):
            print m.groups()[0].strip() + delim,
        else:
            print "Price not available" + delim,
    
        # let's find out how much shipping is...
        m = re.search("Free 3 Business.*?", pc)
        if(m != None):
            print "$0.00"
        else:
            m = re.search("3 Business Day Shipping(.*?)<", pc);
            #strip gets rid of extra spaces on the sides
            if(m != None):
                print m.groups()[0].strip()
            else:
                print "n/a"
