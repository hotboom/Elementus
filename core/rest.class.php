<?
class REST {
    public $error;

    function get($url){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            $this->error=var_export($info);
            return false;
        }
        curl_close($curl);
        return json_decode($curl_response,true);
    }
}


/*Example of calling POST request

//next example will insert new conversation
$service_url = 'http://example.com/api/conversations';
$curl = curl_init($service_url);
$curl_post_data = array(
'message' => 'test message',
'useridentifier' => 'agent@example.com',
'department' => 'departmentId001',
'subject' => 'My first conversation',
'recipient' => 'recipient@example.com',
'apikey' => 'key001'
);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
$curl_response = curl_exec($curl);
if ($curl_response === false) {
$info = curl_getinfo($curl);
curl_close($curl);
die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($curl);
$decoded = json_decode($curl_response);
if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
die('error occured: ' . $decoded->response->errormessage);
}
echo 'response ok!';
var_export($decoded->response);


Example of calling PUT request

//next eample will change status of specific conversation to resolve
$service_url = 'http://example.com/api/conversations/cid123/status';
$ch = curl_init($service_url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
$data = array("status" => 'R');
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
$response = curl_exec($ch);
if ($response === false) {
$info = curl_getinfo($ch);
curl_close($ch);
die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($ch);
$decoded = json_decode($response);
if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
die('error occured: ' . $decoded->response->errormessage);
}
echo 'response ok!';
var_export($decoded->response);


Example of calling DELETE request

$service_url = 'http://example.com/api/conversations/[CONVERSATION_ID]';
$ch = curl_init($service_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
$curl_post_data = array(
'note' => 'this is spam!',
'useridentifier' => 'agent@example.com',
'apikey' => 'key001'
);
curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
$response = curl_exec($ch);
if ($curl_response === false) {
$info = curl_getinfo($curl);
curl_close($curl);
die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($curl);
$decoded = json_decode($curl_response);
if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
die('error occured: ' . $decoded->response->errormessage);
}
echo 'response ok!';
var_export($decoded->response);
*/
?>