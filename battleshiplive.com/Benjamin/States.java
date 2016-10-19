package com.bettina;

/**
 * Helper class that provides static calls to retrieve state
 * and state abbreviation information.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class States {

	private static String[][] states = {
			{"Alabama","AL"}, 
			{"Alaska","AK"}, 
			{"Arizona","AZ"}, 
			{"Arkansas","AR"},
			{"California","CA"},
			{"Colorado","CO"},
			{"Connecticut","CT"},
			{"Delaware","DE"},
			{"District of Columbia","DC"}, 
			{"Florida","FL"}, 
			{"Georgia","GA"}, 
			{"Hawaii","HI"},
			{"Idaho","ID"},
			{"Illinois","IL"},
			{"Indiana","IN"},
			{"Iowa","IA"}, 
			{"Kansas","KS"},
			{"Kentucky","KY"},
			{"Louisiana","LA"},
			{"Maine","ME"},
			{"Maryland","MD"},
			{"Massachusetts","MA"},
			{"Michigan","MI"},
			{"Minnesota","MN"},
			{"Mississippi","MS"},
			{"Missouri","MO"},
			{"Montana","MT"},
			{"Nebraska","NE"},
			{"Nevada","NV"},
			{"New Hampshire","NH"},
			{"New Jersey","NJ"},
			{"New Mexico","NM"},
			{"New York","NY"},
			{"North Carolina","NC"},
			{"North Dakota","ND"},
			{"Ohio","OH"},
			{"Oklahoma","OK"},
			{"Oregon","OR"},
			{"Pennsylvania","PA"},
			{"Rhode Island","RI"},
			{"South Carolina","SC"},
			{"South Dakota","SD"},
			{"Tennessee","TN"},
			{"Texas","TX"},
			{"Utah","UT"},
			{"Vermont","VT"},
			{"Virginia","VA"},
			{"Washington","WA"},
			{"West Virginia","WV"},
			{"Wisconsin","WI"},
			{"Wyoming","WY"}};
	
	private static String[] abbreviations = {
		"AK","AL","AR","AZ","CA","CO","CT","DC","DE","FL","GA","HI","IA","ID","IL","IN","KS","KY",
		"LA","MA","MD","ME","MI","MN","MO","MS","MT","NC","ND","NE","NH","NJ","NM","NV","NY","OH",
		"OK","OR","PA","RI","SC","SD","TN","TX","UT","VA","VT","WA","WI","WV","WY"}; 

		
	/**
	 * Retrieve a list of the state names in alphabetical order.
	 * @return String array of state names
	 */
	public static String[] getStates() {
		String[] sts = new String[51];
		for(int x = 0; x < states.length; x++) {
			sts[x] = states[x][0];
		}
		return sts;
	}
	
	/**
	 * Retrieve an alphabeticall list of the state abbreviations.
	 * @return Alphabetical list of state abbreviations
	 */
	public static String[] getAbbreviations() {
		return abbreviations;
	}
	
	/**
	 * Given a state name retrieve the state abbreviation.
	 * @param state Full state name
	 * @return State abbreviation; otherwise ""
	 */
	public static String getStateAbbrev(String state) {
		for(int x = 0; x < states.length; x++) {
			if( states[x][0].equalsIgnoreCase(state) ) {
				return states[x][1];
			}
		}
		return "";
	}
	
	/**
	 * Retrieve the state name given the state abbreviation.
	 * @param abbrev State abbreviation
	 * @return The state name; otherwise ""
	 */
	public static String getState(String abbrev) {
		for(int x = 0; x < states.length; x++) {
			if( states[x][1].equalsIgnoreCase(abbrev) ) {
				return (String)states[x][0];
			}
		}
		return "";
	}

}
