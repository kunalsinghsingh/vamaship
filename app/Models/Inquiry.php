<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model {

    //
    protected $table = 'enquiries';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
    public function InquirySource() {
        return $this->belongsTo('App\Models\InquirySource', 'contact_source_id');
    }

    public function CustomerSource() {
        return $this->belongsTo('App\Models\CustomerSource', 'customer_source_id');
    }

    public function InquiryUsers() {
        return $this->belongsTo('App\Models\InquiryUsers');
    }

    public function Project() {
        return $this->belongsTo('App\Models\Project');
    }
    public function Building() {
        return $this->belongsTo('App\Models\Building');
    }

    public function InquiryStatus() {
        return $this->belongsTo('App\Models\InquiryStatus', 'status');
    }

    public function InquiryUser() {
        return $this->hasMany('App\Models\InquiryUser', 'enquiry_id');
    }

    function SalesManager() {
        return $this->belongsto('App\Models\User', 'designated_sales_manager');
    }

    public function City() {
        return $this->belongsto('App\Models\City', 'city');
    }
    public function State() {
        return $this->belongsto('App\Models\State', 'states');
    }

    public function Flat() {
        return $this->belongsto('App\Models\Flat', 'flat_id');
    }

    public function Broker() {
         return $this->belongsto('App\Models\User', 'agent_id');
    }
    public function Campaign()
    {
        return $this->belongsTo('App\Models\Campaign','campaign_id');
    }
    public function Followups()
    {
        return $this->hasMany('App\Models\Followups','enq_id');
    }
    public function calls()
    {
        return $this->hasMany('App\Models\CallDetails','enq_id');
    }
    public function Budget()
    {
        return $this->belongsTo('App\Models\Budget','budget');
    }
}
