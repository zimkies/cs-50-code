<?php

    /***********************************************************************
     * constants.php
     *
     * Computer Science 50
     * Problem Set 7
     *
     * Global constants.
     **********************************************************************/


    // your database's name (i.e., username_pset7)
    define("DB_NAME", 'bettina_db');
	//define("DB_NAME", 'ettincom11696net1564_Bettina-Sales'); 

    // your database's username
    define("DB_USER", 'benkies3');
	//define("DB_USER", 'ettin_kies');

    // your database's password
    define("DB_PASS", 'JLxudbXQ');
    //define("DB_PASS", 'Ixtupuful7');	

    // fully qualified domain name of course's database server
    define("DB_SERVER", 'mysql.battleshiplive.com');
    //define("DB_SERVER", 'bettina-network.com');	
	
	// define the folder which this program is in.
	//define("HOME_FOLDER", 'sales/');
	define("HOME_FOLDER", 'bettina/');
	
	// give the working directory of the top-level directory
	define("HOME_PATH", '/home/benkies3/battleshiplive.com/bettina/');
	//define("HOME_PATH", '/home/bettina-network.com/httpdocs/' . HOME_FOLDER);
		
	// define a password to restrict access to certain pages.
	define("AUCTION_PASSWORD", "THUMbal1na");
	
	// define max sizes for uploading files
	define("MAX_MIN_PICTURE_UPLOAD_SIZE", 14900);
	
	// define max sizez for uploading big files
	define("MAX_BIG_PICTURE_UPLOAD_SIZE", 100000);
	
	// The admin email address
	define("ADMIN_EMAIL", "bettinanetwork@comcast.net");
	
	// The admin phone
	define("ADMIN_PHONE", "1800-347-9166");
	
	// website address
	define("WEBSITE_ADDRESS", "http://www.battleshiplive.com/bettina/");
	
	// The maximum number of items to be displayed in a list of auctions, items, users etc
	define("MAX_DISPLAY_NUMBER", 5);
	
	// options for sorting items, and the sequel extentions required to sort them
	$G_ITEM_OPTIONS = array(
							array('price lowest', ' ORDER BY currentprice ASC '), 
							array('price highest', ' ORDER BY currentprice ASC '), 
							array('id Ascending', ' ORDER BY item_id ASC '),
							array('id Descending', ' ORDER BY item_id DESC '));
	
	// options for sorting auctions, and the sequel extentions required to sort them
	$G_AUCTION_OPTIONS = array(
							array('ending soonest', ' ORDER BY end DESC '), 
							array('ending latest', ' ORDER BY end ASC '), 
							array('name Ascending', ' ORDER BY name ASC '),
							array('name Descending', ' ORDER BY name DESC '));
						
	// options for sorting auctions, and the sequel extentions required to sort them
	$G_ADMIN_AUCTION_OPTIONS = array(
							array('ending soonest', ' ORDER BY end DESC '), 
							array('ending latest', ' ORDER BY end ASC '), 
							array('name Ascending', ' ORDER BY name ASC '),
							array('name Descending', ' ORDER BY name DESC '),
							array('Id Ascending', ' ORDER BY uid ASC '),
							array('Id Descending', ' ORDER BY uid DESC '));
						
	// options for sorting users, and the sequel extentions required to sort them
	$G_USER_OPTIONS = array(
							array('username Ascending', ' ORDER BY username ASC '),
							array('username Descending', ' ORDER BY username DESC '),
							array('lastname Ascending', ' ORDER BY lastname ASC '),
							array('lastname Descending', ' ORDER BY lastname DESC '),
							array('Id Ascending', ' ORDER BY uid ASC '),
							array('Id Descending', ' ORDER BY uid DESC '),
							array('Permission? Ascending', ' ORDER BY permission ASC '),
							array('Permission? Descending', ' ORDER BY permission DESC '));

?>
