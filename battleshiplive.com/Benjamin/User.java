package com.bettina;

/**
 * The User class is used to store information about a particular
 * guest to the Bettina Network.  The information will be saved in
 * the database until the end of the persons visit. 
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class User {

	private int id = 0;
	private String first = null;
	private String last = null;
	private String name = null;
	private String password = null;
	private String email = null;
	private String city = null;
	private String state = null;
	private String arrival = null;
	private String departure = null;
		
	/**
	 * Construct a User object.
	 */
	public User() {}
	
	/**
	 * Construct a User object.
	 */
	public User(int id, String first, String last, String name, String password, String email, String arrival, String departure, String city, String state) {
		this.id = id;
		setFirst(first);
		setLast(last);
		setName(name);
		setPassword(password);
		setEmail(email);
		setCity(city);
		setState(state);
		setArrival(arrival);
		setDeparture(departure);
	}
	
	/**
	 * Retrieve the user id.
	 * @return User id
	 */
	public int getId() {
		return this.id;
	}
	
	/**
	 * Set the user id.
	 * @param id User id
	 */
	public void setId(int id) {
		this.id = id;
	}
	
	/**
	 * Retrieve the user's first name.
	 * @return The user's first name
	 */
	public String getFirst() {
		if( this.first == null ) {
			return "";
		}
		return this.first;
	}
	
	/**
	 * Set the user's first name.
	 * @param first The user's first name
	 */
	public void setFirst(String first) {
		if( first != null ) {
			this.first = new String(first);
		}
	}
	
	/**
	 * Retrieve the user's last name.
	 * @return The user's last name
	 */
	public String getLast() {
		if( this.last == null ) {
			return "";
		}
		return this.last;
	}
	
	/**
	 * Set the user's last name.
	 * @param last The user's last name
	 */
	public void setLast(String last) {
		if( last != null ) {
			this.last = new String(last);
		}
	}
		
	/**
	 * Retrieve the user name.
	 * @return User name
	 */
	public String getName() {
		if( this.name == null ) {
			return "";
		}
		return this.name;
	}
	
	/**
	 * Set the user name.
	 * @param name User name
	 */
	public void setName(String name) {
		if( name != null ) {
			this.name = new String(name);
		}
	}
	
	/**
	 * Retreve the user's password.
	 * @return User's password
	 */
	public String getPassword() {
		if( this.password == null ) {
			return "";
		}
		return this.password;
	}
	
	/**
	 * Set the user's password.
	 * @param password User's password
	 */
	public void setPassword(String password) {
		if( password != null ) {
			this.password = new String(password);
		}
	}
	
	/**
	 * Retrieve the email address.
	 * @return Email address
	 */
	public String getEmail() {
		if( this.email == null ) {
			return "";
		}
		return this.email;
	}
			
	/**
	 * Set the email address.
	 * @param email Email address
	 */
	public void setEmail(String email) {
		if( email != null ) {
			this.email = new String(email);
		}
	}
	
	/**
	 * Retrieve the city the user is visting.
	 * @return Visiting city
	 */
	public String getCity() {
		if( this.city == null ) {
			return "";
		}
		return this.city;
	}
	
	/**
	 * Set the city the user is visting.
	 * @param city Visiting city
	 */
	public void setCity(String city) {
		if( city != null ) {
			this.city = new String(city);
		}
	}
	
	/**
	 * Retrieve the state the user is visiting.
	 * @return Visting state
	 */
	public String getState() {
		if( this.state == null ) {
			return "";
		}
		return this.state;
	}
	
	/**
	 * Set the state the user is visiting.
	 * @param state Visiting state
	 */
	public void setState(String state) {
		if( state != null ) {
			this.state = new String(state);
		}
	}
	
	/**
	 * Retrieval the user's arrival date.
	 * @return Arrival date
	 */
	public String getArrival() {
		if( this.arrival == null ) {
			return "";
		}
		return this.arrival;
	}
	
	/**
	 * Set the user's arrival date.
	 * @param arrival Arrival date
	 */
	public void setArrival(String arrival) {
		if( arrival != null ) {
			this.arrival = new String(arrival);
		}
	}
	
	/**
	 * Retrieve the user's departure date.
	 * @return Departure date
	 */
	public String getDeparture() {
		if( this.departure == null ) {
			return "";
		}
		return this.departure;
	}
	
	/**
	 * Set the user's departure date.
	 * @param departure Departure date
	 */
	public void setDeparture(String departure) {
		if( departure != null ) {
			this.departure = new String(departure);
		}
	}
	
}
