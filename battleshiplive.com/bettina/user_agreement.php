<?php

    // require common code
    require_once("includes/common.php"); 
	$default_pic = "images/auctions/default.jgp";
?>
	
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Bettina's Estate sales</title>
<link href="css/styles1.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
 <body>
	<div id="page">
		<div id="menu">
			<div id="loginbar">
				<div id="welcome">
				 Welcome, 
					<? if (isset($_SESSION["uid"]))
							echo $_SESSION["name"];
						else 
							echo "guest";
					?>
				</div>
				<ul>
					<? if (isset($_SESSION["uid"]))
					{
						echo '<li><a href="logout.php">logout</a></li>';
						echo '<li><a href="register.php">re-register</a></li>';
					}
					else
					{
						
						echo '<li><a href="login.php">login</a></li>';
						echo '<li><a href="register.php">register</a></li>';
					}
					?>
				</ul>
			</div>
			<div id="navcontainer" align="right">
				<ul>
					<li><a href="admin_index.php">Administration</a></li>
					<li><a href="contact.php">Contact</a></li>
					<li><a href="about.php">About</a></li>
					<li><a href="index.php">Auctions</a></li>
					<li><a href="index.php">Home</a></li>
				</ul>
			</div>			
		</div>

		<div id="content">
				<div id="content_title">
				User Agreement
				</div>

				<div id="privacy_message">
				<p>TERMS AND CONDITIONS OF USE</p>

<p>PLEASE READ THESE TERMS AND CONDITIONS OF USE CAREFULLY. THESE TERMS AND CONDITIONS MAY HAVE CHANGED SINCE YOUR LAST VISIT TO THIS WEBSITE. BY USING THIS WEBSITE, YOU INDICATE YOUR ACCEPTANCE OF THESE TERMS AND CONDITIONS. IF YOU DO NOT ACCEPT THESE TERMS AND CONDITIONS, THEN DO NOT USE THIS WEBSITE.</p>

<p>The Bettina Network, Inc.  maintains this website as a service to its customers, potential customers, and other interested parties.</p>

<p>Copyright and Trademark Information</p>

<p>&copy;1993-2009 The Bettina Network, Inc. All rights reserved.</p>

<p>This website, and the information which it contains, is the property of Bettina Network and its affiliates and licensors, and is protected from unauthorized copying and dissemination by United States copyright law, trademark law, international conventions and other intellectual property laws.</p>

<p>Use of BETTINA NETWORK Website and Its Content</p>

<p>BETTINA NETWORK may alter, suspend, or discontinue this website at any time for any reason, without notice or cost. The website may become unavailable due to maintenance or malfunction of computer equipment or other reasons.</p>

<p>No part of this website may be published, reproduced or transmitted in any form, by any means, electronic or mechanical, except that BETTINA NETWORK authorizes you to view, copy, download, and print BETTINA NETWORK documents (such as press releases, auction calendars, and lot descriptions) that are available on this website, subject to the following conditions:</p>

<p>1. The documents may be used solely for personal, noncommercial, informational purposes.</p>

<p>2. The documents may not be modified.</p>

<p>3. Copyright, trademark, and other proprietary notices may not be removed.</p>

<p>You will not use any electronic or automated means to collect, extract or compile data or content from the website, nor will you publish, reproduce, distribute, or transmit any collection or compilation of data or any content obtained from the website in any form or medium.</p>

<p>Accuracy of Content and Future Modifications to Website</p>

<p>The information on this website is believed to be complete and reliable; however, the information may contain technical inaccuracies or typographical errors.</p>

<p>BETTINA NETWORK reserves the right to make changes to document names and content, product specifications, or other information without obligation to notify any person of such changes.</p>

<p>Notice and Take Down Procedures; Copyright Agent</p>

<p>If you believe any materials accessible on or from this website infringe your copyright, you may request removal of those materials (or access thereto) from this website by contacting BETTINA NETWORK's copyright agent (identified below) and providing the following information:</p>
<p>1) Identification of the copyrighted work that you believe to be infringed. Please describe the work, and where possible include a copy or the location (e.g., URL) of an authorized version of the work.</p>

<p>2) Identification of the material that you believe to be infringing and its location. Please describe the material, and provide us with its URL or any other pertinent information that will allow us to locate the material.</p>

<p>3) Your name, address, telephone number and (if available) e-mail address.</p>

<p>4) A statement that you have a good faith belief that the complained of use of the materials is not authorized by the copyright owner, its agent, or the law.</p>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

<p>5) A statement that the information that you have supplied is accurate, and indicating that "under penalty of perjury," you are the copyright owner or are authorized to act on the copyright owner's behalf.</p>

<p>6) A signature or the electronic equivalent from the copyright holder or authorized representative.</p>

<p>Availability of Products and Services Mentioned</p>

<p>Information that BETTINA NETWORK publishes on this website may contain references or cross references to products or services that are not available or approved by the appropriate regulatory authorities in your country. Such references do not imply that BETTINA NETWORK intends to announce or make available such products or services to the general public, or in your country. Consult your BETTINA NETWORK account representative to determine which products and services may be available to you.</p>

<p>No Warranties</p>

<p>INFORMATION AND DOCUMENTS, INCLUDING PRODUCT SPECIFICATIONS, PROVIDED ON THIS WEBSITE ARE PROVIDED "AS IS." SPECIFICALLY, BUT WITHOUT LIMITATION, BETTINA NETWORK DOES NOT WARRANT THAT: (i) THE INFORMATION ON THIS WEBSITE IS CORRECT, ACCURATE, RELIABLE OR COMPLETE; (ii) THE FUNCTIONS CONTAINED ON THIS WEBSITE WILL BE UNINTERRUPTED OR ERROR-FREE; (iii) DEFECTS WILL BE CORRECTED, OR (iv) THIS WEBSITE OR THE SERVER(S) THAT MAKES IT AVAILABLE ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS.</p>

<p>Product descriptions and specifications are subject to change. BETTINA NETWORK periodically adds or updates the information and documents on this website without notice.</p>

<p>It is the user's responsibility to ascertain whether any information downloaded from this website is free of viruses, worms, Trojan horses, or other items of a potentially destructive nature.</p>

<p>Limitation of Liability</p>

<p>UNDER NO CIRCUMSTANCES SHALL BETTINA NETWORK BE LIABLE FOR ANY INCIDENTAL, SPECIAL, CONSEQUENTIAL, EXEMPLARY, MULTIPLE OR OTHER INDIRECT DAMAGES THAT RESULT FROM THE USE OF, OR THE INABILITY TO USE, THIS WEBSITE OR THE INFORMATION CONTAINED ON THIS WEBSITE, EVEN IF BETTINA NETWORK HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. IN NO EVENT SHALL BETTINA NETWORK'S TOTAL LIABILITY TO YOU FOR ALL DAMAGES, LOSSES, AND CAUSES OF ACTION RESULTING FROM YOUR USE OF THIS WEBSITE, WHETHER IN CONTRACT, TORT (INCLUDING, BUT NOT LIMITED TO, NEGLIGENCE) OR OTHERWISE, EXCEED THE AMOUNTS YOU PAID TO BETTINA NETWORK DURING THE MOST RECENT THREE-MONTH PERIOD IN CONNECTION WITH AMOUNTS WHICH YOU PAID FOR USING THIS WEBSITE.</p>

<p>Links to Third-Party Websites</p>

<p>This website may contain links to non-BETTINA NETWORK websites. These links are provided to you as a convenience, and BETTINA NETWORK is not responsible for the content of any linked website. Any outside website accessed from the BETTINA NETWORK website is independent from BETTINA NETWORK, and BETTINA NETWORK has no control over the content of that website. In addition, a link to any non-BETTINA NETWORK website does not imply that BETTINA NETWORK endorses or accepts any responsibility for the content or use of such a website.</p>

<p>No Implied Endorsements</p>
<p>In no event shall any reference to any third party or third party product or service be construed as an approval or endorsement by BETTINA NETWORK of that third party or of any product or service provided by a third party.</p>

<p>Jurisdictional Issues</p>

<p>BETTINA NETWORK makes no representation that information on this website is appropriate or available for use outside the United States. Those who choose to access this website from outside the United States do so on their own initiative and are responsible for compliance with local laws, if and to the extent local laws are applicable.</p>

<p>Governing Law</p>

<p>These Terms and conditions are governed and interpreted pursuant to the laws of Deleware, United States of America, notwithstanding any principles of conflicts of law.</p>

<p>Entire Agreement</p>
<p>This is the entire Agreement between the parties relating to the subject matter herein and shall not be modified except in writing signed by both parties or by a new posting by BETTINA NETWORK, as described above.</p>

<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

<p>For Additional Information</p>
<p>If you have any questions about the rights and restrictions above, please email <? echo ADMIN_EMAIL;?>.</p>
				
			<br/> 
			<br/> 
		<div align="center">
			<a href="login.php">Login </a>here to bid in the auction.<br />
			Not registered? <a href="register.php">Register</a> here.						
		</div>
		<div align="right">
			 <a href="admin_index.php">Administration</a> 
		</div>
    </div>
	
	<div id="footer" >
		<table width="100%">
			<tr>
				<td>
					<a href="http://jigsaw.w3.org/css-validator/check/referer">
					<img style="border:0;width:88px;height:31px"
					src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
					alt="Valid CSS!" />
					</a>
					<a href="http://validator.w3.org/check?uri=referer"><img
					style="border:0;width:88px;height:31px"
					src="http://www.w3.org/Icons/valid-xhtml10-blue"
					alt="Valid XHTML 1.0 Transitional"/></a>
					Site by <a href="mailto:kies@fas.harvard.edu">Benjamin Kies</a>
				</td>
				<td>
					<div id="copyright"> &copy;1993-2009 The Bettina Network, Inc. See <a href="user_agreement.php">User Agreement</a> and <a href="privacy_policy.php">Privacy Policy.</a></div>
				</td>
			</tr>
		</table>
	</div>	
</div>
</body>

</html>
