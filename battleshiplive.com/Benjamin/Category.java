package com.bettina;

/**
 * The Category class defines a category in the Bettina Network.
 * Some category examples are ice skating, forums, etc.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class Category implements Comparable {

	private int id = 0;
	private String name = null;
	private String description = null;
	private int referenced = 0;
	
	/**
	 * Construct a Category object.
	 */
	public Category() {}
	
	/**
	 * Construct a Category object
	 * @param id category id
	 * @param name category name
	 * @param description category description
	 * @param referenced number of times searched
	 */
	public Category(int id, String name, String description, int referenced) {
		this.id = id;
		this.setName(name);
		this.setDescription(description);
		this.referenced = referenced;
	}
	
	/**
	 * Set the category id.
	 * @param id category id
	 */
	public void setId(int id) {
		this.id = id;
	}
	
	/**
	 * Retrieve the category id.
	 * @return category id
	 */
	public int getId() {
		return this.id;
	}
	
	/**
	 * Set the category name.
	 * @param name category name
	 */
	public void setName(String name) {
		if( name != null ) {
			this.name = new String(name);
		}
	}
	
	/**
	 * Retrieve the category name.
	 * @return category name
	 */
	public String getName() {
		if( this.name == null ) {
            return "";
        }
		return this.name;
	}
	
	/**
	 * Set the category description.
	 * @param description category description
	 */
	public void setDescription(String description) {
		if( description != null ) {
			this.description = new String(description);
		}
	}
	
	/**
	 * Retrieve the category description.
	 * @return category description
	 */
	public String getDescription() {
		if( this.description == null ) {
			return "";
		}
		return this.description;
	}
	
	/**
	 * Retrieve the display category description
	 * @return display category description
	 */
	public String getDisplayDescription() {
		if( this.description == null ) {
			return "&nbsp;";
		}
		return this.description;
	}
		
	/**
	 * Set the number of times referenced in a search.
	 * @param referenced category referenced.
	 */
	public void setReferenced(int referenced) {
		this.referenced = referenced;
	}
	
	/**
	 * Retrieve the number of times referenced in a search.
	 * @return category referenced.
	 */
	public int getReferenced() {
		return this.referenced;
	}
	
	/**
	 * Compare the category names so the Cateogries can be ordered.
	 * @param obj The Category to compare against
	 * @return 0 if the names are the same; otherwise a # <> 0
	 */
	public int compareTo(Object obj) {
		Category catCompare = (Category)obj;
		return this.name.compareTo(catCompare.getName());
	}
}
