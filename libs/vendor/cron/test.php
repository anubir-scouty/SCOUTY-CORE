<?php

require '../../../equinox/config/conf.php';
require '../../model.php';
require '../../database.php';
require_once '../phpmailer/phpmailer/PHPMailerAutoload.php';

$db = new Database();


$today = date("Y-m-d");
$query_q = 'SELECT 
			b.id,b.startdate,b.enddate,b.finalprice,
			b.guest,b.guests,b.recordid,
			b.total,b.transactionid, b.blocked,
			b.comission,b.commissionperc, b.netamount,b.rated, b.ratingsent,
			o.name as ownerName,
			g.name as guestName,
			g.email as guestEmail,
			p.name AS propName
			FROM cms_content b
			LEFT JOIN (
				SELECT id,name
				FROM cms_content
				WHERE recordset =  "property"
			)p ON p.id = b.recordid 
			LEFT JOIN (
				SELECT id,name,email
				FROM cms_content
				WHERE recordset =  "members"
			)o ON o.id = b.owner 
			LEFT JOIN (
				SELECT id,name,email
				FROM cms_content
				WHERE recordset =  "members"
			)g ON g.id = b.guest
			WHERE
			enddate < "2016-11-28"
			AND b.ratingsent = "no"
			AND (b.blocked IS NULL OR b.blocked = "")
			AND recordset = "bookings"
			ORDER BY startdate ASC';
$query_r = mysql_query($query_q) or die(mysql_error());
echo mysql_num_rows($query_r);
while($bookings = mysql_fetch_array($query_r)){

	$setToRead_q = 'UPDATE cms_content SET rate = "yes" WHERE id = "' . $bookings['id'] . '"';
	$setToRead_r = mysql_query($setToRead_q);

	$subject = "We need your feedback!";
	$to = array(
		$bookings['guestEmail'] => $bookings['guestName']
	);
	$message = file_get_contents('../../../plugins/mail/views/rate-property/index.phtml');
	$message = str_replace("{{siteroot}}",_SITEROOT_,$message);
	$message = str_replace("{{owner}}",$bookings['ownerName'],$message);
	$message = str_replace("{{guestname}}",$bookings['guestName'],$message);
	$message = str_replace("{{propname}}",$bookings['propName'],$message);
	$url = _SITEROOT_ . 'properties/rate-property/' . $bookings['transactionid'];
	$message = str_replace("{{actionurl}}",$url,$message);

	$body = $message;
	$mail = new PHPMailer;
	// $mail->isSMTP();                             // Set mailer to use SMTP
	$mail->Host 	= 'localhost';  				// Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                         // Enable SMTP authentication
	$mail->Username = 'info@whiteroofbnb.com'; 		// SMTP username
	$mail->Password = 'Mothugs123!';                // SMTP password

	$mail->setFrom('info@whiteroofbnb.com', 'White Roof B&B');
	foreach($to as $key => $val) {
		$mail->addAddress($key, $val);     
		// Add a recipient	
	}
	// $mail->addAddress('ellen@example.com');               // Name is optional
	$mail->addReplyTo('info@whiteroofbnb.com', 'Information');
	// $mail->addCC('cc@example.com');
	// $mail->addBCC('bcc@example.com');
	// $mail->addAttachment('/var/tmp/file.tar.gz');      // Add attachments
	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Subject = $subject;
	$mail->Body    = $body;
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->send()) {
		echo 'not sent';
	} else {
	    echo 'sent';
	}
}