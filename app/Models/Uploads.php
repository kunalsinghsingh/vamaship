<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uploads extends Model {

    protected $table = 'uploads';

   public function uploadedUser() {
      return $this->belongsTo('App\Models\User', 'uploaded_by');
    }
   public function campaignName() {
      return $this->belongsTo('App\Models\Campaign', 'campaign_id');
    }

}
