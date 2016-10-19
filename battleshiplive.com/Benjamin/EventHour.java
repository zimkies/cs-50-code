package com.bettina;

/**
 * An EventHour is information about hours for an event for a 
 * specific day.  The event hour has open and close hours.  If
 * the event is closed then the closed flag will be set.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class EventHour {

	private int id;
	private int eventid;
	private int day;
	private int closed = 0;
	private String timestr;
	
	/**
	 * Construct an instance of EventHour.
	 */
	public EventHour() {}
	
	/**
	 * Retrieve the event hour id.
	 * @return Event hour id
	 */
	public int getId() {
		return this.id;
	}
	
	/**
	 * Set the event hour id.
	 * @param id Event hour id
	 */
	public void setId(int id) {
		this.id = id;
	}
	
	/**
	 * Retrieve the event id.
	 * @return Event id
	 */
	public int getEventId() {
		return this.eventid;
	}
	
	/**
	 * Set the event id.
	 * @param eventid Event id
	 */
	public void setEventId(int eventid) {
		this.eventid = eventid;
	}
	
	/**
	 * Retrieve the day of the week identifier. 
	 * @return Day of week (1 mon - 7 sun)
	 */
	public int getDay() {
		return this.day;
	}
	
	/**
	 * Set the day of the week identifier.
	 * @param day Day of week (1 mon - 7 sun)
	 */
	public void setDay(int day) {
		this.day = day;
	}
			
	/**
	 * Retrieve if the location is closed.
	 * @return 0 not closed; 1 closed
	 */
	public int getClosed() {
		return this.closed;
	}
		
	/**
	 * Set if the location is closed.
	 * @param closed 0 not closed; 1 closed
	 */
	public void setClosed(int closed) {
		this.closed = closed;
	}

	/**
	 * Get the time.
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
