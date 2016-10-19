package com.bettina;

/**
 * FirstPage contains the information that is displayed on the first
 * page of the pdf.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class FirstPage {

	private String welcome;
	private String par1;
	private String par2;
	private String par3;
	private String par4;
	private String par5;
	private String par6;
	
	/**
	 * Construct an instance of FirstPage.
	 */
	public FirstPage() {}
	
	/**
	 * Retrieve the welcome string.
	 * @return The welcome string
	 */
	public String getWelcome() {
		if( this.welcome == null ) {
			return "";
		}
		return this.welcome;
	}

	/**
	 * Set the welcome string.
	 * @param welcome The welcome string
	 */
	public void setWelcome(String welcome) {
		this.welcome = welcome;
	}
	
	/**
	 * Retrieve the first paragraph.
	 * @return First paragraph
	 */
	public String getPar1() {
		if( this.par1 == null ) {
			return "";
		}
		return this.par1;
	}

	/**
	 * Set the first paragraph.
	 * @param par1 First paragraph
	 */
	public void setPar1(String par1) {
		this.par1 = par1;
	}
	
	/**
	 * Get the second paragraph.
	 * @return Second paragraph
	 */
	public String getPar2() {
		if( this.par2 == null ) {
			return "";
		}
		return this.par2;
	}

	/**
	 * Set the second paragraph
	 * @param par2 Second paragraph
	 */
	public void setPar2(String par2) {
		this.par2 = par2;
	}
	
	/**
	 * Retrieve the third paragraph
	 * @return The third paragraph
	 */
	public String getPar3() {
		if( this.par3 == null ) {
			return "";
		}
		return this.par3;
	}

	/**
	 * Set the third paragraph
	 * @param par3 The third paragraph
	 */
	public void setPar3(String par3) {
		this.par3 = par3;
	}
	
	/**
	 * Retrieve the fourth paragraph.
	 * @return The fourth paragraph
	 */
	public String getPar4() {
		if( this.par4 == null ) {
			return "";
		}
		return this.par4;
	}

	/**
	 * Set the fourth paragraph.
	 * @param par4 The fourth paragraph
	 */
	public void setPar4(String par4) {
		this.par4 = par4;
	}
	
	/**
	 * Retrieve the fifth paragraph.
	 * @return The fifth paragraph
	 */
	public String getPar5() {
		if( this.par5 == null ) {
			return "";
		}
		return this.par5;
	}

	/**
	 * Set the fifth paragraph.
	 * @param par5 The fifth paragraph
	 */
	public void setPar5(String par5) {
		this.par5 = par5;
	}
	
	/**
	 * Retrieve the sixth paragraph.
	 * @return The sixth paragraph
	 */
	public String getPar6() {
		if( this.par6 == null ) {
			return "";
		}
		return this.par6;
	}

	/**
	 * Set the sixth paragraph.
	 * @param par6 The sixth paragraph
	 */
	public void setPar6(String par6) {
		this.par6 = par6;
	}
}
