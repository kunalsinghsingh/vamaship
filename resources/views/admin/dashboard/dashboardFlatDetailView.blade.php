<div class="modal-body">
    <div  class="table-responsive">
        <table class="table enqtable"> 
            
            <tr>
                <td>Flat No: </td>
                <td>{{$flats->custom_flat_id}} </td>
                <td></td>
                <td>Project/Phase:</td>
                <td>{{$flats->Building->Project->name}}</td>
            </tr>
            <tr>
                <td>Building Name:</td>
                <td>{{$flats->Building->name}}</td>
                <td></td>
                <td>Flat Status: </td>
                <td>{{$flats->FlatStatus->name}}</td>

            </tr>
            <tr>
                <td>Flat Type: </td>
                <td>{{$flats->FlatType->name}}</td>
                <td></td>
                <td>Area:</td>
                <td>{{$flats->flat_area}} sqft</td>
            </tr>
            @if($flats->flat_status=='Booked')
            <tr>
                <td>Customer Name: </td>
                <td>{{@$flats->Inquiry->user->first_name}} {{@$flats->Inquiry->user->last_name}}</td>
                <td></td>
                <td>Email: </td>
                <td>{{@$flats->Inquiry->user->email}}</td>
            </tr>

            <tr>
                <td> Mobile:</td>
                <td>{{@$flats->Inquiry->user->mobile}}</td>
                <td></td>
                <td>Booking Amount</td>
                <td>{{$flats->final_booking_amount}}</td>
            </tr>
            <tr>
                <td> Booking Date:</td>
                <td>{{date('d-m-Y',strtotime($flats->booking_date))}}</td>
                <td></td>
               
            </tr>
            @endif
            <tr>
                <td> Sales Agent:</td>
                <td>{{@$flats->Inquiry->InquiryUser->User->first_name}} {{@$flats->Inquiry->InquiryUser->User->last_name}}</td>
                <td></td>
            </tr>

        </table>
<!--        <div  class="text-justify"> <strong>Remark:</strong> 

        </div>-->
    </div>
</div>
