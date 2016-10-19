package com.bettina;

/**
 * An Event is a event in a specific city & state.  Events have
 * information such as the event location, event url, cost, phone
 * number, description.  An Event is also associated with a category
 * so that the user's can limit their event search to areas of interest.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class Event {

	private int id;
	private String name;
	private String location;
	private String address1;
	private String address2;
	private String city;
	private String state;
	private String zipcode;
	private String url;
	private String phone;
	private String cost;
	private String description;
	private int category;
		
	/**
	 * Construct an instance of Event.
	 */
	public Event() {}
	
	/**
	 * Retrieve the event id.
	 * @return Event id
	 */
	public int getId() {
		return this.id;
	}
		
	/**
	 * Set the event id.
	 * @param id Event id
	 */
	public void setId(int id) {
		this.id = id;
	}
		
	/**
	 * Retrieve the event name.
	 * @return Event name
	 */
	public String getName() {
		if( this.name == null ) {
			return "";
		}
		return this.name;
	}
		
	/**
	 * Set the event name.
	 * @param name Event name
	 */
	public void setName(String name) {
		if( name != null ) {
			this.name = new String(name);
		}
	}

	/**
	 * Retrieve the event location.
	 * @return Event location
	 */
	public String getLocation() {
		if( this.location == null ) {
			return "";
		}
		return this.location;
	}
		
	/**
	 * Set the event location.
	 * @param location Event location
	 */
	public void setLocation(String location) {
		if( location != null ) {
			this.location = new String(location);
		}
	}
	
	/**
	 * Retrieve the first line of the address.
	 * @return Address first line
	 */
	public String getAddress1() {
		if( this.address1 == null ) {
			return "";
		}
		return this.address1;
	}
		
	/**
	 * Set the first line of the address.
	 * @param address1 Address first line
	 */
	public void setAddress1(String address1) {
		if( address1 != null ) {
			this.address1 = new String(address1);
		}
	}
	
	/**
	 * Retrieve the second line of the address.
	 * @return Address second line
	 */
	public String getAddress2() {
		if( this.address2 == null ) {
			return "";
		}
		return this.address2;
	}
	
	/**
	 * Retrieve a displayable string for address2.
	 * @return Displayable string
	 */
	public String getDisplayAddress2() {
		if( this.address2 == null ) {
			return "&nbsp;";
		}
		return this.address2;
	}
		
	/**
	 * Set the second line of the address.
	 * @param address2 Address second line
	 */
	public void setAddress2(String address2) {
		if( address2 != null ) {
			this.address2 = new String(address2);
		}
	}
	
	/**
	 * Retrieve the event city.
	 * @return Event city
	 */
	public String getCity() {
		if( this.city == null ) {
			return "";
		}
		return this.city;
	}
		
	/**
	 * Set the event city.
	 * @param city Event city
	 */
	public void setCity(String city) {
		if( city != null ) {
			this.city = new String(city);
		}
	}
	
	/**
	 * Retrieve the event state.
	 * @return Event state
	 */
	public String getState() {
		if( this.state == null ) {
			return "";
		}
		return this.state;
	}
		
	/**
	 * Set the event state.
	 * @param state Event state
	 */
	public void setState(String state) {
		if( state != null ) {
			this.state = new String(state);
		}
	}
	
	/**
	 * Retrieve the event zip code.
	 * @return Event zip code
	 */
	public String getZipCode() {
		if( this.zipcode == null ) {
			return "";
		}
		return this.zipcode;
	}
		
	/**
	 * Set the event zip code.
	 * @param zipcode Event zip code
	 */
	public void setZipCode(String zipcode) {
		if( zipcode != null ) {
			this.zipcode = new String(zipcode);
		}
	}
	
	/**
	 * Retrieve the event url.
	 * @return Event url
	 */
	public String getUrl() {
		if( this.url == null ) {
			return "";
		}
		return this.url;
	}
		
	/**
	 * Retrieve the displayable string for the url.
	 * @return Displayable url string
	 */
	public String getDisplayUrl() {
		if( this.url == null ) {
			return "&nbsp;";
		}
		return this.url;
	}
	
	/**
	 * Set the event url.
	 * @param url Event url
	 */
	public void setUrl(String url) {
		if( url != null ) {
			this.url = new String(url);
		}
	}
	
	/**
	 * Retrieve the event phone number.
	 * @return Event phone number
	 */
	public String getPhone() {
		if( this.phone == null ) {
			return "";
		}
		return this.phone;
	}
		
	/**
	 * Retrieve the displayable string for the phone number.
	 * @return Displayable phone number string
	 */
	public String getDisplayPhone() {
		if( this.phone == null ) {
			return "&nbsp;";
		}
		return this.phone;
	}
	
	/**
	 * Set the event phone number.
	 * @param phone Event phone number
	 */
	public void setPhone(String phone) {
		if( phone != null ) {
			this.phone = new String(phone);
		}
	}
	
	/**
	 * Retrieve the event cost.
	 * @return Event cost
	 */
	public String getCost() {
		if( this.cost == null ) {
			return "";
		}
		return this.cost;
	}
		
	/**
	 * Retrieve the displayable event cost.
	 * @return Displayable event cost string
	 */
	public String getDisplayCost() {
		if( this.cost == null ) {
			return "&nbsp;";
		}
		return this.cost;
	}
	
	/**
	 * Set the event cost.
	 * @param cost Event cost
	 */
	public void setCost(String cost) {
		if( cost != null ) {
			this.cost = new String(cost);
		}
	}
	
	/**
	 * Retrieve the event description.
	 * @return Event description
	 */
	public String getDescription() {
		if( this.description == null ) {
			return "";
		}
		return this.description;
	}
		
	/**
	 * Retrieve the displayable event description string.
	 * @return Displayable event description string
	 */
	public String getDisplayDescription() {
		if( this.description == null ) {
			return "&nbsp;";
		}
		return this.description;
	}
	
	/**
	 * Set the event description.
	 * @param description Event description
	 */
	public void setDescription(String description) {
		if( description != null ) {
			this.description = new String(description);
		}
	}
	
	/**
	 * Retrieve the category id.
	 * @return Category id
	 */
	public int getCategory() {
		return this.category;
	}
		
	/**
	 * Set the category id.
	 * @param category Category id
	 */
	public void setCategory(int category) {
		this.category = category;
	}
	
}
