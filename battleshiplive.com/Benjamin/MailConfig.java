package com.bettina;

/**
 * The MailConfig class holds mail settings used when the 
 * Administrator has created a user account.  The information
 * will be used to generate the email message, set the emai
 * subject, and return address.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class MailConfig {

	private String content1;
	private String greetings;
	private String subject;
	private String from;
	
	/**
	 * Create an instance of MailConfig.
	 */
	public MailConfig() {}
	
	/**
	 * Create an instance of MailConfig.
	 * @param content1 First paragraph
	 * @param greetings Greetings
	 * @param subject Email subject
	 * @param from Email sent from
	 */
	public MailConfig(String content1, String greetings, String subject, String from) {
		setContent1(content1);
		setGreetings(greetings);
		setSubject(subject);
		setFrom(from);
	}
	
	/**
	 * Set the first paragraph of the email.
	 * @param content1 First paragraph of email
	 */
	public void setContent1(String content1) {
		if( content1 != null ) {
			this.content1 = new String(content1);
		}
	}
		
	/**
	 * Retrieve the first paragraph of the email.
	 * @return First paragraph of email
	 */
	public String getContent1() {
		if( this.content1 == null ) {
			return "";
		}
		return this.content1;
	}
			
	/**
	 * Set the email greetings.
	 * @param greetings Email greetings
	 */
	public void setGreetings(String greetings) {
		if( greetings != null ) {
			this.greetings = new String(greetings);
		}
	}
		
	/**
	 * Retrieve the email greetings.
	 * @return Email greetings
	 */
	public String getGreetings() {
		if( this.greetings == null ) {
			return "";
		}
		return this.greetings;
	}
	
	/**
	 * Set the subject of the email.
	 * @param subject Email subject
	 */
	public void setSubject(String subject) {
		if( subject != null ) {
			this.subject = new String(subject);
		}
	}
		
	/**
	 * Retrieve the subject of the email.
	 * @return Email subject
	 */
	public String getSubject() {
		if( this.subject == null ) {
			return "";
		}
		return this.subject;
	}
	
	/**
	 * Set the email from address.
	 * @param from Email from address
	 */
	public void setFrom(String from) {
		if( from != null ) {
			this.from = new String(from);
		}
	}
		
	/**
	 * Retrieve the email from address.
	 * @return Email from address
	 */
	public String getFrom() {
		if( this.from == null ) {
			return "";
		}
		return this.from;
	}
	
}
