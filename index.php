<h2>View Tickets</h2>
<?php
    error_reporting(0);
    $customValue="Enter Value here";

    $url = "http://YOURDOMAIN.zendesk.com/search.xml?query=$customValue";
    $headers = array('Content-type: application/xml');
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_USERPWD, 'username:pass');
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_GET, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_GETFIELDS, $request);

    $http_result = curl_exec($ch);
    $error       = curl_error($ch);
    $http_code   = curl_getinfo($ch ,CURLINFO_HTTP_CODE);

    curl_close($ch);
    if ($error) {
        print "<br /><br />$error";
    }
    else {
        $doc = new SimpleXmlElement($http_result, LIBXML_NOCDATA);

            // Remove the commenting below to see a full dump of the results, then you can pick your own variables
            //
            //echo "<pre>";
            //var_dump($doc);
            //echo "</pre>";
    
            $numtickets = count($doc->record);

        if ( $numtickets > 0 ) {
        
        ?>
        <P>Zendesk Support Tickets</P>
               <table width="500" id="mytable" cellspacing="0" summary="Customer Tickets"><tr>
            <tr>
        <th scope='col' abbr='ticket'>Ticket</th>
        <th scope='col' abbr='ticket'>Description</th>
           <th scope='col' abbr='createdTime'>Created Time</th>
            <th scope='col' abbr='tags'>Tags</th>
        <th scope='col' abbr='subject'>Subject</th>
            <th scope='col' abbr='priority'>Priority</th>
            <th scope='col' abbr='status'>Status</th>
            <th scope='col' abbr='solved'>Solved Time</th>
            </tr>
        <?php
                
		foreach($doc->record->{'ticket-field-entries'}->{'ticket-field-entry'}[1]->value as $value)
		{
		
		echo "Custom Value: "; echo $value;

		}
		        
        foreach($doc->record as $item)
        {
            echo "<tr>";
            foreach ($item->{'nice-id'} as $ticket) { echo "<th scope=row class=spec>$ticket</th>"; }
            foreach ($item->{'description'} as $description) { echo "<th scope=row class=spec>$description</th>"; }
            foreach ($item->{'created-at'} as $created) { echo "<td>$created</td>"; }
            foreach ($item->{'current-tags'} as $tags) { echo "<td>$tags</td>"; }
            foreach ($item->{'subject'} as $subject) { echo "<td>$subject</td>"; }
                       foreach ($item->{'priority-id'} as $priority) { 
            echo "<td>";
                        switch ($priority)
                                {
                                case 0:
                                        echo "None";
                                        break;
                				case 1:
                   						echo "Low";
                    					break;
                                case 2:
                                        echo "Normal";
                                        break;
                                case 3:
                                        echo "High";
                                        break;
                                case 4:
                                        echo "Urgent";
                                        break;
                default:
                    echo "Uknown";
                }
            }
            echo "</td>";
            foreach ($item->{'status-id'} as $status) {
                        echo "<td>";
                        switch ($status)
                                {
                                case 0:
                                        echo "New";
                                        break;
                                case 1:
                                        echo "Open";
                                        break;
                                case 2:
                                        echo "Pending";
                                        break;
                                case 3:
                                        echo "Solved";
                                        break;
                                case 4:
                                        echo "Closed";
                                        break;
                                default:
                                        echo "Uknown";
                                }
                        }
                        echo "</td>";
                    foreach ($item->{'solved-at'} as $solved) { echo "<td>$solved</td>"; }
                    foreach ($item->{'solved-at'} as $solved) { echo "<td>$solved</td>"; }
            echo "</tr>";
        }
        echo "</table>";
    } else { 
        echo "<BR><BR>Zendesk: No Support Tickets"; 
    }
}

?>