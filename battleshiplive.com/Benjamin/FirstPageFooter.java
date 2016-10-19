package com.bettina;

import com.lowagie.text.pdf.*;
import com.lowagie.text.*;

import javax.servlet.*;

/**
 * This Footer class creates a header and footer for every page of the pdf.
 * The footer contains the Bettina Network image and the address
 * of the Cambridge location.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class FirstPageFooter extends PdfPageEventHelper {
	
	/**
	 *  The table that will be added as the footer of the document.
	 */
	protected PdfPTable footer;
	
	/**
	 * Constructs an instance of Footer.
	 * @param context Servlet context for logging
	 * @param network Class that contains the db queries
	 * @param folder Folder that contains the images and fonts
	 */
	public FirstPageFooter(ServletContext context, BettinaNetwork network, String folder) {
		try {
			float[] widths2 = {1f,1f,1f};
			
			footer = new PdfPTable(widths2);
			footer.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
			footer.addCell("");
			footer.addCell("");
			
			StringBuffer betBuffer = new StringBuffer(folder);
			betBuffer.append("/footer2.gif");
			
			Image img3 = Image.getInstance(betBuffer.toString());
			img3.scalePercent(30);
			PdfPCell footerCell = new PdfPCell(img3, false);
			footerCell.setBorder( PdfPCell.NO_BORDER );
			footer.addCell( footerCell );
			
			footer.addCell("");
			footer.addCell("");
			
			Font[] fonts = new Font[1];
			fonts[0] = FontFactory.getFont(FontFactory.HELVETICA, 10);
			
			Cambridge cam = network.getCambridge(context);
			StringBuffer buffer = new StringBuffer(cam.getAddress1());
			buffer.append("\n");
			if( cam.getAddress2().length() > 0 ) {
				buffer.append(cam.getAddress2());
				buffer.append("\n");
			}
			buffer.append(cam.getCity());
			buffer.append(", ");
			buffer.append(cam.getState());
			buffer.append("  ");
			buffer.append(cam.getZipCode());
			buffer.append("\n");
			buffer.append(cam.getPhone1());
			buffer.append("\n");
			if( cam.getPhone2().length() > 0 ) {
				buffer.append(cam.getPhone2());
				buffer.append("\n");
			}
			if( cam.getFax().length() > 0 ) {
				buffer.append("Fax: ");
				buffer.append(cam.getFax());
				buffer.append("\n");
			}
			Paragraph par = new Paragraph(buffer.toString(), fonts[0]);
			par.setLeading(12);
			footer.addCell(par);
		} catch(Throwable except) {
			context.log("Failed while creating header/footer", except);			
		}
	}

	/**
	 * @see com.lowagie.text.pdf.PdfPageEvent#onEndPage(com.lowagie.text.pdf.PdfWriter,
	 *      com.lowagie.text.Document)
	 */
	public void onEndPage(PdfWriter writer, Document document) {
		try {
			Rectangle page = document.getPageSize();
			footer.setTotalWidth(page.width() - document.leftMargin() - document.rightMargin());
	        footer.writeSelectedRows(0, -1, document.leftMargin(), document.bottomMargin(),
	            writer.getDirectContent());
		} catch(Exception except) {
			System.out.println("Failed on end of page event, exception: " + except.getMessage());			
		}
	}

}
