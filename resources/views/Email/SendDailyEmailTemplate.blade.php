Dear {{@$name}},
<br>
The following are all inquiries across all our projects as on {{date('d-m-Y')}}.
<br><br>
<table class="table table-bordered table-striped Ntable" border="1" cellspacing="0" cellpadding="0" id="DataTables_Table_0" role="grid" style="text-align: center" aria-describedby="DataTables_Table_0_info">
    <thead>
        <tr>

            <th>Sr No</th>
            <th>Created At</th>
            <th>Customer Name</th>
            <th>Contact No</th>
            <th>Inquiry Status</th>
            <th>Project/Phase</th>
            <th>Inquiry Source</th>
            <th>Sales Agent</th>
            <th>Sales Manager</th>
            <th>Last Updated Date</th>

        </tr>
    </thead>
    <tbody>
        @if($inquiry)
        @foreach($inquiry as $k=>$v)

        <tr>
            <td>{{$k+1}}</td>
            <td>{{isset($v->created_at) && (strtotime($v->created_at) != strtotime('0000-00-00'))  ?ConvertGMTToLocalTimezone($v->created_at,'Asia/Calcutta'):''}}</td>
            <td>{{@$v->user->first_name.' '.@$v->user->last_name}}</td>

            <td>{{@$v->user->mobile}}</td>
            <!--<td><a title='Click to call' href='Javascript:;'>{{@$v->user->mobile}}</a></td>-->
            <td>{{@$v->InquiryStatus->name}}</td>
            <td>{{@$v->Project->name}}</td>                
            <td>{{@$v->InquirySource->name}}</td>
            <td>{{@$v->InquiryUser->user->first_name.' '.@$v->InquiryUser->user->last_name}}</td>
            <td>{{@$v->SalesManager->first_name.' '.@$v->SalesManager->last_name}}</td>
            <td>{{isset($v->updated_at) && (strtotime($v->updated_at) != strtotime('0000-00-00'))  ?date("d-m-Y", strtotime($v->updated_at)):''}}</td>

        </tr>
        @endforeach
        @endif


    </tbody>
</table>
<br><br>
