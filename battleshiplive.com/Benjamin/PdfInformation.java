package com.bettina;

import java.util.*;

/**
 * The PdfInformation contains the information that will be used to help create the
 * pdf, and display the page for narrowing down events.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class PdfInformation {

	private String properName;
	private String userName;
	private String city;
	private String state;
	private String begDate;
	private String endDate;
	private Vector events = new Vector();
	
	/**
	 * Construct an instance of PdfInformation.
	 * @param properName The user's proper name
	 * @param userName The user's login name
	 * @param events The events that should be search against
	 */
	public PdfInformation(String properName, String userName, Vector events, String city, String state, String begDate, String endDate) {
		if( properName != null ) {
			this.properName = new String(properName);
		}
		if( userName != null ) {
			this.userName = new String(userName);
		}
		if( city != null ) {
			this.city = new String(city);
		}
		if( state != null ) {
			this.state = new String(state);
		}
		if( begDate != null ) {
			this.begDate = new String(begDate);
		}
		if( endDate != null ) {
			this.endDate = new String(endDate);
		}
				
		if( (events != null) && !events.isEmpty() ) {
			this.events.addAll(events);
		}
	}
	
	/**
	 * Retrieve the user's proper name.
	 * @return The user's proper name
	 */
	public String getProperName() {
		if( this.properName == null ) {
			return "";
		}
		return this.properName;
	}
	
	/**
	 * Retrieve the user name.
	 * @return The user name
	 */
	public String getUserName() {
		if( this.userName == null ) {
			return "temp";
		}
		return this.userName;
	}
	
	/**
	 * Retrieve the city where the user is visiting.
	 * @return City where the user is visiting
	 */
	public String getCity() {
		if( this.city == null ) {
			return "";
		}
		return this.city;
	}
	
	/**
	 * Retrieve the state where the user is visiting.
	 * @return State the user is visiting
	 */
	public String getState() {
		if( this.state == null ) {
			return "";
		}
		return this.state;
	}
	
	/**
	 * Retrieve the start date of the user's visit.
	 * @return Start date of user's visit
	 */
	public String getBegDate() {
		if( this.begDate == null  ) {
			return "";
		}
		return this.begDate;
	}
	
	/**
	 * Retrieve the end date of the user's visit.
	 * @return End date of user's visit
	 */
	public String getEndDate() {
		if( this.endDate == null ) {
			return "";
		}
		return this.endDate;
	}
	
	/**
	 * Retrieve the event ids match the search criteria.
	 * @return Vector of events that match the search criteria
	 */
	public Vector getEvents() {
		return this.events;
	}
	
}
