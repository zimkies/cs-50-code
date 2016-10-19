import pickle
import smtplib
import sys
import glob
import os

# Here are the email package modules we'll need
from email.mime.image import MIMEImage
from email.mime.multipart import MIMEMultipart

ME = "ben@neena.com"
NEENA = "zimkies@gmail.com"
FOLDER = "./images/"


def schedule():
    os.system("at 3 am | ")
    
def getPicture():
    infiles =  glob.glob(FOLDER + "*.png")
    print infiles
    if (len(infiles) == 0):
        return None
    else:
        return infiles[0]
    
def getPictureData():
   pass

def savePictureData(folder):
    pass
    
def send(file):
    filepath = FOLDER + file
    filename, ext = os.path.splitext(file)
    # Create the container (outer) email message.
    msg = MIMEMultipart()
    msg['Subject'] = 'Beneena Picture of the day'
    msg['From'] = ME
    msg['To'] = NEENA
    
    # Open the files in binary mode.  Let the MIMEImage class automatically
    # guess the specific image type.
    fp = open(file, 'rb')
    img = MIMEImage(fp.read())
    fp.close()
    msg.attach(img)
    
    # Send the email via our own SMTP server.
    print "sending..."
    s = smtplib.SMTP()
    s.sendmail(ME, NEENA, msg.as_string())
    s.quit()
    print "sent"
    
    # delete message:
    os.system("rm " + filepath)

def sendDailyPicture():
    
    print "getting picture..."
    file = getPicture()
    if (file == None):
        return None
    else:
        "got picture..."
        send(file)
        
if __name__ == "__main__":
    # Usage
    if (len(sys.argv) != 3):
        sys.exit("Usage: round1 nputfile option")
    
    # Which option?
    sendDailyPicture()
