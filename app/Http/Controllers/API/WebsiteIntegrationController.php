<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Repositories\WebsiteInterface;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\User;
use Input,
    DB;

class WebsiteIntegrationController extends Controller {

    protected $WebsiteRepo;

    public function __construct(WebsiteInterface $WebsiteRepo) {
        $this->WebsiteRepo = $WebsiteRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function create() {


      $post_fields = json_encode(Input::all());
       
//        echo $post_fields = '{"name":"Atul","email":"atul@infiniteit.biz","mobile":"12352555","message":"test"}';
        $post_fields = json_decode($post_fields);
        $form_field = $post_fields;
        DB::table('api_response')->insert(
                array('api_response' => json_encode(Input::all()), 'type' => 'SangamLifeSpaces', 'created_at' => date('Y-m-d H:i'))
        );

        if (isset($form_field->mobile) && is_numeric($form_field->mobile)) {

            $customer = $this->WebsiteRepo->getUser('', $form_field->mobile);
            $saveProfile = new User();
            $adminUserId = 15;
            if (!$customer) {
                //$name = explode(' ', $form_field->name);
                $saveProfile->status = 1;
                $saveProfile->first_name = $form_field->name;
                //  $saveProfile->last_name = $form_field->name;
                $saveProfile->user_group = 'customer';
                $saveProfile->email = $form_field->email;
                $saveProfile->mobile = $form_field->mobile;
                $saveProfile->created_by = $adminUserId;
                $saveProfile->save();
                $customerId = $saveProfile->id;
            } else {
                $customerId = $customer->id;
            }
            $inquiry = $this->WebsiteRepo->checkExistingInquiry($form_field->mobile);
            if (!$inquiry) {
                $saveInquiry = new Inquiry();
                $saveInquiry->created_by = $adminUserId;
                $saveInquiry->user_id = $customerId;
                $saveInquiry->cc_callid = '';
                $saveInquiry->status = 7;
                $saveInquiry->contact_source_id = 14;
                $saveInquiry->operator_remark = $form_field->message;
                $saveInquiry->save();
                $inquiryId = $saveInquiry->id;
            } else {
                $this->WebsiteRepo->updateInquiry($inquiry->enquiry_id);
                $inquiryId = $inquiry->enquiry_id;
            }
            $this->WebsiteRepo->createInquiryUser($inquiryId, $adminUserId);
        }
    }

    public function test1() {

        $ans = array();
        $ans['name'] = 'man';
        $ans['email'] = 'man@man.com';
        $ans['mobile'] = '9874563210';
        $ans['message'] = 'sdasdasds';
        $json_post_data = json_encode($ans);
        $postUrl = 'http://localhost/leadCrm/Sangam-contact-us';
//        $postUrl = 'http://crmsangam.clu.pw/Sangam-contact-us';
        $ch = curl_init($postUrl);
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTREDIR, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        echo $result = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);
    }

    public function test() {
        echo '123';
        $ans = array();
        $ans['name'] = 'man';
        $ans['email'] = 'man@man.com';
        $ans['mobile'] = '9874523088';
        $ans['message'] = 'sdasdasds';
        $json_post_data = json_encode($ans);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://localhost/leadCrm/Sangam-contact-us');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "name=man1&email=man@man.com&mobile=778777785&message=sdasdasds");

// receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $server_output = curl_exec($ch);

        curl_close($ch);

// further processing ....
        echo $server_output;
    }

}
