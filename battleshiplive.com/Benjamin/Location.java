package com.bettina;

public class Location {

	private int id;
	private String location;
	private String address1;
	private String address2;
	private String city;
	private String state;
	private String zipcode;
	private String url;
	private String phone;
	private int category;
	
	/**
	 * Construct an instance of Location.
	 */
	public Location() {}
	
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
