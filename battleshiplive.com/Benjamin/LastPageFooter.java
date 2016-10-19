package com.bettina;

import com.lowagie.text.*;
import com.lowagie.text.pdf.*;

import javax.servlet.*;

/**
 * The LastPageFooter is used to write the footer for the last page of the
 * pdf documents.
 * 
 * @author Michael Spellman
 * Copyright (c) 2007 Boston Web Co. All rights reserved
 * 
 */
public class LastPageFooter extends PdfPageEventHelper {

	protected PdfPTable footer;
	
	/**
	 * Construct an instance of LastPageFooter.
	 * @context Logging context
	 * @imageFolder Folder that contains the images
	 */
	public LastPageFooter(ServletContext context, Administration admin, String imageFolder) {
		try {
			float[] widths2 = {1f,1f,1f};
			
			footer = new PdfPTable(widths2);
			footer.getDefaultCell().setBorder(PdfPCell.NO_BORDER);
			footer.addCell("");
			footer.addCell("");
			
			StringBuffer imageBuffer = new StringBuffer(imageFolder);
			imageBuffer.append("/footer2.gif");
			
			Image img3 = Image.getInstance(imageBuffer.toString());
			img3.scalePercent(30);
			PdfPCell footerCell = new PdfPCell(img3, false);
			footerCell.setBorder( PdfPCell.NO_BORDER );
			footer.addCell( footerCell );
			
			footer.addCell("");
			footer.addCell("");
			
			Font[] fonts = new Font[1];
			fonts[0] = FontFactory.getFont(FontFactory.HELVETICA, 10);
						
			Cambridge cam = admin.getCambridge(context);
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
			context.log("Failure while setting up last page footer", except);
		}
	}
	
	/**
	 * When we are on the end of the page, then this event is called to write the footer.
	 * @param writer The writer to write to the document
	 * @param document The document
	 */
	public void onEndPage(PdfWriter writer, Document document) {
		try {
			Rectangle page = document.getPageSize();
			footer.setTotalWidth(page.width() - document.leftMargin() - document.rightMargin());
			footer.writeSelectedRows(0, -1, document.leftMargin(), document.bottomMargin(),
					writer.getDirectContent());
		} catch(Exception except) {
			System.out.println("Failure while handling last page on end page event, exception: " + except.getMessage());
		}
	}
}
