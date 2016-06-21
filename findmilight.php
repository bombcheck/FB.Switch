<?php
header("Content-Type: application/json; charset=utf-8");

$broadcast_string="Link_Wi-Fi";
$port = 48899;
$timeout = array('sec'=>1,'usec'=>500000);

if(!($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP))) {
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
     
    die('{"ERROR":{"ERRORCODE":'.json_encode($errorcode).',"ERRORMSG":'.json_encode($errormsg).'}}');
}

socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, 1); 
socket_sendto($socket, $broadcast_string, strlen($broadcast_string), 0, '255.255.255.255', $port);

socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,$timeout);

$from_recv = '';
$port_recv = 0;
$mlbridge = array();
$id=10;

while($len = @socket_recvfrom($socket, $buf, 255, 0, $from_recv, $port_recv)) {
    if($buf != "") {
        $dataString = explode(",", $buf);
        for ($cnt=0;$cnt < count($dataString);$cnt=$cnt+2) {
            if ($dataString[$cnt] != "") {
                $data = array();
                $data['ID'] = $id;
                $data['STATUS'] = 'OK';
                $data['IP'] = $dataString[$cnt];
                $data['MAC'] = $output = wordwrap($dataString[$cnt+1],2,':',true);
                $data['PORT'] = "8899";
                $mlbridge["MILIGHT".$id] = $data;
            }
        }
    $id++;
    }
}

if (empty($mlbridge)) {
    $mlbridge["MILIGHT".$id] = array('ID' => $id, 'STATUS' => 'NOT FOUND');
}
echo json_encode($mlbridge);
socket_close($socket); 
?>
