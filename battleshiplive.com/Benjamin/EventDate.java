package com.bettina;

/**
 * An EventDate is a specific date range where an event takes
 * place.  The event date has a date type identifier that is
 * used to distinquish the different types (date range, single
 * date, date range with hours).
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class EventDate {

	private int id;
	private int eventid;
	private int datetype;
	private String begdate;
	private String enddate;
	private String timestr;
	
	
	/**
	 * Construct an instance of EventDate.
	 *
	 */
	public EventDate() {}
	
	/**
	 * Retrieve the date id.
	 * @return Date id
	 */
	public int getId() {
		return this.id;
	}
		
	/**
	 * Set the date id.
	 * @param id Date id
	 */
	public void setId(int id) {
		this.id = id;
	}
	
	/**
	 * Retrieve the date type.
	 * @return Date type (1 - random, 2 recurring with hours)
	 */
	public int getDateType() {
		return this.datetype;
	}
	
	/**
	 * Set the date type.
	 * @param datetype Date type (1 - random, 2 recurring with hours)
	 */
	public void setDateType(int datetype) {
		this.datetype = datetype;
	}
	
	/**
	 * Retrieve the event id.
	 * @return Event id
	 */
	public int getEventId() {
		return this.eventid;
	}
	
	/**
	 * Set the event id
	 * @param eventid Event id.
	 */
	public void setEventId(int eventid) {
		this.eventid = eventid;
	}
	
	/**
	 * Retrieve the beginning date.
	 * @return Beginning date
	 */
	public String getBegDate() {
		if( this.begdate == null ) {
			return "";
		}
		return this.begdate;
	}
		
	/**
	 * Set the beginning date.
	 * @param begdate Beginning date
	 */
	public void setBegDate(String begdate) {
		if( begdate != null ) {
			this.begdate = new String(begdate);
		}
	}
	
	/**
	 * Retrieve the ending date.
	 * @return Ending date
	 */
	public String getEndDate() {
		if( this.enddate == null ) {
			return "";
		}
		return this.enddate;
	}
			
	/**
	 * Set the ending date.
	 * @param enddate Ending date
	 */
	public void setEndDate(String enddate) {
		if( enddate != null ) {
			this.enddate = new String(enddate);
		}
	}
	
	/**
	 * Retrieve the time.
	 * @return The time
	 */
	public String getTimeStr() {
		if( this.timestr == null ) {
			return "";
		}
		return this.timestr;
	}
	
	/**
	 * Set the time.
	 * @param timestr The time
	 */
	public void setTimeStr(String timestr) {
		if( timestr != null ) {
			this.timestr = new String(timestr);
		}
	}
}
