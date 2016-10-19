package com.bettina;

import javax.servlet.*;

/**
 * An AuthenticatedUser object is stored in the user's session after
 * they have successfully been validated.  Then the object is used to
 * check to see if they are still validated.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class AuthenticatedUser {

	private boolean authenticated = false;
	private String uid = null;
	
	/**
	 * Construct an AuthenticatedUser object.
	 * @param context Servlet context for logging
	 * @param network Bettina Network class
	 * @param uid The user's id
	 * @param password The user's password
	 */
	public AuthenticatedUser(ServletContext context, BettinaNetwork network, String uid, String password) {
		if( (uid == null) || (password == null) ) {
			return;
		}
		
		try {
			User user = network.getUserByName(context, uid);
			if( user != null ) {
				if( user.getPassword().equals(password) ) {
					authenticated = true;
					this.uid = uid;
				}
			}
				
		} catch(Throwable except) {
			context.log("Exception while authenticating " + uid, except);
			authenticated = false;
		}
	}
	
	/**
	 * Return information about whether or not the user is authenticated.
	 * @return True if the user is authenticated; otherwise false
	 */
	public boolean isAuthenticated() {
		return this.authenticated;
	}
	
	/**
	 * Retrieve the user's name
	 * @return The user's name
	 */
	public String getUserName() {
		return new String(this.uid);
	}
	
}
