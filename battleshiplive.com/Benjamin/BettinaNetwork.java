package com.bettina;

import com.birchweb.dbpool.ConnectionPool;
import java.sql.*;
import java.util.*;
import java.io.*;
import java.net.MalformedURLException;
import com.lowagie.text.*;
import com.lowagie.text.pdf.*;

import java.text.SimpleDateFormat;
import java.text.ParseException;
import javax.servlet.*;

/**
 * A BettinaNetwork object is the controller for the user view
 * of the Bettina Network site.  It provides the JSPs with operations
 * on the underlying database.  It also provides helper methods to
 * keep the presentation layer cleaner.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class BettinaNetwork {

	public final static String TOKEN = "TOKEN";
	
	//Web Visitors
	private static final String UPDATE_WEB_VISITOR="update webvisitors set access=?";
	
	//Site Status
    private static final String SELECT_AVAILABILITY="select updown from sitestatus where id=?";

	//Category
    private static final String GET_CATEGORIES="select id, name, description, referenced from category order by name asc";
    private static final String GET_CATEGORY="select id, name, description, referenced from category where id=?";
    
    //User
    private static final String GET_USER_BY_NAME="select id, first, last, name, password, email, arrival, departure, city, state from user where name=?";
     
    //Event
    private static final String GET_EVENT="select id,name,location,address1,address2,city,state,zipcode,url,phone,cost,description,category from event where id=?";
    private static final String GET_EVENT_CITIES="select DISTINCT city from event where state=? order by city asc";
    private static final String GET_PDF_STATE_EVENTS_ALL_CATEGORIES="select DISTINCT a.id,a.name,location,address1,address2,city,state,zipcode,url,phone,cost,a.description,category from event a, eventdate b, category c where a.id = b.eventid and a.category = c.id and state=? and ((? >= b.begdate and ? <= b.enddate) or (? >= b.begdate and ? <= b.enddate) or (? <= b.begdate and ? >= b.enddate)) order by c.name, a.name asc";
        
    //Event Dates
    private static final String GET_EVENTDATES="select id, eventid, datetype, begdate, enddate, timestr from eventdate where eventid=?";
    
    //Hours
    private static final String GET_HOURS="select id, eventid, day, closed, timestr from hours where eventid=? order by day asc";
    
    //Cambridge Address
    private static final String GET_CAMBRIDGE="select address1, address2, city, state, zipcode, phone1, phone2, fax, url from cambridge";
    
    //Pdf First Page
    private static final String GET_FIRSTPAGE="select welcome, par1, par2, par3, par4, par5, par6 from pdf";
       
    
    private String POOL_CFG_FILE = "com.bettina.BettinaPool";
	private String PDF_PROPS = "com.bettina.pdf";
	
	private String EXCEPTION_EMAIL_TO="tomcat1@bettina-network.com";
	private String EXCEPTION_EMAIL_FROM="info@bettina-network.com";
	
    private ConnectionPool pool = null;
    private String pdfFolder = "";
    private String imagesFolder = "";
    
    
    /**
     * Construct an instance of Bettina Network
     * @throws Exception
     */
    public BettinaNetwork() throws Exception {
    	this.pool = new ConnectionPool();
        try {
            this.pool.initialize(POOL_CFG_FILE);
        } catch( Exception except) {
            System.out.println("Failed to create the BettinaNetwork object, exception: " + except.getMessage());
            throw except;
        }
        
        java.util.PropertyResourceBundle props = (java.util.PropertyResourceBundle)java.util.ResourceBundle.getBundle(PDF_PROPS);
        pdfFolder = props.getString("directory");
        imagesFolder = props.getString("images");
    }
    
    /**
     * Closes all the connections in the connection pool.
     * @return The number of database connections destroyed
     * @throws Exception
     *      Failed while shutting down the database
     */
    public int shutdown() throws Exception {
        return this.pool.destroy();    
    }
    
    /**
     * Retrieve the information about the current open database
     * connections.
     * @return Database connection information
     */
    public String getPoolInfo() {
        return this.pool.getPoolInfo();    
    }
    
    /**
     * Reinitialize the database connection pool
     * @return True if the database was reinitialized; otherwise false
     * @throws Exception
     *      Failed to reinitialize the database
     */
    public boolean reinitializePool() throws Exception {
        return this.pool.initialize(POOL_CFG_FILE);    
    }
            
    /**
     * Return the number of connections that are currently open to the 
     * database.
     * @return The number of open database connections
     */
    public int getNumConnections() {
        return this.pool.getNumConnections();    
    }
    
    /**
     * Retrieve all the categories in a Vector
     * @param context The servlet context
     * @return A Vector of Categories
     * @throws SQLException
     * 		Failed while accessing database
     */
    public Vector getCategories(ServletContext context) throws SQLException {
    	Connection conn = null;
    	Statement stmt = null;
    	ResultSet rs = null;
        Vector categories = new Vector();
        
        try {        	
            conn = this.pool.getConnection();
            stmt = conn.createStatement();
            rs = stmt.executeQuery(GET_CATEGORIES);
            while( rs.next() ) {
            	Category cat = new Category();
                cat.setId(rs.getInt(1));
                cat.setName(rs.getString(2));
                cat.setDescription(rs.getString(3));
                cat.setReferenced(rs.getInt(4));
                categories.add(cat);
            }
            stmt.close();
            rs.close();
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getCategories", sExcept);
            throw sExcept;
        } finally {
        	try { if( stmt != null) stmt.close(); } catch(SQLException ex) {}
        	try { if( rs != null) rs.close(); } catch(SQLException ex) {}
        	if( conn != null ) { this.pool.close(conn); }
        }
        return categories;
    }
    
    public Category getCategory(ServletContext context, int id) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_CATEGORY);
            pstmt.setInt(1, id);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	Category cat = new Category();
            	cat.setId(rs.getInt(1));
                cat.setName(rs.getString(2));
                cat.setDescription(rs.getString(3));
                cat.setReferenced(rs.getInt(4));
                pstmt.close();
                rs.close();
                return cat;
            }
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getCategory", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Retrieve the User object given the user's name.
     * @param context The servlet context
     * @param name The user's name
     * @return User object; otherwise null
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public User getUserByName(ServletContext context, String name) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_USER_BY_NAME);
            pstmt.setString(1, name);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	User user = new User();
            	user.setId(rs.getInt(1));
            	user.setFirst(rs.getString(2));
            	user.setLast(rs.getString(3));
                user.setName(rs.getString(4));
                user.setPassword(rs.getString(5));
                user.setEmail(rs.getString(6));
                user.setArrival(rs.getString(7));
                user.setDeparture(rs.getString(8));
                user.setCity(rs.getString(9));
                user.setState(rs.getString(10));
                pstmt.close();
                rs.close();
                return user;
            }
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getUserByName", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
     
    /**
     * Retrieve the name of the cities that have events in the given state.
     * @param context Servlet context
     * @param state State tp check for city names
     * @return Vector of city names
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getStateCities(ServletContext context, String state) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector cities = new Vector();         
        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_EVENT_CITIES);
            pstmt.setString(1, state);
            rs = pstmt.executeQuery();
            while( rs.next() ) {
            	cities.add(rs.getString(1));
            }
            pstmt.close();
            rs.close();
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getStateCities", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return cities;
    }
    
    /**
     * Retrieve the events for the given state, categories, and date range.
     * @param context Servlet context for logging
     * @param state State to include in result set
     * @param categories Categories to include in result set
     * @param bdate Start date of event search
     * @param edate End date of event search
     * @return Vector of Event objects
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getPdfStateEvents(ServletContext context, String state, String[] categories, String bdate, String edate) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector events = new Vector();
         
        StringBuffer buffer = new StringBuffer("select DISTINCT a.id,a.name,location,address1,address2,city,state,zipcode,url,phone,cost,a.description,category from event a, eventdate b, category c where a.id = b.eventid and a.category = c.id and state=? and a.category in"); 
        buffer.append("(");
        buffer.append(this.commaSeperate(categories));
        buffer.append(")");
        buffer.append(" and ((? >= b.begdate and ? <= b.enddate) or (? >= b.begdate and ? <= b.enddate) or (? <= b.begdate and ? >= b.enddate)) order by c.name, a.name asc");
        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(buffer.toString());
            pstmt.setString(1, state);
            pstmt.setString(2, bdate);
            pstmt.setString(3, bdate);
            pstmt.setString(4, edate);
            pstmt.setString(5, edate);
            pstmt.setString(6, bdate);
            pstmt.setString(7, edate);
            rs = pstmt.executeQuery();
            while( rs.next() ) {
            	Event event = new Event();
            	event.setId(rs.getInt(1));
            	event.setName(rs.getString(2));
            	event.setLocation(rs.getString(3));
            	event.setAddress1(rs.getString(4));
            	event.setAddress2(rs.getString(5));
            	event.setCity(rs.getString(6));
            	event.setState(rs.getString(7));
            	event.setZipCode(rs.getString(8));
            	event.setUrl(rs.getString(9));
            	event.setPhone(rs.getString(10));
            	event.setCost(rs.getString(11));
            	event.setDescription(rs.getString(12));
            	event.setCategory(rs.getInt(13));
            	events.add(event);
            }
            rs.close();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getPdfStateEvents", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return events;
    }
    
    /**
     * Retrieve an Event given an event id
     * @param context Servlet context for logging
     * @param id Event id
     * @return Event object
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Event getEvent(ServletContext context, int id) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_EVENT);
            pstmt.setInt(1, id);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	Event event = new Event();
            	event.setId(rs.getInt(1));
            	event.setName(rs.getString(2));
            	event.setLocation(rs.getString(3));
            	event.setAddress1(rs.getString(4));
            	event.setAddress2(rs.getString(5));
            	event.setCity(rs.getString(6));
            	event.setState(rs.getString(7));
            	event.setZipCode(rs.getString(8));
            	event.setUrl(rs.getString(9));
            	event.setPhone(rs.getString(10));
            	event.setCost(rs.getString(11));
            	event.setDescription(rs.getString(12));
            	event.setCategory(rs.getInt(13));
            	rs.close();
            	pstmt.close();
            	return event;
            }
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getEvent", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Retrieve the events for the given state and date range.
     * @param context Servlet context for logging
     * @param state State to include in result set
     * @param bdate Start date of event search
     * @param edate End date of event search
     * @return Vector of Event objects
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getPdfStateEventsAllCategories(ServletContext context, String state, String bdate, String edate) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector events = new Vector();
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_PDF_STATE_EVENTS_ALL_CATEGORIES);
            pstmt.setString(1, state);
            pstmt.setString(2, bdate);
            pstmt.setString(3, bdate);
            pstmt.setString(4, edate);
            pstmt.setString(5, edate);
            pstmt.setString(6, bdate);
            pstmt.setString(7, edate);
            rs = pstmt.executeQuery();
            while( rs.next() ) {
            	Event event = new Event();
            	event.setId(rs.getInt(1));
            	event.setName(rs.getString(2));
            	event.setLocation(rs.getString(3));
            	event.setAddress1(rs.getString(4));
            	event.setAddress2(rs.getString(5));
            	event.setCity(rs.getString(6));
            	event.setState(rs.getString(7));
            	event.setZipCode(rs.getString(8));
            	event.setUrl(rs.getString(9));
            	event.setPhone(rs.getString(10));
            	event.setCost(rs.getString(11));
            	event.setDescription(rs.getString(12));
            	event.setCategory(rs.getInt(13));
            	events.add(event);
            }
            rs.close();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getPdfStateEventsAllCategories", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return events;
    }
    
    /**
     * Retrieve the events for the given cities, categories, and date range.
     * @param context Servlet context for logging
     * @param cities Cities to include in result set
     * @param categories Categories to include in result set
     * @param bdate Start date of event search
     * @param edate End date of event search
     * @return Vector of Event objects
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getPdfCityEvents(ServletContext context, String[] cities, String state, String[] categories, String bdate, String edate) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector events = new Vector();
          
        StringBuffer buffer = new StringBuffer("select DISTINCT a.id,a.name,location,address1,address2,city,state,zipcode,url,phone,cost,a.description,category from event a, eventdate b, category c where a.id = b.eventid and a.category = c.id and a.state = ? and city in ");
        buffer.append("(");
        buffer.append(this.commaSeperate(cities));
        buffer.append(")");
        buffer.append(" and a.category in ");
        buffer.append("(");
        buffer.append(this.commaSeperate(categories));
        buffer.append(")");
        buffer.append(" and ((? >= b.begdate and ? <= b.enddate) or (? >= b.begdate and ? <= b.enddate) or (? <= b.begdate and ? >= b.enddate)) order by c.name, a.name asc");
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(buffer.toString());
            pstmt.setString(1, state);
            pstmt.setString(2, bdate);
            pstmt.setString(3, bdate);
            pstmt.setString(4, edate);
            pstmt.setString(5, edate);
            pstmt.setString(6, bdate);
            pstmt.setString(7, edate);
            rs = pstmt.executeQuery();
            while( rs.next() ) {
            	Event event = new Event();
            	event.setId(rs.getInt(1));
            	event.setName(rs.getString(2));
            	event.setLocation(rs.getString(3));
            	event.setAddress1(rs.getString(4));
            	event.setAddress2(rs.getString(5));
            	event.setCity(rs.getString(6));
            	event.setState(rs.getString(7));
            	event.setZipCode(rs.getString(8));
            	event.setUrl(rs.getString(9));
            	event.setPhone(rs.getString(10));
            	event.setCost(rs.getString(11));
            	event.setDescription(rs.getString(12));
            	event.setCategory(rs.getInt(13));
            	events.add(event);
            }
            rs.close();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getPdfCityEvents", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return events;
    }
    
    /**
     * Retrieve the events for the given cities and date range.
     * @param context Servlet context for logging
     * @param cities Cities to include in result set
     * @param bdate Start date of event search
     * @param edate End date of event search
     * @return Vector of Event objects
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getPdfCityEventsAllCategories(ServletContext context, String[] cities, String state, String bdate, String edate) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector events = new Vector();
          
        StringBuffer buffer = new StringBuffer("select DISTINCT a.id,a.name,location,address1,address2,city,state,zipcode,url,phone,cost,a.description,category from event a, eventdate b, category c where a.id = b.eventid and a.category = c.id and a.state = ? and city in (");
        buffer.append(this.commaSeperate(cities));
        buffer.append(")");
        buffer.append(" and ((? >= b.begdate and ? <= b.enddate) or (? >= b.begdate and ? <= b.enddate) or (? <= b.begdate and ? >= b.enddate)) order by c.name, a.name asc");
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(buffer.toString());
            pstmt.setString(1, state);
            pstmt.setString(2, bdate);
            pstmt.setString(3, bdate);
            pstmt.setString(4, edate);
            pstmt.setString(5, edate);
            pstmt.setString(6, bdate);
            pstmt.setString(7, edate);
            rs = pstmt.executeQuery();
            while( rs.next() ) {
            	Event event = new Event();
            	event.setId(rs.getInt(1));
            	event.setName(rs.getString(2));
            	event.setLocation(rs.getString(3));
            	event.setAddress1(rs.getString(4));
            	event.setAddress2(rs.getString(5));
            	event.setCity(rs.getString(6));
            	event.setState(rs.getString(7));
            	event.setZipCode(rs.getString(8));
            	event.setUrl(rs.getString(9));
            	event.setPhone(rs.getString(10));
            	event.setCost(rs.getString(11));
            	event.setDescription(rs.getString(12));
            	event.setCategory(rs.getInt(13));
            	events.add(event);
            }
            rs.close();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getPdfCityEventsAllCategories", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return events;
    }
    
    /**
     * Return the String array as a comma seperated list.
     * @param vals String array of Strings
     * @return String of comma seperated values
     */
    private String commaSeperate(String[] vals) {
    	StringBuffer result = new StringBuffer();
    	for(int x = 0; x < vals.length; x++) {
    		result.append("'");
    		result.append(vals[x]);
    		result.append("'");
    		if( (x+1) != vals.length) {
    			result.append(",");
    		}
    	}
    	return result.toString();
    }
    
    /**
     * Retrieve the Cambridge location address.
     * @param context Servlet context for logging
     * @return Cambridge address; otherwise null
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Cambridge getCambridge(ServletContext context) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_CAMBRIDGE);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	Cambridge address = new Cambridge();
            	address.setAddress1(rs.getString(1));
            	address.setAddress2(rs.getString(2));
            	address.setCity(rs.getString(3));
            	address.setState(rs.getString(4));
            	address.setZipCode(rs.getString(5));
            	address.setPhone1(rs.getString(6));
            	address.setPhone2(rs.getString(7));
            	address.setFax(rs.getString(8));
            	address.setUrl(rs.getString(9));
            	pstmt.close();
                rs.close();
                return address;
            }
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getCambridge", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Retrieve the event dates associated with the event.
     * @param context Servlet context
     * @param eventid Event id
     * @return Vector of EventDate objects
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getEventDates(ServletContext context, int eventid) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector eventdates = new Vector();
                        
        try {
        	conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_EVENTDATES);
            pstmt.setInt(1, eventid);
            rs = pstmt.executeQuery();
            while( rs.next() ) {
            	EventDate eventdate = new EventDate();
            	eventdate.setId(rs.getInt(1));
            	eventdate.setEventId(rs.getInt(2));
            	eventdate.setDateType(rs.getInt(3));
            	eventdate.setBegDate(rs.getString(4));
            	eventdate.setEndDate(rs.getString(5));
            	eventdate.setTimeStr(rs.getString(6));
            	eventdates.add(eventdate);
            }
            rs.close();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getEventDates", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return eventdates;
    }
    
    /**
     * Retrieve the hours for an event
     * @param context Servlet context
     * @param eventid Event id
     * @return Hours for an event
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getHours(ServletContext context, int eventid) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector hours = new Vector();
                                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_HOURS);
            pstmt.setInt(1, eventid);
            rs = pstmt.executeQuery();
            while( rs.next() ) {
            	EventHour hour = new EventHour();
            	hour.setId(rs.getInt(1));
            	hour.setEventId(rs.getInt(2));
            	hour.setDay(rs.getInt(3));
            	hour.setClosed(rs.getInt(4));
            	hour.setTimeStr(rs.getString(5));
            	hours.add(hour);
            }	
            rs.close();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getHours", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return hours;
    }
    
    /**
     * Check to see if the website with the given id is available.
     * @param context The servlet context
     * @param id The website id
     * @return true if the website is available; otherwise false
     * @throws SQLException
     * 		Failed while accessing database
     */
    public boolean isSiteAvailable(ServletContext context, int id) throws SQLException {
       Connection conn = null;
       PreparedStatement pstmt = null;
       ResultSet rs = null;
       
       int status = 0;
       
       try {
           conn = this.pool.getConnection();
           if( conn == null ) {
           	return false;
           }
           pstmt = conn.prepareStatement(SELECT_AVAILABILITY);
           pstmt.setInt(1, id);
           rs = pstmt.executeQuery();
           if( rs.next() ) {
               status = rs.getInt(1);
           }
           pstmt.close();
           rs.close();
       } catch( SQLException sExcept ) {
    	   context.log("(BN) Failed in isSiteAvailable", sExcept);
           throw sExcept;
       } finally {
    	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
    	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
       	   if( conn != null ) { this.pool.close(conn); }
       }
       
       if( status == 1 )
           return true;
       return false;        
    }
    
    /**
     * Retrieve the information for the first page of the PDF.
     * @param context Servlet context for logging
     * @return FirstPage object
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public FirstPage getFirstPage(ServletContext context) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_FIRSTPAGE);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	FirstPage page = new FirstPage();
            	page.setWelcome(rs.getString(1));
            	page.setPar1(rs.getString(2));
            	page.setPar2(rs.getString(3));
            	page.setPar3(rs.getString(4));
            	page.setPar4(rs.getString(5));
            	page.setPar5(rs.getString(6));
            	page.setPar6(rs.getString(7));
            	pstmt.close();
                rs.close();
                return page;
            }
        } catch( SQLException sExcept ) {
        	context.log("(BN) Failed in getFirstPage", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Update the date/time that a user last visited the Bettina
     * Network site.
     * @param context The Servlet context, used for logging
     * @throws SQLException
     *      Failed while accessing the database		
     */
    public void updateWebVisitor(ServletContext context) throws SQLException {
        Connection conn = null;
        PreparedStatement pstmt = null;
        
        try {
        	conn = this.pool.getConnection();
            if( conn == null ) {
            	return;
            }
        	pstmt = conn.prepareStatement(UPDATE_WEB_VISITOR);
            pstmt.setString(1, this.getCurrentDate());
            pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
            context.log("(BN) Failed in updateWebVisitor", sExcept);
            throw sExcept;
        } finally {
        	try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
        	if( conn != null ) { this.pool.close(conn); }
        }    	    	
    }
    
    /**
     * Retrieve the current date as a String that can be saved
     * to the database.
     * @return Date in format 0000-00-00 00:00:00
     */
    private String getCurrentDate() {
    	TimeZone tz = TimeZone.getTimeZone("America/New_York");
        Calendar cal = Calendar.getInstance(tz);
                    
        StringBuffer dateStr = new StringBuffer(String.valueOf(cal.get(Calendar.YEAR)));
        
        dateStr.append("-");
        int month = cal.get(Calendar.MONTH)+1;
        if( month < 10 ) {
        	dateStr.append("0");
        	dateStr.append(String.valueOf(month));
        } else {
        	dateStr.append(String.valueOf(month));
        }
        
        
        dateStr.append("-");
        int day = cal.get(Calendar.DAY_OF_MONTH);
        if( day < 10 ) {
        	dateStr.append("0");
        	dateStr.append(String.valueOf(day));
        } else {
        	dateStr.append(String.valueOf(day));
        }
        
        dateStr.append(" ");
        int hour = cal.get(Calendar.HOUR_OF_DAY);
        if( hour < 10 ) {
        	dateStr.append("0");
        	dateStr.append(String.valueOf(hour));
        } else {
        	dateStr.append(String.valueOf(hour));
        }
        
        dateStr.append(":");
        int min = cal.get(Calendar.MINUTE);
        if( min < 10 ) {
        	dateStr.append("0");
        	dateStr.append(String.valueOf(min));
        } else {
        	dateStr.append(String.valueOf(min));
        } 
        
        dateStr.append(":00");
        return dateStr.toString();
    }
    
    /**
     * Create the event menu pdf given a list of events.
     * @param context Servlet context for logging
     * @param userName Name to use for the pdf file
     * @param properName User's first & last name for writing in the pdf
     * @param events Vector of events to add to the event menu
     * @throws SQLException
     * 		Failed while accessing the database
     * @throws DocumentException
     * 		Failed creating the pdf
     * @throws ParseException
     * 		Failed writing to the pdf
     * @throws IOException
     * 		Failed writing to the pdf
     */
    public void createEventPdf(ServletContext context, String userName, String properName, Vector events) throws SQLException, DocumentException, ParseException, IOException {
    	
    	StringBuffer filename = new StringBuffer(pdfFolder);
    	filename.append("/");
    	filename.append(userName);
    	filename.append(".pdf");
    	    	
    	Document document = new Document();
    	PdfWriter writer = PdfWriter.getInstance(document,	new FileOutputStream(filename.toString()));
		writer.setPageEvent(new HeaderFooter(context, this, imagesFolder));
		document.setPageSize( PageSize.LETTER );
		document.setMargins(18,18,18,80);
		document.open();
		
		this.generateFirstPagePdf(context, document, properName, userName);
		document.newPage();
		
		StringBuffer ttfBuffer = new StringBuffer(imagesFolder);
		ttfBuffer.append("/PAPYRUS.TTF");
		   	
		BaseFont bf;
		bf = BaseFont.createFont(ttfBuffer.toString(), BaseFont.CP1252, BaseFont.EMBEDDED);
					
		Font[] fonts = new Font[4];
		fonts[0] = FontFactory.getFont(FontFactory.HELVETICA, 10, Font.BOLD);
		fonts[1] = new Font(bf, 12, Font.BOLD);
		fonts[2] = new Font(bf, 12, Font.BOLD | Font.UNDERLINE);
		fonts[3] = new Font(bf, 12);
		
		StringBuffer lineName = new StringBuffer(imagesFolder);
    	lineName.append("/thin-line.jpg");
    				
		Vector cats = getCategories(context);
			
		//line seperator
		float[] widths1 = {1f,2f};
		PdfPTable lineTable = new PdfPTable(widths1);
		lineTable.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
		lineTable.setSpacingBefore(5f);
		lineTable.setSpacingAfter(5f);
		
		Image lineImg = Image.getInstance(lineName.toString());
		lineImg.setAlignment(Image.ALIGN_LEFT);
		lineTable.addCell( lineImg );
		lineTable.addCell( new Paragraph("") );
		
		lineTable.setWidthPercentage(100);
		
		PdfPCell lineCell = new PdfPCell();
    	lineCell.setBorder(PdfPCell.NO_BORDER);
    	lineCell.addElement(lineTable);
    	lineCell.setFixedHeight(10f);
    	
		String catName = "";
		int prevCat = -1;
		int count = 1;
		
		PdfPTable itable = new PdfPTable(1);
		itable.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
		
		Iterator eventIter = events.iterator();
		while( eventIter.hasNext() ) {
			Event evt = (Event)eventIter.next();
						
			if( evt.getCategory() != prevCat ) {
				Iterator catIter = cats.iterator();
				while( catIter.hasNext() ) {
					Category cat = (Category)catIter.next();
					if( cat.getId() == evt.getCategory() ) {
						catName = new String(cat.getName());
					}
				}
			}
			
			try {
				itable.addCell(addSectionData(context, catName, evt, fonts));
			} catch( Throwable except ) {
				context.log("Failed to add an event to a pdf, exception: " + except.getMessage());
			}
			prevCat = evt.getCategory();
			if( ((count % 2) == 0) || !eventIter.hasNext() ) {
				document.add(itable);
				document.newPage();
				itable = new PdfPTable(1);
			} else {
				itable.addCell(lineCell);
			}
			++count;
		}
						
		document.close();
	}
    
    /**
     * Add the first page to the pdf document.
     * @param document The pdf document
     * @param properName User's first & last name to add to document
     * @param fonts Fonts to use within the document
     * @throws DocumentException
     * 		Failed while accessing the document
     * @throws IOException
     * 		Failed writing to the document
     */
    private void generateFirstPagePdf(ServletContext context, Document document, String properName, String userName) throws DocumentException, IOException {
    			
    	StringBuffer ttfBuffer = new StringBuffer(imagesFolder);
		ttfBuffer.append("/PAPYRUS.TTF");
		
    	Font[] fonts = new Font[2];
		BaseFont bf;
		bf = BaseFont.createFont(ttfBuffer.toString(), BaseFont.CP1252, BaseFont.EMBEDDED);
		fonts[0] = new Font(bf, 14);
		fonts[1] = FontFactory.getFont(FontFactory.HELVETICA, 12);
		
    	StringBuffer buffer = new StringBuffer(this.imagesFolder);
    	buffer.append("/pdf_preparedfor2.gif");
    	
    	Image img1 = Image.getInstance(buffer.toString());
		img1.setAlignment(Image.LEFT | Image.UNDERLYING);
		img1.scalePercent(25);
		document.add(img1);
		
		Phrase p = new Phrase("\n");
		document.add(p);
		p = new Phrase("\n");
		document.add(p);
		p = new Phrase("\n");
		document.add(p);
		p = new Phrase("\n");
		document.add(p);
		p = new Phrase("\n");
		document.add(p);
		p = new Phrase("                                                            ", fonts[1]);
		p.add(properName);
		document.add(p);
		
		p = new Phrase("\n\n\n\n\n");
		document.add(p);
		FirstPage fpage = new FirstPage();
		
		try {
			fpage = this.getFirstPage(context);
		} catch(SQLException except) {
			context.log("Failed retrieving first page of pdf", except);
		}
		
		Paragraph par  = new Paragraph(fpage.getWelcome(), fonts[0]);
		par.setAlignment(Element.ALIGN_CENTER);
		par.setLeading(18);
		document.add(par);
		
		p = new Phrase("\n");
		document.add(p);
		
		par = new Paragraph(fpage.getPar1(), fonts[0]);
		par.setAlignment(Element.ALIGN_LEFT);
		par.setIndentationLeft(60);
		par.setIndentationRight(60);
		par.setLeading(18);
		document.add(par);

		p = new Phrase("\n");
		document.add(p);
		
		par = new Paragraph(fpage.getPar2(), fonts[0]);
		par.setAlignment(Element.ALIGN_LEFT);
		par.setIndentationLeft(60);
		par.setIndentationRight(60);
		par.setLeading(18);
		document.add(par);
		
		p = new Phrase("\n");
		document.add(p);
		
		par = new Paragraph(fpage.getPar3(), fonts[0]);
		par.setAlignment(Element.ALIGN_LEFT);
		par.setIndentationLeft(60);
		par.setIndentationRight(60);
		par.setLeading(18);
		document.add(par);
		
		p = new Phrase("\n");
		document.add(p);
		
		par = new Paragraph(fpage.getPar4(), fonts[0]);
		par.setAlignment(Element.ALIGN_LEFT);
		par.setIndentationLeft(60);
		par.setIndentationRight(60);
		par.setLeading(18);
		document.add(par);
		
		p = new Phrase("\n");
		document.add(p);
		
		par = new Paragraph(fpage.getPar5(), fonts[0]);
		par.setAlignment(Element.ALIGN_LEFT);
		par.setIndentationLeft(60);
		par.setIndentationRight(60);
		par.setLeading(18);
		document.add(par);
		
		p = new Phrase("\n");
		document.add(p);
		
		par = new Paragraph(fpage.getPar6(), fonts[0]);
		par.setAlignment(Element.ALIGN_LEFT);
		par.setIndentationLeft(60);
		par.setIndentationRight(60);
		par.setLeading(18);
		document.add(par);
	}
            
    /**
     * Add the given event to the given section of the pdf document.
     * @param context Servlet context for logging 
     * @param section Section of document to add event to
     * @param evt Event to add to document
     * @param fonts Fonts to use within the document
     * @throws SQLException
     * 		Failed while accessing the database
     * @throws ParseException
     * 		Failed while parsing the document
     */
    private PdfPCell addSectionData(ServletContext context, String catName, Event evt, Font[] fonts) throws SQLException, ParseException, DocumentException, IOException, MalformedURLException {
    	PdfPCell eventCell = new PdfPCell();
    	eventCell.setBorder(PdfPCell.NO_BORDER);
    	eventCell.setFixedHeight(335f);
    	
    	PdfPTable itable = new PdfPTable(2);
		itable.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
    	
		PdfPTable innertable = new PdfPTable(1);
		innertable.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
		
		Paragraph category = new Paragraph(catName, fonts[0]);
		PdfPCell cellCategory = new PdfPCell( category ); 
		cellCategory.setBorder(PdfPCell.NO_BORDER);
		cellCategory.setPaddingBottom(10);
		cellCategory.setPaddingTop(30);
		innertable.addCell(cellCategory);
		
		Paragraph event = new Paragraph(evt.getName(), fonts[1]);
		PdfPCell cellEvent = new PdfPCell( event );
		cellEvent.setPadding(0);
		cellEvent.setBorder(PdfPCell.NO_BORDER);
		event.setLeading(16);
		innertable.addCell(cellEvent);
				
		itable.addCell( innertable );
		//document.add( itable );
				
		StringBuffer imageBuffer = new StringBuffer(this.imagesFolder);
		imageBuffer.append("/letterhead2.gif");
		
		Image img2 = Image.getInstance(imageBuffer.toString());
		img2.scalePercent(24);
		PdfPCell imageCell = new PdfPCell(img2, false);
		imageCell.setBorder( PdfPCell.NO_BORDER );
		itable.addCell( imageCell );
		
		itable.setWidthPercentage(100);
		eventCell.addElement(itable);
    	    	
    	PdfPTable table = new PdfPTable(3);
		table.setSpacingAfter(5f);
		table.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
				
		Vector edates = getEventDates(context, evt.getId());
		EventDate edate = (EventDate)edates.get(0);
		
		SimpleDateFormat endDFormat = new SimpleDateFormat("M/d/yyyy");
   		SimpleDateFormat startDFormat = new SimpleDateFormat("yyyy-MM-dd");
    	   		
		if( edate.getDateType() == 1 ) {
			String startD = endDFormat.format(startDFormat.parse(edate.getBegDate()));
			String startT = "";
			if( edate.getTimeStr() != null ) {
				startT = edate.getTimeStr();
			}
			String endD = endDFormat.format(startDFormat.parse(edate.getEndDate()));
			
			Paragraph from = new Paragraph();
			from.add( new Phrase("From: ", fonts[2]));
			from.add( new Phrase(startD, fonts[3]));
			PdfPCell fromCell = new PdfPCell( from ); 
			fromCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell( fromCell );
			
			Paragraph to = new Paragraph();
			to.add( new Phrase("To: ", fonts[2]));
			to.add( new Phrase(endD, fonts[3]));
			PdfPCell toCell = new PdfPCell( to ); 
			toCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell(toCell);
			
			Paragraph time = new Paragraph();
			time.add( new Phrase("Time: ", fonts[2]));
			time.add( new Phrase(startT, fonts[3]));
			PdfPCell timeCell = new PdfPCell( time );
			timeCell.setLeading(14f,0);
			timeCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell(timeCell);
			
		} else if( edate.getDateType() == 2 ) {
			String startD = endDFormat.format(startDFormat.parse(edate.getBegDate()));
			String endD = endDFormat.format(startDFormat.parse(edate.getEndDate()));
			
			Paragraph from = new Paragraph();
			from.add( new Phrase("From: ", fonts[2]));
			from.add( new Phrase(startD, fonts[3]));
			PdfPCell fromCell = new PdfPCell( from ); 
			fromCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell( fromCell );
			
			Paragraph to = new Paragraph();
			to.add( new Phrase("To: ", fonts[2]));
			to.add( new Phrase(endD, fonts[3]));
			PdfPCell toCell = new PdfPCell( to ); 
			toCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell(toCell);
									
			Paragraph timepar = new Paragraph();
			timepar.add( new Phrase("Time:\n", fonts[2]));
			
			Vector hours = this.getHours(context, evt.getId());
			Iterator hourIter = hours.iterator();
			while( hourIter.hasNext() ) {
				EventHour hour = (EventHour)hourIter.next();
				if( hour.getDay() == 1 ) {
					if( hour.getClosed() == 1 ) {
						timepar.add( new Phrase(" Mon: Closed\n", fonts[3]));
					} else {
						timepar.add( new Phrase(" Mon: " + hour.getTimeStr() + "\n", fonts[3]));
					}
				} else if( hour.getDay() == 2 ) {
					if( hour.getClosed() == 1 ) {
						timepar.add( new Phrase(" Tues: Closed\n", fonts[3]));
					} else {
						timepar.add( new Phrase(" Tues: " + hour.getTimeStr() + "\n", fonts[3]));
					}
				} else if( hour.getDay() == 3 ) {
					if( hour.getClosed() == 1 ) {
						timepar.add( new Phrase(" Wed: Closed\n", fonts[3]));
					} else {
						timepar.add( new Phrase(" Wed: " + hour.getTimeStr() + "\n", fonts[3]));
					}
				} else if( hour.getDay() == 4 ) {
					if( hour.getClosed() == 1 ) {
						timepar.add( new Phrase(" Thurs: Closed\n", fonts[3]));
					} else {
						timepar.add( new Phrase(" Thurs: " + hour.getTimeStr() + "\n", fonts[3]));
					}
				} else if( hour.getDay() == 5 ) {
					if( hour.getClosed() == 1 ) {
						timepar.add( new Phrase(" Fri: Closed\n", fonts[3]));
					} else {
						timepar.add( new Phrase(" Fri: " + hour.getTimeStr() + "\n", fonts[3]));
					}
				} else if( hour.getDay() == 6 ) {
					if( hour.getClosed() == 1 ) {
						timepar.add( new Phrase(" Sat: Closed\n", fonts[3]));
					} else {
						timepar.add( new Phrase(" Sat: " + hour.getTimeStr() + "\n", fonts[3]));					}
				} else if( hour.getDay() == 7 ) {
					if( hour.getClosed() == 1 ) {
						timepar.add( new Phrase(" Sun: Closed\n", fonts[3]));	
					} else {
						timepar.add( new Phrase(" Sun: " + hour.getTimeStr() + "\n", fonts[3]));
					}
				}
			}
						
			PdfPCell timeCell = new PdfPCell( timepar );
			timeCell.setLeading(14f,0);
			timeCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell(timeCell);
								
		} else if( edate.getDateType() == 3) {
			String startD = endDFormat.format(startDFormat.parse(edate.getBegDate()));
			String startT = "";
			if( edate.getTimeStr() != null ) {
				startT = edate.getTimeStr();
			}
			
			Paragraph from = new Paragraph();
			from.add( new Phrase("From: ", fonts[2]));
			from.add( new Phrase(startD, fonts[3]));
			PdfPCell fromCell = new PdfPCell( from ); 
			fromCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell( fromCell );
			
			Paragraph to = new Paragraph();
			to.add( new Phrase("To: ", fonts[2]));
			to.add( new Phrase(startD, fonts[3]));
			PdfPCell toCell = new PdfPCell( to ); 
			toCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell(toCell);
			
			Paragraph time = new Paragraph();
			time.add( new Phrase("Time: ", fonts[2]));
			time.add( new Phrase(startT, fonts[3]));
			PdfPCell timeCell = new PdfPCell( time );
			timeCell.setLeading(14f,0);
			timeCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell(timeCell);
					
		} else if( edate.getDateType() == 4 ) {
			Paragraph timepar = new Paragraph();
			timepar.setLeading(16f);
			timepar.add( new Phrase("Time:\n", fonts[2]));
			Paragraph startpar = new Paragraph();
			timepar.setLeading(16f);
			startpar.add( new Phrase("From:\n", fonts[2]));
			Paragraph endpar = new Paragraph();
			endpar.setLeading(16f);
			endpar.add( new Phrase("To:\n", fonts[2]));
						
			Iterator evIter = edates.iterator();
			while( evIter.hasNext() ) {
				EventDate evdate = (EventDate)evIter.next();
				String startD = endDFormat.format(startDFormat.parse(evdate.getBegDate()));
				String endD = endDFormat.format(startDFormat.parse(evdate.getEndDate()));
				
				timepar.add( new Phrase("     " + evdate.getTimeStr() + "\n", fonts[3]));
				startpar.add( new Phrase("     " + startD + "\n", fonts[3]));
				endpar.add( new Phrase("     " + endD + "\n", fonts[3]));
			}
			
			PdfPCell fromCell = new PdfPCell( startpar); 
			fromCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell( fromCell );
			
			PdfPCell toCell = new PdfPCell( endpar ); 
			toCell.setBorder(PdfPCell.NO_BORDER);
			table.addCell(toCell);
			
			PdfPCell timeCell = new PdfPCell( timepar ); 
			timeCell.setBorder(PdfPCell.NO_BORDER);
			timeCell.setLeading(14f,0);
			table.addCell(timeCell);
		}
				
		table.setWidthPercentage(100);
		eventCell.addElement(table);

		PdfPTable table2 = new PdfPTable(3);
		table2.setSpacingAfter(5f);
		table2.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
		
		PdfPTable locTable = new PdfPTable(1);
		locTable.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
		
		Paragraph location = new Paragraph("Location:\n", fonts[2]);
		PdfPCell cellLoc = new PdfPCell( location ); 
		cellLoc.setBorder(PdfPCell.NO_BORDER);
		locTable.addCell(cellLoc);
					
		StringBuffer addr = new StringBuffer();
		addr.append(evt.getLocation());
		addr.append("\n");
		addr.append(evt.getAddress1());
		addr.append("\n");
		String address2 = evt.getAddress2();
		if( (address2 != null) && (address2.length() > 0)  ) {
			addr.append(evt.getAddress2());
			addr.append("\n");
		}
		addr.append(evt.getCity());
		addr.append(", ");
		addr.append(evt.getState());
		addr.append(" ");
		addr.append(evt.getZipCode());
		addr.append("\n");
		Paragraph address = new Paragraph(addr.toString(), fonts[3]);
		PdfPCell cellAddr = new PdfPCell( address ); 
		cellAddr.setLeading(14f,0);
		cellAddr.setBorder(PdfPCell.NO_BORDER);
		locTable.addCell(cellAddr);
								
		PdfPCell cellFirst = new PdfPCell( locTable ); 
		cellFirst.setBorder(PdfPCell.NO_BORDER);
		table2.addCell(cellFirst);
						
		PdfPTable urlTable = new PdfPTable(1);
		urlTable.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
		
		if( evt.getUrl() != null ) {
			Paragraph url = new Paragraph("URL:\n", fonts[2]);
			PdfPCell cellUrl = new PdfPCell( url ); 
			cellUrl.setBorder(PdfPCell.NO_BORDER);
			urlTable.addCell(cellUrl);
				
			Anchor anchor = new Anchor(evt.getUrl(), fonts[3]);
			anchor.setReference("http://" + evt.getUrl());
			PdfPCell cellAnchor = new PdfPCell( anchor ); 
			cellAnchor.setLeading(14f,0);
			cellAnchor.setBorder(PdfPCell.NO_BORDER);
			urlTable.addCell(cellAnchor);
		}
		
		PdfPCell cellMiddle = new PdfPCell( urlTable ); 
		cellMiddle.setBorder(PdfPCell.NO_BORDER);
		table2.addCell(cellMiddle);
					
		PdfPTable infoTable = new PdfPTable(1);
		infoTable.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
				
		if( evt.getPhone() != null ) {
			Paragraph info = new Paragraph();
			info.add( new Phrase("TelePhone: ", fonts[2]));
			info.add( new Phrase(evt.getPhone() + "\n", fonts[3]));
			PdfPCell cellInfo = new PdfPCell( info ); 
			cellInfo.setLeading(14f,0);
			cellInfo.setBorder(PdfPCell.NO_BORDER);
			infoTable.addCell(cellInfo);
		}
		
		if( evt.getCost() != null ) {
			Paragraph cost = new Paragraph();
			cost.add( new Phrase("Cost: ", fonts[2]));
			cost.add( new Phrase(evt.getCost(), fonts[3]));
			PdfPCell cellCost = new PdfPCell( cost );
			cellCost.setLeading(14f,0);
			cellCost.setBorder(PdfPCell.NO_BORDER);
			infoTable.addCell(cellCost);
		}
						
		PdfPCell cellSecond = new PdfPCell( infoTable ); 
		cellSecond.setBorder(PdfPCell.NO_BORDER);
		table2.addCell(cellSecond);
		
		table2.setWidthPercentage(100);
		eventCell.addElement(table2);
								
		Paragraph par7 = new Paragraph();
		par7.setLeading(14f);
		par7.setSpacingAfter(5f);
		par7.add( new Phrase("Description: ", fonts[2]));
		
		StringBuffer buffer = new StringBuffer();
		if( evt.getDescription().length() > 325 ) {
			StringBuffer tempBuffer = new StringBuffer(evt.getDescription().substring(0,325));
			int periodIndex = tempBuffer.lastIndexOf(".") + 1;
			buffer.append(tempBuffer.substring(0,periodIndex));
		} else {
			buffer.append(evt.getDescription());
		}
				
		par7.add(new Phrase(buffer.toString(), fonts[3]));
		eventCell.addElement(par7);
		return eventCell;
	}

    public String breakName(String name) {
    	boolean done = false;
    	StringBuffer outName = new StringBuffer();
    	for(int x = 0; x < name.length(); x++) {
    		if( !done && (x > 40) ) {
    			if( name.charAt(x) == ' ' ) {
    				outName.append("\n");
    				done = true;
    			} else {
    				outName.append(name.charAt(x));
    			}
    		} else {
    			outName.append(name.charAt(x));
    		}
    	}
    	return outName.toString();
    }
    	
    /**
     * Return the email address to send exception messages to.
     * @return Email address for exception messages
     */
    public String getExceptionTo() {
    	return EXCEPTION_EMAIL_TO;
    }
    
    /**
     * Return the email address that exception messages are from.
     * @return Email address exception messages are sent from
     */
    public String getExceptionFrom() {
    	return EXCEPTION_EMAIL_FROM;
    }
  
}
