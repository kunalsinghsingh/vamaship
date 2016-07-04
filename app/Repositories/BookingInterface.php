<?php

namespace Repositories;

interface BookingInterface {

   public function selectAll();
    
     public function save();


      public function find($id);
        

     public function update($id);

     public function delete($id);
    
}

?>