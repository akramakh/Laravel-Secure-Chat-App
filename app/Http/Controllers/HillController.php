<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use App\Message;
use App\Chat;
use App\User;
use App\Photo;

class HillController extends Controller
{
    //
    // foe real time chatting
    public function msgLoadEnc(Request $r)
    {
      $text = '';
      $chat = Chat::whereId($r->chat_id)->first();
     
      $msgs = Message::where('chat_id',$chat->id)->orderBy('id','desc')->get();
      foreach($msgs as $msg){
        if($msg->user_id == Auth::user()->id){
          $text .='<div class="msg me row">'.
                    '<div class="covor">'.
                      '<div class="item">'.$msg->body.'</div>'.
                      '<div class="date">'.$msg->created_at->diffForHumans().'</div>'.
                    '</div>'.
                ' </div>';
        }
        else{
          $text .='<div class="msg you row">'.
                    '<div class="covor">'.
                        '<div class="item">'.$msg->body.'</div>'.
                        '<div class="date">'.$msg->created_at->diffForHumans().'</div>'.
                    '</div>'.
                ' </div>';
        }
        // array_push($messages,$msg);
      }
      return $text;
    }
    // foe real time chatting
    public function msgLoadDec(Request $r)
    {
      $messages = array();
      $text = '';
      $chat = Chat::whereId($r->chat_id)->first();
      $uid = $chat->creator_id==Auth::user()->id ? $chat->member_id : $chat->creator_id;
      $user = User::whereId($uid)->first();
      if($chat->dim == 3){
        $key = array(array($chat->key[0],$chat->key[2],$chat->key[4]),
                    array($chat->key[6],$chat->key[8],$chat->key[10]),
                    array($chat->key[12],$chat->key[14],$chat->key[16]));
      }else{
        $key = array(array($chat->key[0],$chat->key[2]),
              array($chat->key[4],$chat->key[6]));
      }
      $msgs = Message::where('chat_id',$chat->id)->latest()->get();
      foreach($msgs as $msg){
        $time = $msg->created_at->minute * 10;
        $iv = $this->xor1(strval($time).'000', $chat->iv, $chat->dim);
        
        if($msg->user_id == Auth::user()->id){
          // return $msg->body;
          if($msg->is_photo == 0){
            
            $msg->body = $this->dec($msg->body, $chat->dim, $key, $iv);
            $text .='<div class="msg me row">'.
                      '<div class="covor">'.
                        '<div class="item">'.$msg->body.'</div>'.
                        '<div class="date">'.$msg->created_at->diffForHumans().'</div>'.
                      '</div>'.
                  ' </div>';
          }else{
            // $img = DB::select('select body from photos where id = ?',[intval($msg->body)]);
            $img = Photo::find(intval($msg->body));
            $base = $this->dec($img->body, $chat->dim, $key, $iv);
            $img = Image::make($base)->save(public_path('img/msg/').$img->id.".jpg");
            $text .='<div class="msg me row">'.
                      '<div class="img-covor">'.
                        '<div class="img"><img src="/img/msg/'.$msg->body.'.jpg"></img></div>'.
                        '<div class="date">'.$msg->created_at->diffForHumans().'</div>'.
                      '</div>'.
                  ' </div>';
          }
        }
        else{
          if($msg->is_photo == 0){
            $msg->body = $this->dec($msg->body, $chat->dim, $key, $iv);
            $text .='<div class="msg you row">'.
                      '<div class="covor">'.
                          '<div class="item">'.$msg->body.'</div>'.
                          '<div class="date">'.$msg->created_at->diffForHumans().'</div>'.
                      '</div>'.
                  ' </div>';
          }else{
            $img = Photo::find(intval($msg->body));
            $base = $this->dec($img->body, $chat->dim, $key, $iv);
            $img = Image::make($base)->save(public_path('img/msg/').$img->id.".jpg");
            $text .='<div class="msg you row">'.
                      '<div class="img-covor">'.
                          '<div class="img"><img src="/img/msg/'.$msg->body.'.jpg" style="float: right;"></img></div>'.
                          '<div class="date">'.$msg->created_at->diffForHumans().'</div>'.
                      '</div>'.
                  ' </div>';
          }
        }
        // array_push($messages,$msg);
      }
      return $text;
    }

    public function createMsg(Request $r)
    {
        $chat = Chat::whereId($r->chat_id)->first(); // getting the chat object
        if($chat->dim == 3){
          $key = array(array($chat->key[0],$chat->key[2],$chat->key[4]), //getting the chat key
                      array($chat->key[6],$chat->key[8],$chat->key[10]),
                      array($chat->key[12],$chat->key[14],$chat->key[16]));
        }else{
          $key = array(array($chat->key[0],$chat->key[2]), //getting the chat key
                array($chat->key[4],$chat->key[6]));
        }
        $time = (intval(time()/60)%60)*10; // calculate the random value (minutes)
        $iv = $this->xor1(strval($time).'000', $chat->iv, $chat->dim); // rando xor IV
        
        $msg_body = $this->enc($r->body, $chat->dim, $key, $iv); // encrypt the message
        $msg = Message::create([  // insert the encrypted message into the database
            "user_id" => $r->user_id,
            "chat_id" => $r->chat_id,
            "body" => $msg_body,
            "is_photo" => 0
        ]);
        return response($this->dec($msg_body, $chat->dim, $key, $iv)); // return decrypted messaage to the user
        
    }
    
    public function createImgMsg(Request $r)
    {
        $chat = Chat::whereId($r->chat_id)->first(); // getting the chat object
        if($chat->dim == 3){
          $key = array(array($chat->key[0],$chat->key[2],$chat->key[4]), //getting the chat key
                      array($chat->key[6],$chat->key[8],$chat->key[10]),
                      array($chat->key[12],$chat->key[14],$chat->key[16]));
        }else{
          $key = array(array($chat->key[0],$chat->key[2]), //getting the chat key
                array($chat->key[4],$chat->key[6]));
        }
        $time = (intval(time()/60)%60)*10; // calculate the random value (minutes)
        $iv = $this->xor1(strval($time).'000',$chat->iv, $chat->dim); // rando xor IV
        
        $base = $this->enc($r->img, $chat->dim, $key, $iv); // encrypt the image (base -4)
        $img = Photo::create([ // insert the encrypted image into the database
          "body" => $base,
          "extention" => 'jpg'
      ]);
      $msg = Message::create([ // insert the new message into the database contains a link to the encrypted image
          "user_id" => $r->user_id,
          "chat_id" => $r->chat_id,
          "body" => $img->id,
          "is_photo" => 1
      ]);
      Image::make($r->img)->resize(300,200)->save(public_path('img/msg/').$img->id.".jpg"); // convert base-64 into image
        return $img->id;
        
    }


    public function decMsg(Request $r){
      $messages = array();
      $chat = Chat::whereId($r->chat_id)->first();
      $uid = $chat->creator_id==Auth::user()->id ? $chat->member_id : $chat->creator_id;
      $user = User::whereId($uid)->first();
      if($chat->dim == 3){
        $key = array(array($chat->key[0],$chat->key[2],$chat->key[4]),
                    array($chat->key[6],$chat->key[8],$chat->key[10]),
                    array($chat->key[12],$chat->key[14],$chat->key[16]));
      }else{
        $key = array(array($chat->key[0],$chat->key[2]),
              array($chat->key[4],$chat->key[6]));
      }
      $msgs = Message::where('chat_id',$chat->id)->get();
      foreach($msgs as $msg){
        $time = $msg->created_at->minute * 10;
        $iv = $this->xor1(strval($time).'000',$chat->iv, $chat->dim);
        $msg->body = $this->dec($msg->body, $chat->dim, $key, $iv);
        array_push($messages,$msg);
      }
      return view("chat.index",compact("user","messages","chat"));
    }


  public function enc($msg, $dimension, $key, $iv){
    $result = "";
    if($dimension == 2){
      if((strlen($msg) % 2) == 1){ // apply padding if it need
        $msg .= " ";
      }
      $arr = str_split($msg, 2); // devide the message into blocks with 2 letters in each
    }else{
      if((strlen($msg) % 3) == 1){ // apply padding if it need
        $msg .= "  ";
      }
      elseif((strlen($msg) % 3) == 2){ // apply padding if it need
        $msg .= " ";
      }
      $arr = str_split($msg, 3); // devide the message into blocks with 3 letters in each
    }
 
    foreach($arr as $array){ // for applying CBC mode operation
      $array = $this->xor1($iv, $array, $dimension); // Pi = IV xor Pi-1
      $iv = $this->encrypt($array, $dimension, $key); // IV = Ci
      $result .=$iv; // result = result || Ci
    }
    return $result;
  }
  
  public function xor1($txt1, $txt2, $dim)
  {
    $result = "";
    for($i = 0; $i < $dim; $i++){ // loop depending on the legnth of the two strings
      $l = (($this->search($txt1[$i]) + $this->search($txt2[$i])) % 89); // do xor to the positions of the two letters
      $result .= $this->revSearch($l); // get the letter which has the position x
    }
    return $result;
  }
  
  public function xor2($txt1, $txt2, $dim)
  {
    $result = "";
    for($i = 0; $i < $dim; $i++){ // loop depending on the legnth of the two strings
      $l = ((($this->search($txt2[$i]) - $this->search($txt1[$i])) + 89) % 89); // do xor to the positions of the two letters
      $result .= $this->revSearch($l); // get the letter which has the position x
    }
    return $result;
  }

  public function dec($msg, $dimension, $key, $iv){
    $result = "";
    if($dimension == 2){
      $arr = str_split($msg, 2); // devide the message into blocks with 2 letters in each
      $tmp = array_reverse($arr); // reverse the elements of the array
      array_push($tmp,$iv); // push the IV at the end of the reversed array
      $tmp = array_reverse($tmp); // re-reverse the elements of the array which mean that the IV is in the front
      for($i = 0; $i < count($tmp)-1; $i++){
        $s = $this->decrypt($tmp[$i+1], $dimension, $key); // Pi = K-1 * Ci
        $r = $this->xor2($tmp[$i], $s[0].$s[1], $dimension); // Pi = IV xor Pi
        $result .= $r;
      }
    }
    else{ 
      $arr = str_split($msg, 3); // devide the message into blocks with 3 letters in each
      $tmp = array_reverse($arr); // reverse the elements of the array
      array_push($tmp,$iv); // push the IV at the end of the reversed array
      $tmp = array_reverse($tmp); // re-reverse the elements of the array which mean that the IV is in the front
      for($i = 0; $i < count($tmp)-1; $i++){
        $s = $this->decrypt($tmp[$i+1], $dimension, $key); // Pi = K-1 * Ci
        $r = $this->xor2($tmp[$i], $s[0].$s[1].$s[2], $dimension); // Pi = IV xor Pi
        $result .= $r;
      }
    } 
    return trim($result); // to remove the spaces (padded text)
  }
    
 // Encryption algorithm operations
 function encrypt($text, $dimension, $key){
  $encryptedArray = array();
    if ( $dimension == 2 ) { // to ckeck the encryption is based on 2x2 matrix or 3x3 matrix
      $digrams = $this->getDigrams($text); //return diagrams array 2 letters per element
      $columnVectors = $this->getColumnVectors($digrams, 2); //return vector array contains the position of each letter.
      $premodMatrix = $this->getEncMatrix($columnVectors, 2, $key); //return Encrypted Matrix
      for ($i=0; $i < count($premodMatrix); $i++) { //to perform the mod on the encrypted matrix elements
        $topElement = $premodMatrix[$i][0];
        $bottomElement = $premodMatrix[$i][1];
        array_push($encryptedArray, [$topElement % 89, $bottomElement % 89] );
      } 
      $result = "";
      $R = $this->reverseSearch($encryptedArray, $dimension);//convert the encrypted matrix from positions to letters
      foreach($R as $rr){
        $result .= $rr[0].$rr[1];
      }
    }
    else {
      $trigraph = $this->getTrigraph($text);//return diagrams array 3 letters per element
      $columnVectors = $this->getColumnVectors($trigraph, 3);
      $premodMatrix = $this->getEncMatrix($columnVectors, 3, $key);
      for ($i=0; $i < count($premodMatrix); $i++ ) { //to perform the mod on the encrypted matrix elements
        $topElement = $premodMatrix[$i][0];
        $middleElement = $premodMatrix[$i][1];
        $bottomElement = $premodMatrix[$i][2];
        array_push($encryptedArray, [$topElement % 89, $middleElement % 89, $bottomElement % 89] );
      }       
      $result = "";
      $R = $this->reverseSearch($encryptedArray, $dimension);
      foreach($R as $rr){
        $result .= $rr[0].$rr[1].$rr[2];
      }
    } 
  return $result;  
}

  // Decryption Function
function decrypt($ciphT, $dimension, $keyArray) {
  $decryptedArray = array();
  $determinant = $this->det($keyArray, $dimension); // to get the deteminant of the key matrix
  $multiplicativeInverse = $this->mulInverse(($determinant), 89); // to get the multiplicative inverse of the key
  $adjugateMatrix = $this->getAdjugateMatrix($keyArray, $dimension); // to get the adjusted matrix
  $inverseKeyMatrix = $this->getInverseKeyMatrix($adjugateMatrix, $multiplicativeInverse, $dimension);// to get key inverse
  if ( $dimension == 2 ) {
    $digrams = $this->getDigrams($ciphT);
    $columnVectors = $this->getColumnVectors($digrams, 2);
    // finally, the decryption K-1*C
    for ($i = 0; $i < count($columnVectors); $i++ ) {
      $topElement = ( $inverseKeyMatrix[0][0] * $columnVectors[$i][0] +
                      $inverseKeyMatrix[0][1] * $columnVectors[$i][1]) % 89;
      $bottomElement = ( $inverseKeyMatrix[1][0] * $columnVectors[$i][0] +
                          $inverseKeyMatrix[1][1] * $columnVectors[$i][1]) % 89;
      array_push($decryptedArray,[$topElement, $bottomElement]);
    }
    $result = "";
      $R = $this->reverseSearch($decryptedArray, $dimension);
      foreach($R as $rr){
        $result .= $rr[0].$rr[1];
      }
  }
  else {
    $trigraph = $this->getTrigraph($ciphT);
    $columnVectors = $this->getColumnVectors($trigraph, 3);
    // finally, the decryption
    for ($i = 0; $i < count($columnVectors); $i++ ) {
      $topElement = ( $inverseKeyMatrix[0][0] * $columnVectors[$i][0] +
                      $inverseKeyMatrix[1][0] * $columnVectors[$i][1] +
                      $inverseKeyMatrix[2][0] * $columnVectors[$i][2]) % 89;
      $middleElement = ($inverseKeyMatrix[0][1] * $columnVectors[$i][0] +
                        $inverseKeyMatrix[1][1] * $columnVectors[$i][1] +
                        $inverseKeyMatrix[2][1] * $columnVectors[$i][2]) % 89;
      $bottomElement = ($inverseKeyMatrix[0][2] * $columnVectors[$i][0] +
                        $inverseKeyMatrix[1][2] * $columnVectors[$i][1] +
                        $inverseKeyMatrix[2][2] * $columnVectors[$i][2]) % 89;
      array_push($decryptedArray,[$topElement, $middleElement, $bottomElement]);
    }
    $result = "";
      $R = $this->reverseSearch($decryptedArray, $dimension);
      foreach($R as $rr){
        $result .= $rr[0].$rr[1].$rr[2];
      }
  }
  return $result;
}
  
    // important functions
    
function search($aChar) {
  // $letter = strtolower($aChar);
  $letter = $aChar;
  $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz 0123456789!@#$%^&*()[]{}'/+-=:;.>,<?";
  return strpos($alphabet,$letter); 
}   
function revSearch($pos) {
  $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz 0123456789!@#$%^&*()[]{}'/+-=:;.>,<?";
  return str_split($alphabet)[$pos]; 
} 
function reverseSearch($array, $dimension) {
  $strArray = array();
  $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz 0123456789!@#$%^&*()[]{}'/+-=:;.>,<?";
  $item;

  if ($dimension == 2) {
    for ($i = 0; $i < count($array); $i++ ) {
      $item = $array[$i];
      array_push($strArray,[$alphabet[$item[0]], $alphabet[$item[1]]]);
    }
  }
  else {
    for ($i = 0; $i < count($array); $i++ ) {
      $item = $array[$i];
      array_push($strArray,[$alphabet[$item[0]], $alphabet[$item[1]], $alphabet[$item[2]]]);
    }
  }
  return $strArray;
}
//y
function mulInverse($a, $m){
  $atemp = $a; // Determenant
  $atemp = $atemp % $m;
  if ( $atemp < 0 ) {
    $atemp = $m + $atemp;
  }

  for ($x = 1; $x < $m; $x++) { // 88 times
    if ( (($atemp * $x) % $m) == 1) {
        return $x;
    }
  }

}
//m
function getColumnVectors($xdimgrams, $dimensions) {
  $item;
  $topElement;
  $middleElement;
  $bottomElement;
  $columnVectors = array();

  if ( $dimensions == 2) {
    for ($i = 0; $i < count($xdimgrams); $i++ ) {
      $item = $xdimgrams[$i];
      $topElement = str_split($item)[0];
      $bottomElement = str_split($item)[1];

      //get the index of each letter and push into column vector
      array_push($columnVectors,[$this->search($topElement), $this->search($bottomElement)]);
    }
  }
  else {
    for ($i = 0; $i < count($xdimgrams); $i++ ) {
      $item = $xdimgrams[$i];
      $topElement = str_split($item)[0];
      $middleElement = str_split($item)[1];
      $bottomElement = str_split($item)[2];

      //get the index of each letter and push into column vector
      array_push($columnVectors,[$this->search($topElement), $this->search($middleElement), $this->search($bottomElement)]);
    }
  }

  return $columnVectors;
}
//m
function getEncMatrix($columnVectors, $dimensions, $keyArray) {

  $premodArray = array();
  $kr0 = $keyArray[0][0];
  $kr1 = $keyArray[0][1];
  $kr2 = $keyArray[1][0];
  $kr3 = $keyArray[1][1];
  $cr0;
  $cr1;
  $cr2;
  $topElement;
  $middleElement;
  $bottomElement;
  $counter = 0;

  if ($dimensions == 2) {

    while (count($premodArray) < count($columnVectors) ) {
      for ($i = 0; $i < count($columnVectors); $i++ ) {
        $cr0 = $columnVectors[$i][0];
        $cr1 = $columnVectors[$i][1];
        $topElement = ($kr0 * $cr0) + ($kr1 * $cr1);
        $bottomElement = ($kr2 * $cr0) + ($kr3 * $cr1);
        array_push($premodArray,[$topElement,$bottomElement]);
      }
      $counter++;
    }
  }
  else {

    //  while (count($premodArray) < count($columnVectors) ) {

      for ($i = 0; $i < count($columnVectors); $i++) {

        $cr0 = $columnVectors[$i][0];
        $cr1 = $columnVectors[$i][1];
        $cr2 = $columnVectors[$i][2];
        $topElement = $keyArray[0][0] * $cr0 + $keyArray[0][1] * $cr1 + $keyArray[0][2] * $cr2;
        $middleElement = $keyArray[1][0] * $cr0 + $keyArray[1][1] * $cr1 + $keyArray[1][2] * $cr2;
        $bottomElement = $keyArray[2][0] * $cr0 + $keyArray[2][1] * $cr1 + $keyArray[2][2] * $cr2;
        array_push($premodArray,[$topElement, $middleElement, $bottomElement]);
      }
        $counter++;
    // }
  }

  return $premodArray;
}
//n
function getDigrams($aString) {
  $input = $aString; // hima
  $tempDigram = "";
  $textLength = strlen($input);
  $digramLength;
  $letter;
  $array = array();
  $count = 0;
  while ($count < $textLength) {  // 0<4 , 1<4 , 2<4 , 3<4

    $digramLength = strlen($tempDigram); // 0,1,/ 0,1
    $letter = str_split($input)[$count]; // h,i,/ m,a

    if ($digramLength < 2) { // T, F, T ,F
      $tempDigram .= $letter; // h,i,/ m

      if (strlen($tempDigram) == 2) { // F, T, F
        array_push($array,$tempDigram); // hi,
        $tempDigram = "";
      }
    }
    else {
      array_push($array,$tempDigram);
      $tempDigram = "";
      $tempDigram .= $letter;
    }

    // pad if at odd  ending
    if ( $count == $textLength - 1 && $textLength % 2 != 0 ) { // F, F, F
      $tempDigram .= "$";
      array_push($array,$tempDigram);
    }
    $count++;  // 1, 2, 3
  }

  return $array;
}
//n
function getTrigraph($aString) {
  $input = $aString;
  $tempTrigram = "";
  $textLength = strlen($input);
  $trigramLength;
  $letter;
  $array = array();
  $count = 0;

  while ( $count < $textLength ) {

    $trigramLength = strlen($tempTrigram);
    $letter = $input[$count];

    if ($trigramLength < 3) {
      $tempTrigram .= $letter;

      if (strlen($tempTrigram) == 3) {
        array_push($array,$tempTrigram);
        $tempTrigram = "";
      }
    }
    else {
    array_push($array,$tempTrigram);
      $tempTrigram = "";
      $tempTrigram .= $letter;
    }

    // pad if at odd  ending
    if ( $count == $textLength - 1 && $textLength % 3 != 0 ) {

      if ( strlen($tempTrigram) == 1 ) {
        $tempTrigram .= "$$";
      }
      else {
        $tempTrigram .= "$";
      }
      array_push($array,$tempTrigram);
    }
    $count++;
  }

  return $array;
}
//y
function getAdjugateMatrix($keyArray, $dim){
  $adjugateMatrix = array();
  if($dim == 2){
    array_push($adjugateMatrix,[$keyArray[1][1], -$keyArray[0][1] + 89]);
    array_push($adjugateMatrix,[-$keyArray[1][0] + 89, $keyArray[0][0]]);
  }else{
    // cofactor calculation
    $cf00 = $keyArray[1][1] * $keyArray[2][2] - $keyArray[1][2] * $keyArray[2][1];
    $cf01 = -($keyArray[1][0] * $keyArray[2][2] - $keyArray[2][0] * $keyArray[1][2]);
    $cf02 = $keyArray[1][0] * $keyArray[2][1] - $keyArray[1][1] * $keyArray[2][0];
    $cf10 = -($keyArray[0][1] * $keyArray[2][2] - $keyArray[0][2] * $keyArray[2][1]);
    $cf11 = $keyArray[0][0] * $keyArray[2][2] - $keyArray[0][2] * $keyArray[2][0];
    $cf12 = -($keyArray[0][0] * $keyArray[2][1] - $keyArray[0][1] * $keyArray[2][0]);
    $cf20 = $keyArray[0][1] * $keyArray[1][2] - $keyArray[0][2] * $keyArray[1][1];
    $cf21 = -($keyArray[0][0] * $keyArray[1][2] - $keyArray[0][2] * $keyArray[1][0]);
    $cf22 = $keyArray[0][0] * $keyArray[1][1] - $keyArray[0][1] * $keyArray[1][0];

    array_push($adjugateMatrix,[$cf00,$cf01,$cf02]);
    array_push($adjugateMatrix,[$cf10,$cf11,$cf12]);
    array_push($adjugateMatrix,[$cf20,$cf21,$cf22]);

    //find the mods
    for ($i = 0; $i < count($adjugateMatrix); $i++ ) {
      if ($adjugateMatrix[$i][0] < 0) {
        $adjugateMatrix[$i][0] = ($adjugateMatrix[$i][0] % 89) + 89;
      }
      else {
        $adjugateMatrix[$i][0] = $adjugateMatrix[$i][0] % 89;
      }

      if ($adjugateMatrix[$i][1] < 0) {
          $adjugateMatrix[$i][1] = ($adjugateMatrix[$i][1] % 89) + 89;
      }
      else {
          $adjugateMatrix[$i][1] = $adjugateMatrix[$i][1] % 89;
      }

      if ($adjugateMatrix[$i][2] < 0) {
          $adjugateMatrix[$i][2] = ($adjugateMatrix[$i][2] % 89) + 89;
      }
      else {
          $adjugateMatrix[$i][2] = $adjugateMatrix[$i][2] % 89;
      }

    }
  }

  return $adjugateMatrix;
}
//y
function getInverseKeyMatrix($adjugateMatrix, $multiplicativeInverse, $dim){
  $inverseKeyMatrix = array();
  if($dim == 2){
    for ( $i = 0; $i < count($adjugateMatrix); $i++ ) {
      array_push($inverseKeyMatrix,[($adjugateMatrix[$i][0] * $multiplicativeInverse)
            % 89, ($adjugateMatrix[$i][1] * $multiplicativeInverse) % 89]);
    }
  }else{
    for ($i = 0; $i < count($adjugateMatrix); $i++ ) {
      $topElement = ($multiplicativeInverse * $adjugateMatrix[$i][0]) % 89;
      $middleElement = ($multiplicativeInverse * $adjugateMatrix[$i][1]) % 89;
      $bottomElement = ($multiplicativeInverse * $adjugateMatrix[$i][2]) % 89;
      array_push($inverseKeyMatrix,[$topElement, $middleElement, $bottomElement]);
    }
  }
  return $inverseKeyMatrix;
}
function invertable($key, $dim){
  $det = $this->det($key, $dim);
  $gcd = $this->gcd($det, 89);
  if($det != 0 && $gcd == 1){
      return true;
  }else{
      return false;
  }

}
function gcd($a, $b){
  $tmpa=$a; 
  $tmpb=$b; 
  while ($tmpb > 0){
      $r = $tmpa % $tmpb;
      $tmpa = $tmpb;
      $tmpb = $r;
  } 
  return $tmpa;
}
function det($array, $dim){
  $determinant = 0;
  if($dim == 2){
      $determinant = $array[0][0] * $array[1][1] -
      $array[0][1] * $array[1][0];
  }else{
      $leftElement = $array[0][0] * ($array[1][1] * $array[2][2] -
            $array[1][2] * $array[2][1]);
      $middleElement = $array[0][1] * ($array[1][0] * $array[2][2] -
            $array[1][2] * $array[2][0]);
      $rightElement = $array[0][2] * ($array[1][0] * $array[2][1] -
            $array[1][1] * $array[2][0]);

      $determinant = $leftElement - $middleElement + $rightElement;
  }
  return $determinant;
  
}

}
