package com.bettina;

import com.birchweb.dbpool.ConnectionPool;
import com.birchweb.util.BirchMail;
import com.lowagie.text.Document;
import com.lowagie.text.DocumentException;
import com.lowagie.text.Element;
import com.lowagie.text.Font;
import com.lowagie.text.Image;
import com.lowagie.text.PageSize;
import com.lowagie.text.Paragraph;
import com.lowagie.text.Phrase;
import com.lowagie.text.pdf.BaseFont;
import com.lowagie.text.pdf.PdfWriter;

import java.io.FileOutputStream;
import java.io.IOException;
import java.sql.*;
import java.util.*;
import javax.servlet.*;

/**
 * An Administration object is the controller for the administrator view
 * of the Bettina Network Administration site. It provides the JSPs with
 * operations on the underlying database.  It also provides helper methods
 * to keep the presentation layer cleaner.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class Administration {

	//Time values for sleeping
	private static final long SIX_HOURS = 21600000;
	
	//Properties file
	private String PDF_PROPS = "com.bettina.pdf";
			
	//Admin Visitors
	private static final String SELECT_LAST_ADMIN_VISITOR="select access from adminvisitors order by access asc";
	private static final String UPDATE_ADMIN_VISITOR="update adminvisitors set access=?";
		
	//Web Visitors
	private static final String SELECT_LAST_WEB_VISITOR="select access from webvisitors order by access asc";
	
	//Site Status
    private static final String CHANGE_AVAILABILITY="update sitestatus set updown=? where id=?";
    private static final String SELECT_AVAILABILITY="select updown from sitestatus where id=?";

    //Category
    private static final String ADD_CATEGORY="insert into category (name,description) values(?,?)";
    private static final String GET_CATEGORY="select id, name, description, referenced from category where id=?";
    private static final String GET_CATEGORY_BY_NAME = "select id, name, description, referenced from category where name=?";
    private static final String GET_CATEGORIES="select id, name, description, referenced from category order by name asc";
    private static final String DELETE_CATEGORY="delete from category where id=?";
    private static final String UPDATE_CATEGORY="update category set name=?,description=? where id=?";
        
    //User
    private static final String GET_USERS="select id, first, last, name, arrival, departure, city, state from user order by last asc";
    private static final String GET_USER="select id, first, last, name, password, email, arrival, departure, city, state from user where id=?";
    private static final String GET_USER_BY_NAME="select id, first, last, name, password, email, arrival, departure, city, state from user where name=?";
    private static final String ADD_USER="insert into user (first,last,name,password,email,arrival,departure,city,state) values(?,?,?,?,?,?,?,?,?)";
    private static final String UPDATE_USER="update user set first=?,last=?,name=?,password=?,email=?,arrival=?,departure=?,city=?,state=? where id=?";
    private static final String DELETE_USER="delete from user where id=?";
    private static final String GET_ARRIVALS="select id, first, last, name, arrival, departure, city, state from user where arrival >=? and arrival <= ?";
    private static final String DELETE_OLD="delete from user where departure < CURDATE()";    
    
    //Event
    private static final String ADD_EVENT="insert into event (name,location,address1,address2,city,state,zipcode,url,phone,cost,description,category) values(?,?,?,?,?,?,?,?,?,?,?,?)";
    private static final String GET_EXPIRED_EVENTS="select DISTINCT a.id,name,location,address1,address2,city,state,zipcode,url,phone,cost,description,category from event a, eventdate b where a.id = b.eventid and b.enddate < CURDATE() order by name asc";
    private static final String GET_NOT_EXPIRED_EVENTS="select DISTINCT a.id,name,location,address1,address2,city,state,zipcode,url,phone,cost,description,category from event a, eventdate b where a.id = b.eventid and b.enddate >= CURDATE() order by name asc";
    private static final String GET_EVENT="select id,name,location,address1,address2,city,state,zipcode,url,phone,cost,description,category from event where id=?";
	private static final String UPDATE_EVENT="update event set name=?,location=?,address1=?,address2=?,city=?,state=?,zipcode=?,url=?,phone=?,cost=?,description=?,category=? where id=?";
    private static final String DELETE_EVENT="delete from event where id=?";
    private static final String SELECT_SPEC_EVENT="select id from event where name=? order by id desc, name asc";
    private static final String SELECT_EVENTS_WITH_CAT="select id from event where category=?";
    private static final String GET_EVENT_BY_NAME="select id from event where lower(name)=?";
    
    //Event Dates
    private static final String ADD_EVENTDATE="insert into eventdate (eventid,datetype,begdate,enddate,timestr) values(?,?,?,?,?)";
    private static final String GET_EVENTDATES="select id, eventid, datetype, begdate, enddate, timestr from eventdate where eventid=? order by begdate asc";
    private static final String UPDATE_EVENTD="update eventdate set begdate=?,enddate=?,timestr=? where id=?";
    private static final String DELETE_EVENTDATE="delete from eventdate where id=?";
    private static final String DELETE_EVENTDATE_EVENT="delete from eventdate where eventid=?";
    
    //Hours
    private static final String ADD_HOURS="insert into hours (eventid,day,closed,timestr) values(?,?,?,?)";
    private static final String GET_HOURS="select id, eventid, day, closed, timestr from hours where eventid=? order by day asc";
    private static final String UPDATE_HOURS="update hours set closed=?, timestr=? where id=?";
    private static final String DELETE_HOURS="delete from hours where eventid=?";
    
    //Location
    private static final String GET_LOCATIONS="select id, location, address1, address2, city, state, zipcode, url, phone, category from Location order by location asc";
    private static final String ADD_LOCATION="insert into Location (location,address1,address2,city,state,zipcode,url,phone,category) values (?,?,?,?,?,?,?,?,?)";
    private static final String GET_LOCATION_BY_NAME="select id from Location where lower(location)=?";
    private static final String GET_LOCATION="select id, location, address1, address2, city, state, zipcode, url, phone, category from Location where id=?";
    private static final String DELETE_LOCATION="delete from Location where id=?";
    private static final String UPDATE_LOCATION="update Location set location=?,address1=?,address2=?,city=?,state=?,zipcode=?,url=?,phone=? where id=?";
    
    //Mail Configuration
    private static final String GET_MAIL="select content1, greetings, subject, efrom from mailconfig";
    private static final String UPDATE_MAIL="update mailconfig set content1=?, greetings=?, subject=?, efrom=?";
    
    //Cambrige Address
    private static final String GET_CAMBRIDGE="select address1, address2, city, state, zipcode, phone1, phone2, fax, url from cambridge";
    private static final String UPDATE_CAMBRIDGE="update cambridge set address1=?, address2=?, city=?, state=?, zipcode=?, phone1=?, phone2=?, fax=?, url=?";
    
    //Pdf First Page
    private static final String GET_FIRSTPAGE="select welcome, par1, par2, par3, par4, par5, par6 from pdf";
    private static final String UPDATE_FIRSTPAGE="update pdf set welcome=?, par1=?, par2=?, par3=?, par4=?, par5=?, par6=?";
    
    //Email information
    private String EXCEPTION_EMAIL_TO="tomcat1@bettina-network.com";
	private String EXCEPTION_EMAIL_FROM="info@bettina-network.com";
    
    String POOL_CFG_FILE = "com.bettina.AdminPool";
    ConnectionPool pool = null;
    
    private String pdfFolder = "";
    private String imagesFolder = "";
    
    /**
     * Create an instance of Administration.
     * @throws Exception
     * 		Failed to initialize the connection pool
     */
    public Administration() throws Exception {
    	try {
        	this.pool = new ConnectionPool();
            this.pool.initialize(POOL_CFG_FILE);
        } catch( Throwable except) {
            System.out.println("Failed to create Administration object, exception: " + except.getMessage());
            throw except;
        }
                
        Thread monitorThread = new Thread(new UserAccountMonitor());
        monitorThread.start();
        
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
    	int numConnections = this.pool.getNumConnections();    
        return numConnections;
    }
        
    /**
     * Retrieve the date & time stamp of when the Administrator
     * last accessed their site.
     * @param context Context for logging messages
     * @return Date & time stamp in yyyy-MM-dd HH:mm:ss format
     * @throws SQLException
     *      Failed while accessing the database
     */
    public String getLastAdminVisitor(ServletContext context) throws SQLException {
    	Connection conn = null;
    	Statement stmt = null;
    	ResultSet rs = null;
        String lastaccessed = "";
        
        try {        	
            conn = this.pool.getConnection();
            if( conn == null ) {
            	return lastaccessed;
            }
            stmt = conn.createStatement();
            rs = stmt.executeQuery(SELECT_LAST_ADMIN_VISITOR);
            while( rs.next() ) {
                lastaccessed = rs.getString(1);
            }
            stmt.close();
            rs.close();
        } catch( Throwable sExcept ) {
        	context.log("Failed in getLastAdminVisitor", sExcept);
            throw sExcept;
        } finally {
        	try { if( stmt != null) stmt.close(); } catch(SQLException ex) {}
        	try { if( rs != null) rs.close(); } catch(SQLException ex) {}
        	if( conn != null ) { this.pool.close(conn); }
        }
        return lastaccessed;
    }
    
    /**
     * Update the date/time that the Administrator last visited
     * the Administration site.
     * @param context The Servlet context, used for logging
     * @throws SQLException
     *      Failed while accessing the database		
     */
    public void updateAdminVisitor(ServletContext context) throws SQLException {
        Connection conn = null;
        PreparedStatement pstmt = null;
        
        try {
        	conn = this.pool.getConnection();
            if( conn == null ) {
            	return;
            }
        	pstmt = conn.prepareStatement(UPDATE_ADMIN_VISITOR);
            pstmt.setString(1, this.getCurrentDate());
            pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
            context.log("Failed in updateAdminVisitor", sExcept);
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
     * Retrieve the date & time stamp of when a customer last
     * the web site.
     * @param context Context for logging messages
     * @return Date & time stamp in yyyy-MM-dd HH:mm:ss format
     * @throws SQLException
     *		Failed while accessing the database
     */
    public String getLastWebVisitor(ServletContext context) throws SQLException {
    	Connection conn = null;
    	Statement stmt = null;
    	ResultSet rs = null;
        String lastaccessed = "";
        
        try {        	
            conn = this.pool.getConnection();
            if( conn == null ) {
            	return lastaccessed;
            }
            stmt = conn.createStatement();
            rs = stmt.executeQuery(SELECT_LAST_WEB_VISITOR);
            while( rs.next() ) {
                lastaccessed = rs.getString(1);
            }
            stmt.close();
            rs.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in getLastWebVisitor", sExcept);
            throw sExcept;
        } finally {
        	try { if( stmt != null) stmt.close(); } catch(SQLException ex) {}
        	try { if( rs != null) rs.close(); } catch(SQLException ex) {}
        	if( conn != null ) { this.pool.close(conn); }
        }
        return lastaccessed;
    }
    
    /**
     * Make a website unavailable for viewing.
     * @param context The servlet context
     * @param id The id of the website to shutdown
     * @throws SQLException
     *      Failed while accessing the database
     */
    public void stopAccess(ServletContext context, int id) throws SQLException {
        Connection conn = null;
        PreparedStatement pstmt = null;
        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(CHANGE_AVAILABILITY);
            pstmt.setInt(1, 0);
            pstmt.setInt(2, id);
            pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
            context.log("Failed in stopAccess", sExcept);
            throw sExcept;
        } finally {
        	try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
        	if( conn != null ) { this.pool.close(conn); }
        }
    }
   
    /**
     * Make a website available for viewing.
     * @param context The servlet context
     * @param id The id of the website to start
     * @throws SQLException
     *      Failed while accessing database
     */
    public void startAccess(ServletContext context, int id) throws SQLException {
        Connection conn = null;
        PreparedStatement pstmt = null;
        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(CHANGE_AVAILABILITY);
            pstmt.setInt(1, 1);
            pstmt.setInt(2, id);
            pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in startAccess", sExcept);
            throw sExcept;
        } finally {
        	try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
        	if( conn != null ) { this.pool.close(conn); }
        }
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
       } catch( Throwable sExcept ) {
    	   context.log("Failed in isSiteAvailable", sExcept);
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
     * Add the category to the Category table.
     * @param context The servlet context
     * @param name The category name
     * @param description The category description
     * @throws SQLException
     * 		Failed while accessing database
     */
    public void addCategory(ServletContext context, String name, String description) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(ADD_CATEGORY);
            pstmt.setString(1, name);
            if( (description == null) || (description.trim().length() == 0) ) {
                pstmt.setNull(2, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(2, description);
            }
            pstmt.execute();
            pstmt.close();
        } catch( SQLException sExcept ) {
     	   context.log("Failed in addCategory", sExcept);
     	   throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
    }
    
    /**
     * Retrieve the Category object given the category id.
     * @param context The servlet context for logging
     * @param id The category id
     * @return A Category object
     * @throws SQLException
     * 		Failed while accessing the database
     */
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
        	context.log("Failed in getCategory", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Retrieve the Category object given the category name.
     * @param context Servlet context
     * @param name category name
     * @return Category object
     * @throws SQLException
     * 		Failed while accessing database
     */
    public Category getCategoryByName(ServletContext context, String name) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_CATEGORY_BY_NAME);
            pstmt.setString(1, name);
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
        	context.log("Failed in getCategoryByName", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        
        return null;
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
        	context.log("Failed in getCategories", sExcept);
            throw sExcept;
        } finally {
        	try { if( stmt != null) stmt.close(); } catch(SQLException ex) {}
        	try { if( rs != null) rs.close(); } catch(SQLException ex) {}
        	if( conn != null ) { this.pool.close(conn); }
        }
        return categories;
    }
    
    /**
     * Delete the category with the given id from the database.
     * @param context The servlet context for logging
     * @param id The id of the category to delete
     * @return True if the category was deleted; otherwise false
     * @throws SQLException
     * 		Failed while accessing database
     */
    public boolean deleteCategory(ServletContext context, int id) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(DELETE_CATEGORY);
            pstmt.setInt(1, id);
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in deleteCategory", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
    
    /**
     * Update the given Category in the database.
     * @param context Servlet context for logging
     * @param cat Category to update in database
     * @return True if the update was successful; otherwise false
     * @throws SQLException
     * 		Failed while accessing database
     */
    public boolean updateCategory(ServletContext context, Category cat) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(UPDATE_CATEGORY);
            pstmt.setString(1, cat.getName());
            if( (cat.getDescription() == null) || (cat.getDescription().trim().length() == 0) ) {
                pstmt.setNull(2, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(2, cat.getDescription());
            }
            pstmt.setInt(3, cat.getId());
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in updateCategory", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
        
    /**
     * Retrieve the search users.
     * @param context The servlet context
     * @return Vector of User objects
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getUsers(ServletContext context) throws SQLException {
    	Connection conn = null;
    	Statement stmt = null;
    	ResultSet rs = null;
        Vector users = new Vector();
        
        try {        	
            conn = this.pool.getConnection();
            stmt = conn.createStatement();
            rs = stmt.executeQuery(GET_USERS);
            while( rs.next() ) {
            	User user = new User();
                user.setId(rs.getInt(1));
                user.setFirst(rs.getString(2));
                user.setLast(rs.getString(3));
                user.setName(rs.getString(4));
                user.setArrival(rs.getString(5));
                user.setDeparture(rs.getString(6));
                user.setCity(rs.getString(7));
                user.setState(rs.getString(8));
                users.add(user);
            }
            stmt.close();
            rs.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in getUsers", sExcept);
            throw sExcept;
        } finally {
        	try { if( stmt != null) stmt.close(); } catch(SQLException ex) {}
        	try { if( rs != null) rs.close(); } catch(SQLException ex) {}
        	if( conn != null ) { this.pool.close(conn); }
        }
        return users;
    }
    
    /**
     * Retrieve the User associated with the given id.
     * @param context The servlet context
     * @param id The user id
     * @return A User object; otherwise null if no one with that id
     * @throws SQLException
     * 		Failed while accessing the database 
     */
    public User getUser(ServletContext context, int id) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_USER);
            pstmt.setInt(1, id);
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
        	context.log("Failed in getUser", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Add the User to the database.
     * @param context The servlet context
     * @param user User to add
     * @throws SQLException
     * 		Failed while accessing the database 
     */
    public void addUser(ServletContext context, User user) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(ADD_USER);
            pstmt.setString(1, user.getFirst());
            pstmt.setString(2, user.getLast());
            pstmt.setString(3, user.getName());
            pstmt.setString(4, user.getPassword());
            pstmt.setString(5, user.getEmail());
            pstmt.setString(6, user.getArrival());
            pstmt.setString(7, user.getDeparture());
            pstmt.setString(8, user.getCity());
            pstmt.setString(9, user.getState());
            pstmt.execute();
            pstmt.close();
        } catch( SQLException sExcept ) {
     	   context.log("Failed in addUser", sExcept);
     	   throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
    }
    
    /**
     * Delete the user from the database.
     * @param context The servlet context
     * @param id The id of the user to delete
     * @return True if the user was deleted; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean deleteUser(ServletContext context, int id) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(DELETE_USER);
            pstmt.setInt(1, id);
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in deleteUser", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
    
    /**
     * Delete the user accounts where their departure date is after the current date.
     * @param context Servlet Context
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public void deleteOldUsers() throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
                            
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(DELETE_OLD);
            pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
    }
    
    /**
     * Update the user in the database.
     * @param context The servlet context
     * @param user User to update
     * @return True if the user was updated; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database 
     */
    public boolean updateUser(ServletContext context, User user) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(UPDATE_USER);
            pstmt.setString(1, user.getFirst());
            pstmt.setString(2, user.getLast());
            pstmt.setString(3, user.getName());
            pstmt.setString(4, user.getPassword());
            pstmt.setString(5, user.getEmail());
            pstmt.setString(6, user.getArrival());
            pstmt.setString(7, user.getDeparture());
            pstmt.setString(8, user.getCity());
            pstmt.setString(9, user.getState());
            pstmt.setInt(10, user.getId());
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in updateUser", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
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
        	context.log("Failed in getUserByName", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Retrieve the User records where the arrival date is between the given dates.
     * @param context Servlet Context
     * @param begDate Beginning date to search for arrivals
     * @param endDate End date to search for arrivals
     * @return Vector of User objects that fall within the date range
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getUserArrivalsBetween(ServletContext context, String begDate, String endDate) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector areas = new Vector();
        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_ARRIVALS);
            pstmt.setString(1, begDate);
            pstmt.setString(2, endDate);
            rs = pstmt.executeQuery();
            while( rs.next() ) {
            	User user = new User();
            	user.setId(rs.getInt(1));
            	user.setFirst(rs.getString(2));
            	user.setLast(rs.getString(3));
                user.setName(rs.getString(4));
                user.setArrival(rs.getString(5));
                user.setDeparture(rs.getString(6));
                user.setCity(rs.getString(7));
                user.setState(rs.getString(8));
                areas.add(user);
            }
            pstmt.close();
            rs.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in getUserArrivalsBetween", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return areas;
    }
            
    /**
     * Add an Event to the database.  This does not include the event
     * date/time information.
     * @param context Servlet Context
     * @param event Event to add
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public void addEvent(ServletContext context, Event event) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(ADD_EVENT);
            pstmt.setString(1, event.getName());
            pstmt.setString(2, event.getLocation());
            pstmt.setString(3, event.getAddress1());
            if( (event.getAddress2() == null) || (event.getAddress2().trim().length() == 0) ) {
                pstmt.setNull(4, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(4, event.getAddress2());
            }
            pstmt.setString(5, event.getCity());
            pstmt.setString(6, event.getState());
            if( (event.getZipCode() == null) || (event.getZipCode().trim().length() == 0) ) {
            	pstmt.setNull(7, java.sql.Types.VARCHAR);
            } else {
            	pstmt.setString(7, event.getZipCode());
            }
            if( (event.getUrl() == null) || (event.getUrl().trim().length() == 0) ) {
                pstmt.setNull(8, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(8, event.getUrl());
            }
            if( (event.getPhone() == null) || (event.getPhone().trim().length() == 0) ) {
                pstmt.setNull(9, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(9, event.getPhone());
            }
            if( (event.getCost() == null) || (event.getCost().trim().length() == 0) ) {
                pstmt.setNull(10, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(10, event.getCost());
            }
            if( (event.getDescription() == null) || (event.getDescription().trim().length() == 0) ) {
                pstmt.setNull(11, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(11, event.getDescription());
            }
            pstmt.setInt(12, event.getCategory());
                        
            pstmt.execute();
            pstmt.close();
        } catch( SQLException sExcept ) {
     	   context.log("Failed in addEvent", sExcept);
     	   throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
    }
                
    /**
     * Retrieve all the expired events.
     * @param context Servlet context
     * @return Vector of Event objects
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getExpiredEvents(ServletContext context) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector events = new Vector();
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_EXPIRED_EVENTS);
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
        	context.log("Failed in getExpiredEvents", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return events;
    }
    
    /**
     * Retrieve all the non-expired events.
     * @param context Servlet context
     * @return Vector of Event objects
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getEvents(ServletContext context) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector events = new Vector();
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_NOT_EXPIRED_EVENTS);
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
        	context.log("Failed in getEvents", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return events;
    }
    
    /**
     * Retrieve an event given an event id.
     * @param context Servlet context
     * @param id Event id
     * @return Event
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
        	context.log("Failed in getEvent", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Check to see if an event exists with the given name;
     * @param context Servlet context for logging
     * @param name Event name to look for
     * @return Event object if one exists; otherwise null
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Event getEventByName(ServletContext context, String name) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_EVENT_BY_NAME);
            pstmt.setString(1, name);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	Event event = new Event();
            	event.setId(rs.getInt(1));
            	rs.close();
            	pstmt.close();
            	return event;
            }
        } catch( SQLException sExcept ) {
        	context.log("Failed in getEventByName", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Retrieve the id of the event with the given name, and was last added to
     * the database.
     * @param context Servlet context
     * @param name Event name
     * @return Event id
     * @throws SQLException
     * 		Failed while accessing the database.
     */
    public int getEventId(ServletContext context, String name) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        int id = 0;
        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(SELECT_SPEC_EVENT);
            pstmt.setString(1, name);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	id = rs.getInt(1);
            }
            rs.close();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in getEventId", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return id;
    }
    
    /**
     * Retrieve the events that reference the given category.
     * @param context The servlet context
     * @param category The category to search events for
     * @return Vector of Events with the given category
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getEventsByCategory(ServletContext context, int category) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector events = new Vector();
        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(SELECT_EVENTS_WITH_CAT);
            pstmt.setInt(1, category);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	Event event = new Event();
            	event.setId( rs.getInt(1));
            	events.add(event);
            }
            rs.close();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in getEventsByCategory", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return events;
    	
    }
    
    /**
     * Update an event stored in the database.
     * @param context Servlet context
     * @param event Event id
     * @return True if the event was updated; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean updateEvent(ServletContext context, Event event) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(UPDATE_EVENT);
            pstmt.setString(1, event.getName());
            pstmt.setString(2, event.getLocation());
            pstmt.setString(3, event.getAddress1());
            if( (event.getAddress2() == null) || (event.getAddress2().trim().length() == 0) ) {
                pstmt.setNull(4, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(4, event.getAddress2());
            }
            pstmt.setString(5, event.getCity());
            pstmt.setString(6, event.getState());
            if( (event.getZipCode() == null) || (event.getZipCode().trim().length() == 0) ) {
                pstmt.setNull(7, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(7, event.getZipCode());
            }
            if( (event.getUrl() == null) || (event.getUrl().trim().length() == 0) ) {
                pstmt.setNull(8, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(8, event.getUrl());
            }
            if( (event.getPhone() == null) || (event.getPhone().trim().length() == 0) ) {
                pstmt.setNull(9, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(9, event.getPhone());
            }
            if( (event.getCost() == null) || (event.getCost().trim().length() == 0) ) {
                pstmt.setNull(10, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(10, event.getCost());
            }
            if( (event.getDescription() == null) || (event.getDescription().trim().length() == 0) ) {
                pstmt.setNull(11, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(11, event.getDescription());
            }
            pstmt.setInt(12, event.getCategory());
            pstmt.setInt(13, event.getId());
            
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in updateEvent", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
    
    /**
     * Delete the event from the database
     * @param context Servlet context
     * @param id Event id
     * @return True if the event was deleted; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean deleteEvent(ServletContext context, int id) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(DELETE_EVENT);
            pstmt.setInt(1, id);
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in deleteEvent", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }

    /**
     * Add an EventDate record for an event.
     * @param context Servlet context
     * @param eventdate Event date to insert
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public void addEventDate(ServletContext context, EventDate eventdate) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(ADD_EVENTDATE);
            pstmt.setInt(1, eventdate.getEventId());
            pstmt.setInt(2, eventdate.getDateType());
            pstmt.setString(3, eventdate.getBegDate());
            pstmt.setString(4, eventdate.getEndDate());
            if( (eventdate.getTimeStr() == null) || (eventdate.getTimeStr().trim().length() == 0) ) {
                pstmt.setNull(5, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(5, eventdate.getTimeStr());
            }
            pstmt.execute();
            pstmt.close();
        } catch( SQLException sExcept ) {
     	   context.log("Failed in addEventDate", sExcept);
     	   throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
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
        	context.log("Failed in getEventDates", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return eventdates;
    }
    
    /**
     * Update an event date.
     * @param context Servlet context
     * @param eventdate Event date to update
     * @return True if it updated successfully; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean updateEventDate(ServletContext context, EventDate eventdate) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(UPDATE_EVENTD);
            pstmt.setString(1, eventdate.getBegDate());
            pstmt.setString(2, eventdate.getEndDate());
            if( (eventdate.getTimeStr() == null) || (eventdate.getTimeStr().trim().length() == 0) ) {
                pstmt.setNull(3, Types.VARCHAR);
            } else {
                pstmt.setString(3, eventdate.getTimeStr());
            }
            pstmt.setInt(4, eventdate.getId());
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in updateEventDate", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
   
    /**
     * Delete an event date given an event date id.
     * @param context Servlet context
     * @param id Event date id
     * @return True if the event date was deleted; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean deleteSpecificEventDate(ServletContext context, int id) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
       
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(DELETE_EVENTDATE);
            pstmt.setInt(1, id);
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in deleteSpecificEventDate", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
    
    /**
     * Delete all event dates associated with the given event id.
     * @param context Servlet context
     * @param eventId Event id
     * @return True if the event dates where deleted; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean deleteEventDate(ServletContext context, int eventId) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
     
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(DELETE_EVENTDATE_EVENT);
            pstmt.setInt(1, eventId);
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in deleteEventDat", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
        
    /**
     * Add the hours for a specific event.
     * @param context Servlet context
     * @param hour Event hour information
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public void addHours(ServletContext context, EventHour hour) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(ADD_HOURS);
            pstmt.setInt(1, hour.getEventId());
            pstmt.setInt(2, hour.getDay());
            pstmt.setInt(3, hour.getClosed());
            if( (hour.getTimeStr() == null) || (hour.getTimeStr().trim().length() == 0) ) {
                pstmt.setNull(4, Types.VARCHAR);
            } else {
                pstmt.setString(4, hour.getTimeStr());
            }
            pstmt.execute();
            pstmt.close();
        } catch( SQLException sExcept ) {
     	   context.log("Failed in addHours", sExcept);
     	   throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
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
        	context.log("Failed in getHours", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return hours;
    }
    
    /**
     * Update the hours for an event
     * @param context Servlet context
     * @param hours Event hours
     * @return True if the hours where updated; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean updateHours(ServletContext context, EventHour hours) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(UPDATE_HOURS);
            pstmt.setInt(1, hours.getClosed());
            
            if( (hours.getTimeStr() == null) || (hours.getTimeStr().trim().length() == 0) ) {
                pstmt.setNull(2, Types.VARCHAR);
            } else {
                pstmt.setString(2, hours.getTimeStr());
            }
            pstmt.setInt(3, hours.getId());
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in updateHours", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
    
    /**
     * Delete the hours associated with an event.
     * @param context Servlet context
     * @param eventId Event id
     * @return True if the hours were deleted; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean deleteHours(ServletContext context, int eventId) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
            
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(DELETE_HOURS);
            pstmt.setInt(1, eventId);
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in deleteHours", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
    
    /**
     * Update the mail configuration.
     * @param context Servlet context
     * @param mailconfig Mail configuration information
     * @return True if the mail configuration was updated; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean updateMail(ServletContext context, MailConfig mailconfig) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(UPDATE_MAIL);
            pstmt.setString(1, mailconfig.getContent1());
            pstmt.setString(2, mailconfig.getGreetings());
            pstmt.setString(3, mailconfig.getSubject());
            pstmt.setString(4, mailconfig.getFrom());
            
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in updateMail", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
    
    /**
     * Retrieve the mal configuration information.
     * @param context Servlet context
     * @return The current mail configuration
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public MailConfig getMail(ServletContext context) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_MAIL);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	MailConfig mail = new MailConfig();
            	mail.setContent1(rs.getString(1));
                mail.setGreetings(rs.getString(2));
                mail.setSubject(rs.getString(3));
                mail.setFrom(rs.getString(4));
                pstmt.close();
                rs.close();
                return mail;
            }
        } catch( SQLException sExcept ) {
        	context.log("Failed in getMail", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
        
    /**
     * Retrieve the Cambridge address.
     * @param context Servlet context
     * @return Cambridge address
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
        	context.log("Failed in getCambridge", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Update the Cambridge address.
     * @param context Servlet context
     * @param address Cambridge address
     * @return True is the update was successful; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean updateCambridge(ServletContext context, Cambridge address) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(UPDATE_CAMBRIDGE);
            pstmt.setString(1, address.getAddress1());
            pstmt.setString(2, address.getAddress2());
            pstmt.setString(3, address.getCity());
            pstmt.setString(4, address.getState());
            pstmt.setString(5, address.getZipCode());
            pstmt.setString(6, address.getPhone1());
            pstmt.setString(7, address.getPhone2());
            pstmt.setString(8, address.getFax());
            pstmt.setString(9, address.getUrl());
            result = pstmt.executeUpdate();
            pstmt.close();
            return true;
        } catch( SQLException sExcept ) {
        	context.log("Failed in updateCambridge", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
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
        	context.log("Failed in getFirstPage", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Update the first page of the PDF
     * @param context Servlet context for logging
     * @param page First page information
     * @return True if the page was updated; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean updateFirstPage(ServletContext context, FirstPage page) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(UPDATE_FIRSTPAGE);
            if( (page.getWelcome() == null) || (page.getWelcome().trim().length() == 0) ) {
                pstmt.setNull(1, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(1, page.getWelcome());
            }
            if( (page.getPar1() == null) || (page.getPar1().trim().length() == 0) ) {
                pstmt.setNull(2, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(2,page.getPar1());
            }
            if( (page.getPar2() == null) || (page.getPar2().trim().length() == 0) ) {
                pstmt.setNull(3, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(3,page.getPar2());
            }
            if( (page.getPar3() == null) || (page.getPar3().trim().length() == 0) ) {
                pstmt.setNull(4, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(4,page.getPar3());
            }
            if( (page.getPar4() == null) || (page.getPar4().trim().length() == 0) ) {
                pstmt.setNull(5, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(5,page.getPar4());
            }
            if( (page.getPar5() == null) || (page.getPar5().trim().length() == 0) ) {
                pstmt.setNull(6, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(6,page.getPar5());
            }
            if( (page.getPar6() == null) || (page.getPar6().trim().length() == 0) ) {
                pstmt.setNull(7, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(7,page.getPar6());
            }
            result = pstmt.executeUpdate();
            pstmt.close();
            return true;
        } catch( SQLException sExcept ) {
        	context.log("Failed in updateFirstPage", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
    }
    
    /**
     * Add a location to the database
     * @param context Servlet context for logging
     * @param location Location to add
     * @throws SQLException
     * 		Failed while accessing the database
     */    
    public void addLocation(ServletContext context, Location location) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(ADD_LOCATION);
            pstmt.setString(1, location.getLocation());
            pstmt.setString(2, location.getAddress1());
            if( (location.getAddress2() == null) || (location.getAddress2().trim().length() == 0) ) {
                pstmt.setNull(3, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(3, location.getAddress2());
            }
            pstmt.setString(4, location.getCity());
            pstmt.setString(5, location.getState());
            if( (location.getZipCode() == null) || (location.getZipCode().trim().length() == 0) ) {
            	pstmt.setNull(6, java.sql.Types.VARCHAR);
            } else {
            	pstmt.setString(6, location.getZipCode());
            }
            if( (location.getUrl() == null) || (location.getUrl().trim().length() == 0) ) {
                pstmt.setNull(7, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(7, location.getUrl());
            }
            if( (location.getPhone() == null) || (location.getPhone().trim().length() == 0) ) {
                pstmt.setNull(8, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(8, location.getPhone());
            }
            pstmt.setInt(9, location.getCategory());
                        
            pstmt.execute();
            pstmt.close();
        } catch( SQLException sExcept ) {
     	   context.log("Failed in addLocation", sExcept);
     	   throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
    }
    
    /**
     * Retrieve a Location given a location name.
     * @param context Servlet context for logging
     * @param location Location 
     * @return Location object; otherwise null
     * @throws SQLException
     *		Failed while accessing the database 
     */
    public Location getLocationByName(ServletContext context, String location) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_LOCATION_BY_NAME);
            pstmt.setString(1, location);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	Location loc = new Location();
            	loc.setId(rs.getInt(1));
            	rs.close();
            	pstmt.close();
            	return loc;
            }
        } catch( SQLException sExcept ) {
        	context.log("Failed in getLocationByName", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Retrieve a location given a location id.
     * @param context Servlet context for logging
     * @param id Location id
     * @return Location object
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Location getLocation(ServletContext context, int id) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
                                
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_LOCATION);
            pstmt.setInt(1, id);
            rs = pstmt.executeQuery();
            if( rs.next() ) {
            	Location loc = new Location();
            	loc.setId(rs.getInt(1));
            	loc.setLocation(rs.getString(2));
            	loc.setAddress1( rs.getString(3));
            	loc.setAddress2(rs.getString(4));
            	loc.setCity(rs.getString(5));
            	loc.setState(rs.getString(6));
            	loc.setZipCode(rs.getString(7));
            	loc.setUrl(rs.getString(8));
            	loc.setPhone(rs.getString(9));
            	loc.setCategory(rs.getInt(10));
            	rs.close();
            	pstmt.close();
            	return loc;
            }
        } catch( SQLException sExcept ) {
        	context.log("Failed in getLocation", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return null;
    }
    
    /**
     * Retrieve all the locations.
     * @param context Servlet context for logging
     * @return Vector of Location objects
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public Vector getLocations(ServletContext context) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        ResultSet rs = null;
        Vector locations = new Vector();
           
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(GET_LOCATIONS);
            rs = pstmt.executeQuery();
            while( rs.next() ) {
            	Location loc = new Location();
            	loc.setId(rs.getInt(1));
            	loc.setLocation(rs.getString(2));
            	loc.setAddress1( rs.getString(3));
            	loc.setAddress2(rs.getString(4));
            	loc.setCity(rs.getString(5));
            	loc.setState(rs.getString(6));
            	loc.setZipCode(rs.getString(7));
            	loc.setUrl(rs.getString(8));
            	loc.setPhone(rs.getString(9));
            	loc.setCategory(rs.getInt(10));
            	locations.add(loc);
            }
            rs.close();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in getLocations", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   try { if( rs != null) rs.close(); } catch(SQLException ex) {}
           if( conn != null ) { this.pool.close(conn); }
        }
        return locations;
    }
    
    /**
     * Delete the location with the given id.
     * @param context Servlet context for logging
     * @param id Location id
     * @return True if the location was deleted; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean deleteLocation(ServletContext context, int id) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(DELETE_LOCATION);
            pstmt.setInt(1, id);
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in deleteLocation", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
    
    /**
     * Update the location
     * @param context Servlet context for logging
     * @param location Location
     * @return True if the location was updated; otherwise false
     * @throws SQLException
     * 		Failed while accessing the database
     */
    public boolean updateLocation(ServletContext context, Location location) throws SQLException {
    	Connection conn = null;
        PreparedStatement pstmt = null;
        int result = 0;
                        
        try {
            conn = this.pool.getConnection();
            pstmt = conn.prepareStatement(UPDATE_LOCATION);
            pstmt.setString(1, location.getLocation());
            pstmt.setString(2, location.getAddress1());
            if( (location.getAddress2() == null) || (location.getAddress2().trim().length() == 0) ) {
                pstmt.setNull(3, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(3, location.getAddress2());
            }
            pstmt.setString(4, location.getCity());
            pstmt.setString(5, location.getState());
            if( (location.getZipCode() == null) || (location.getZipCode().trim().length() == 0) ) {
                pstmt.setNull(6, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(6, location.getZipCode());
            }
            if( (location.getUrl() == null) || (location.getUrl().trim().length() == 0) ) {
                pstmt.setNull(7, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(7, location.getUrl());
            }
            if( (location.getPhone() == null) || (location.getPhone().trim().length() == 0) ) {
                pstmt.setNull(8, java.sql.Types.VARCHAR);
            } else {
                pstmt.setString(8, location.getPhone());
            }
            pstmt.setInt(9, location.getId());
            
            result = pstmt.executeUpdate();
            pstmt.close();
        } catch( SQLException sExcept ) {
        	context.log("Failed in updateLocation", sExcept);
            throw sExcept;
        } finally {
     	   try { if( pstmt != null) pstmt.close(); } catch(SQLException ex) {}
     	   if( conn != null ) { this.pool.close(conn); }
        }
        if( result == 1 ) {
        	return true;
        }
        return false;
    }
               
    /**
     * Retrieve the code version
     * @return Code version
     */
    public String getVersion() {
    	return "1.0.0";    	
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
    
    /**
     * class UserAccountMonitor
     */
    class UserAccountMonitor implements Runnable {
        
        public void run() {
            while(true) {
                try {
                    Thread.sleep(SIX_HOURS);
                    deleteOldUsers();
                } catch(Exception e) {
                	try {
                		BirchMail mailer = new BirchMail("com.bettina.mail");
                		mailer.sendText("Failed to delete old users, message: " + e.getMessage(),"User Account Monitor failure","mike@bettina-network.com","mike_spellman@comcast.net");
                   	} catch(Exception except) {}
                }
            }
        }
             
    }//end UserAccountMonitor

}
