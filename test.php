<?php session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once("function.php");
require './vendor/autoload.php';
// error_reporting(1);
date_default_timezone_set("Asia/Calcutta");

$deduct_amt = 0;
$refund = 0;
$bill_id = $_SESSION['bill_id'] = $_GET['b_id'] = $_GET['abh_id'];

$select = "*";
$from = "ambit_booking_hotel";
$condition = array("abh_id" => $bill_id);
$hotel_order_details = getDetails(doSelect1($select, $from, $condition));
//  print_r($hotel_order_details);die();
$h_id = $hotel_order_details[0]['aah_id'];
$checkin_date = $hotel_order_details[0]['checkin_date'];
$customer_id = $hotel_order_details[0]['ac_id'];

if ($hotel_order_details['0']['booking_status'] != "0" && $hotel_order_details['0']['confirmation'] != 1) {

    $total_price = $hotel_order_details[0]['total_price'];
    $date = date('Y-m-d h:i:s');
    $earlier = new DateTime($date);
    $later = new DateTime($checkin_date);


    $b_date = $hotel_order_details[0]['checkin_date'];









    $day_diff = $earlier->diff($later)->format("%r%a"); //3

    if ($day_diff >= 30) {
        //   echo 'total refund';
        $deduct_amt = 0;
    } elseif ($day_diff >= 14) {
        //   deduction is 10 per
        // $deduct_amt = $total_price * 0.10;
        // $refund = $total_price - $deduct_amt;

        $deduct_amt = 0;
    } elseif ($day_diff >= 1) {
        //   deduction is 15
        // $deduct_amt = $total_price * 0.15;
        // $refund = $total_price - $deduct_amt;

        $deduct_amt = 0;
    } elseif ($day_diff == 0) {
        //   deuct 25%   
        // $deduct_amt = $total_price * 0.25;

        $deduct_amt = $total_price;
        $refund = $total_price - $deduct_amt;
    }




    $sql = "UPDATE ambit_booking_hotel SET 
                                                    total_price='0', confirmation ='3', no_room ='0', refund_amt = '$refund', cancel_date = '$date', booking_status='0' 
                                                    WHERE abh_id = '$bill_id'";
    if (mysqli_query($conn, $sql)) {



        //   push notification code start here ================

        //   push notification code end here ================


        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'weetu.in';                     //Set the SMTP server to send 
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'noreply@weetu.in';                     //SMTP username
        $mail->Password   = 'Ranchi@123';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       =  465;                                    //TCP port to connect to; 
        $mail->setFrom('noreply@weetu.in', 'weetu');
        $mail->addReplyTo('noreply@weetu.in', 'Information');




        $room_type = getRoomType($r['room_type']);






        $c = mysqli_query($conn, "SELECT * FROM `ambit_customer` where ac_id=" . $customer_id);
        $customer = mysqli_fetch_array($c);

        // $cc = mysqli_query($conn, "SELECT client_mail, bdm_name, asm_name FROM `ambit_add_hotel` where aah_id =" . $_SESSION["id"]);
        // $data = mysqli_fetch_array($cc);







        //  echo $customer['email'];
        //  echo getCustomerName($customer['ac_id']);
        // echo $hotel_order_details['aah_id'];
        // echo getHotelName('1');
        //  die();

        // user
        $mail->addAddress($customer['email'], getCustomerName($customer['ac_id']));     //Add a recipient
        // $mail->addCC($data['client_mail']);
        // $mail->addCC($data['bdm_name']);
        // $mail->addCC($agent['email']);
        $mail->addCC('prince.aircel@gmail.com');
        $mail->addCC('santo18186@gmail.com');
        $mail->addBCC('noreply@weetu.in');

        $mail->isHTML(true);
        //Set email format to HTML
        // $pending = $r['total_price'] - $r['advance_amt'];
        $mail->Subject = 'Weetu: Booking Cancellation -' . 'BOOK' .  str_pad($bill_id, 6, '0', STR_PAD_LEFT);
        // $mail->Body = '<h2>Booking is cancel</h2><br>Booking Information<br><b>Hi '.getCustomerName($hotel_order_details['ac_id']).'</b><br> Hotel Name: '.getHotelName($hotel_order_details['aah_id'])
        // .'<br> Booking ID: BOOK' .  str_pad($bill_id, 6, '0', STR_PAD_LEFT)
        // .'<br>Check-In: '.$hotel_order_details['checkin_date']
        // .'<br>Check-out: '.$hotel_order_details['chekout_date']
        // .'<br>Adult: '.$hotel_order_details['adult']
        // .'<br>Child: '.$hotel_order_details['children']
        // .'<br>Night Hold: '.$hotel_order_details['night_hold']
        // .'<br>Booking Date: '.$hotel_order_details['booking_date']
        // .'<br>Room Type: '.$room_type
        // .'<br>Refund Amount: '.$refund
        // // .'<br>GST: '.$r['gst']
        // // .'<br>Paid Amount : '.$r['advance_amt']
        // // .'<br>Pending Amount : '.$pending
        // // .'<br>Total Room : '.$r['no_room']
        // .'<br><br> Thanks <br> Weetu Team. Visit Again.'
        // ;
        $c_name = getCustomerName($hotel_order_details[0]['ac_id']);
        $hotel_name = getHotelName($hotel_order_details[0]['aah_id']);
        $chin = $hotel_order_details[0]['checkin_date'];
        $chout = $hotel_order_details[0]['chekout_date'];
        $adutl = $hotel_order_details[0]['adult'];
        $child = $hotel_order_details[0]['children'];

        $mail->Body = '<body style="   width: 700px; border: border-box; background-color:lightred;">
    <mail class="container">

        
        <div style=" color: #0e4b66;
                     text-align: center;
                     justify-content: center;">
            <h1>Booking is Cancelled</h1>
        </div>
        <div style=" text-align: center;
                     justify-content: center;">
            <h3 style="color: #0e4b66;">Hi ' . $c_name . ',</h3>
            <p style=" color: #0e4b66;
                       margin: 0 auto;
                       width: 60%;">Hotel Name: ' . $hotel_name . '
                                             </p>
            <br>

          
        </div><br>
        <div class="booking-details" style=" width: 100%;
                    height: 50vh;">
            <div class="details" style="  width: 60%;
                   height: 100%;
                   border-style: dotted;
                   border-radius: 10px;
                   margin: 0 auto;">
                <div class="details-div1" style=" text-align: center;
                      justify-content: center;
                      color: #0e4b66;">

                    <h2>Your Cancellation...!</h2>
                    <p>Your Cancellation request is received with us, your refund will be credited to your bank and cancellation details are below.</p>
                </div>
                <div class="details-div2" style=" color: #0e4b66;
                       margin: 0 10%;">
                    <label for="#">Name:-' . $c_name . '</label><br>
                    <label for="#">Id:- BOOK' .  str_pad($bill_id, 6, '0', STR_PAD_LEFT) . '</label><br>
                                            
                    <label for="#">Check-In:' . $hotel_order_details[0]['checkin_date'] . '</label><br>
                    <label for="#">Check-Out:' . $hotel_order_details[0]['chekout_date'] . '</label><br> 
                    <label for="#">Adult: ' . $hotel_order_details[0]['adult'] . '</label><br> 
                    <label for="#">Child: ' . $hotel_order_details[0]['children'] . '</label><br> 
                    <label for="#">Number of Days: ' . $hotel_order_details[0]['night_hold'] . '</label><br> 
                    <label for="#">Booking Date: ' . $hotel_order_details[0]['booking_date'] . '</label><br>
                   

                    
                    <label for="#">Refund Amount: ' . $refund . '</label><br>
                    
                  
                    
                        
                </div>
            </div>
        </div>
        <br>
      
        
        
        
        
                    <div class="social-icons" style=" width: 100%;
                    height: 10vh;
                    background-color: lightgray;
                    border-radius: 10px;">
      
       <div style="display:flex; align-content: center;
    justify-content: center;
    align-items: center;">
      <div style="width:50%; display:flex; justify-content:center; align-item:center;">

        <a href="#"><img src="https://weetu.in/images/App_Store.png" alt="" style="height: 40px; width:100px; padding:10px; "></a>
        <a href="#"><img src="https://weetu.in/images/Google.png" alt="" style="height: 40px; width:100px; padding:10px;"></a>
      </div>
      <div style="width:50%; display:flex; justify-content:center; align-item:center;">
        <div class="div-sicon" style=" display: flex; flex-direction: row-reverse;
                        text-align: center;
                        justify-content: center;">
          <div class="icon-div">
            <a href="https://www.facebook.com/Weetu-Deals-104107242074035" style ="padding: 0 10px;"> <img src="https://weetu.in/images/fb.png" alt="facebook" style="margin-top: 10px;"></a>
          </div>
          <div class="icon-div">
            <a href="https://www.instagram.com/weetudeals/" style ="padding: 0 10px;"> <img src="https://weetu.in/images/insta.png" alt="instagram"  style="margin-top: 10px;"></a>

          </div>
          <div class="icon-div">
            <a href="mailto:WeetuServices@gmail.com" style ="padding: 0 10px;"> <img src="https://weetu.in/images/goo.png" alt="google"  style="margin-top: 10px;"></a>

          </div>
          <div class="icon-div">
            <a href="https://twitter.com/weetuservices" style ="padding: 0 10px;"> <img src="https://weetu.in/images/twi.png" alt="twiter" style=" margin-top: 10px;"></a>

          </div>
        </div>
      </div>
      </div>

      
    </div>
    </mail>
</body>';
        $mail->AltBody = 'Booking ' . 'BOOK' .  str_pad($bill_id, 6, '0', STR_PAD_LEFT);
        $mail->send();
        header('location:cancel_invoice.php');
    }
} else {
    $_SESSION['error1'] = "error";
    //  echo "Already Checked IN";
    header('location:booking.php');
}
