<?php
function sendMail($type, $message, $startTime) {
    
    $endtime = date('Y-m-d H:i:s', mktime(date('H')+5,date('i')+30,date('s'),date('m'),date('d'),date('Y')));
    
    switch($type) {
        
        case 'product_simple_start':
            $subject = 'Simple Product Import Starts - '.$startTime;
            $body = "Simple Product Import job has been started now and you will receive another notification after the completion.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'product_simple_end':
            $subject = 'Simple Product Import Ends - '.$endtime;
            $body = "Simple Product Importing job which had started on ".$startTime." been successfully ended now.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'product_simple_csv_error':
            $subject = 'Simple Product Import Ends - '.$endtime;
            $body = "Error is in processing the simple product CSV file. Today's CSV is not exists.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'product_simple_download_error':
            $subject = 'Simple Product Import Ends - '.$endtime;
            $body = "Error is in downloading the CSV file.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'product_config_start':
            $subject = 'Configure Product Import Starts - '.$startTime;
            $body = "Configure Product Import job has been started now and you will receive another notification after the completion.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'product_config_end':
            $subject = 'Configure Product Import Ends - '.$endtime;
            $body = "Configure Product Importing job which had started on ".$startTime." been successfully ended now.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'product_config_csv_error':
            $subject = 'Configure Product Import Ends - '.$endtime;
            $body = "Error is in processing the configure product CSV file. Today's CSV is not exists.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'product_config_download_error':
            $subject = 'Configure Product Import Ends - '.$endtime;
            $body = "Error is in downloading the CSV file.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'product_stock_start':
            $subject = 'Product Stock Update Starts - '.$startTime;
            $body = "Product Stock update job has been started now and you will receive another notification after the completion.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'product_stock_end':
            $subject = 'Product Stock Update Ends - '.$endtime;
            $body = "Product Stock update job which had started on ".$startTime." been successfully ended now.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'product_stock_csv_error':
            $subject = 'Product Stock Update Ends - '.$endtime;
            $body = "Error is in processing the stock CSV file. This hour's CSV is not exists.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;

		/*For Order Status update*/
        case 'order_status_start':
            $subject = 'Order Status Update Starts - '.$endtime;
            $body = "Order Status update job which had started on ".$startTime." been successfully started now.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
        
        case 'order_status_end':
            $subject = 'Order Status Update Ends - '.$endtime;
            $body = "Order Status update job which had started on ".$startTime." been successfully finished now.\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;

        case 'status_update_csv_error':
            $subject = 'Order Status - No CSV File - '.$endtime;
            $body = "This hour's CSV is not exists ".$endtime.".\n\n";
            if($message) $body .= $message."\n\n";
            $body .= MAIL_SIGNATURE;
            break;
		/*For Order Status update*/

    }

    $headers = 'From: '.MAIL_FROM;
    $body = "Hi Team,\n\n".$body;
    mail(MAIL_TO, $subject, $body, $headers);
    //echo $body;
    
}