<?php

    /***********************************************************************
     * helpers.php
     *
     * Computer Science 50
     * Problem Set 7
     *
     * Helper functions.
     **********************************************************************/


    /*
     * void
     * apologize($message)
     *
     * Apologizes to user by displaying a page with message.
     */

    function apologize($message)
    {
        // require template
        require_once("apology.php");

        // exit immediately since we're apologizing
        exit;
    }


    /*
     * void
     * dump($variable)
     *
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */

    function dump($variable)
    {
        // dump variable using some quick and dirty (albeit invalid) XHTML
        if (!$variable && !is_numeric($variable))
            print("Variable is empty, null, or not even set.");
        else
            print("<pre>" . print_r($variable, true) . "</pre>");

        // exit immediately so that we can see what we printed
        exit;
    }


    /*
     * stock
     * lookup($symbol)
     *
     * Returns a stock by symbol (case-insensitively) else NULL if not found.
     *
     * Relies on Microsoft for articles and Yahoo for everything else.
     */

    function lookup($symbol)
    {
        // open connection to Yahoo
        if (($fp = fopen(YAHOO . $symbol, "r")) === FALSE)
            return NULL;

        // download first line of CSV file
        if (($data = fgetcsv($fp)) === FALSE || count($data) == 1)
            return NULL;

        // close connection to Yahoo
        fclose($fp);

        // ensure symbol was found
        if ($data[2] == 0.00)
            return NULL;

        // instantiate a stock object
        $stock = new stock();

        // remember stock's symbol and trades
        $stock->symbol = $data[0];
        $stock->name = $data[1];
        $stock->price = $data[2];
        $stock->time = strtotime($data[3] . " " . $data[4]);
        $stock->change = $data[5];
        $stock->open = $data[6];
        $stock->high = $data[7];
        $stock->low = $data[8];

        // download RSS from MSN
        if (($xml = @simplexml_load_file(MSN. $symbol)) !== FALSE)
        {
            // parse description
            if (preg_match("/^News about (.*)$/", $xml->channel->description, $matches))
            {
                // override Yahoo's uppercase name with MSN's capitalized name
                if (isset($matches[1]))
                    $stock->name = $matches[1];
            }

            // remember news items
            foreach ($xml->channel->item as $item)
                $stock->items[] = (array) $item;
        }

        // return stock
        return $stock;
    }


    /*
     * void
     * redirect($destination)
     * 
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any XHTML.
     */

    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^http:\/\//", $destination))
            header("Location: " . $destination);

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $host = $_SERVER["HTTP_HOST"];
            header("Location: http://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: http://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }

?>
