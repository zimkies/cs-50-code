package com.bettina;

/**
 * The Cambridge class was written to hold information about
 * the Bettina Network Cambridge location.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class Cambridge {

	private String address1;
	private String address2;
	private String state;
	private String city;
	private String zipcode;
	private String phone1;
	private String phone2;
	private String fax;
	private String url;
	
	/**
	 * Construct an instance of Cambrige.
	 */
	public Cambridge() {}
	
	/**
	 * Construct an instance of Cambridge.
	 * @param address1 First line of address
	 * @param address2 Second line of address
	 * @param city City
	 * @param state State
	 * @param zipcode Zip Code
	 * @param phone Phone number
	 * @param fax Fax number
	 * @param url Website Url
	 */
	public Cambridge(String address1, String address2, String city, String state, String zipcode, String phone1, String phone2, String fax, String url) {
		setAddress1(address1);
		setAddress2(address2);
		setCity(city);
		setState(state);
		setZipCode(zipcode);
		setPhone1(phone1);
		setPhone2(phone2);
		setFax(fax);
		setUrl(url);
	}
	
	/**
	 * Set the first line of the address.
	 * @param address1 First line of address
	 */
	public void setAddress1(String address1) {
		if( address1 != null ) {
			this.address1 = new String(address1);
		}
	}
	
	/**
	 * Retrieve the first line of the address.
	 * @return First line of address
	 */
	public String getAddress1() {
		if( this.address1 == null ) {
            return "";
        }
		return this.address1;
	}
	
	/**
	 * Set the second line of the address.
	 * @param address2 Second line of address
	 */
	public void setAddress2(String address2) {
		if( address2 != null ) {
			this.address2 = new String(address2);
		}
	}
	
	/**
	 * Retrieve the second line of the address
	 * @return Second line of the address
	 */
	public String getAddress2() {
		if( this.address2 == null ) {
            return "";
        }
		return this.address2;
	}
	
	/**
	 * Set the city name.
	 * @param city City name
	 */
	public void setCity(String city) {
		if( city != null ) {
			this.city = new String(city);
		}
	}
	
	/**
	 * Retrieve the city name.
	 * @return
	 */
	public String getCity() {
		if( this.city == null ) {
            return "";
        }
		return this.city;
	}
	
	/**
	 * Set the state name.
	 * @param state State name
	 */
	public void setState(String state) {
		if( state != null ) {
			this.state = new String(state);
		}
	}
	
	/**
	 * Get the state name.
	 * @return State name
	 */
	public String getState() {
		if( this.state == null ) {
            return "";
        }
		return this.state;
	}
	
	/**
	 * Set the address zip code.
	 * @param zipcode Address zip code
	 */
	public void setZipCode(String zipcode) {
		if( zipcode != null ) {
			this.zipcode = new String(zipcode);
		}
	}
	
	/**
	 * Retrieve the address zip code.
	 * @return Address zip code
	 */
	public String getZipCode() {
		if( this.zipcode == null ) {
            return "";
        }
		return this.zipcode;
	}
	
	/**
	 * Set the first phone number.
	 * @param phone First phone number
	 */
	public void setPhone1(String phone1) {
		if( phone1 != null ) {
			this.phone1 = new String(phone1);
		}
	}
	
	/**
	 * Retrieve the first phone number.
	 * @return First phone number
	 */
	public String getPhone1() {
		if( this.phone1 == null ) {
            return "";
        }
		return this.phone1;
	}
	
	/**
	 * Set the second phone number.
	 * @param phone Second phone number
	 */
	public void setPhone2(String phone2) {
		if( phone2 != null ) {
			this.phone2 = new String(phone2);
		}
	}
	
	/**
	 * Retrieve the second phone number.
	 * @return Second phone number
	 */
	public String getPhone2() {
		if( this.phone2 == null ) {
            return "";
        }
		return this.phone2;
	}
	/**
	 * Set the fax number.
	 * @param fax Fax number
	 */
	public void setFax(String fax) {
		if( fax != null ) {
			this.fax = new String(fax);
		}
	}
	
	/**
	 * Retrieve the fax number.
	 * @return Fax number
	 */
	public String getFax() {
		if( this.fax == null ) {
            return "";
        }
		return this.fax;
	}
	
	/**
	 * Set the website url.
	 * @param url Website url
	 */
	public void setUrl(String url) {
		if( url != null ) {
			this.url = new String(url);
		}
	}
	
	/**
	 * Retrieve the website url.
	 * @return Website url
	 */
	public String getUrl() {
		if( this.url == null ) {
            return "";
        }
		return this.url;
	}
}
