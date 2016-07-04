Dear {{$User}},
<br><br>
Thank you for visiting Sangam Lifespaces. 
<br><br>
<p>
    Based on your interest in our property <b>{{$Project}}</b>, one of our sales managers will get in touch with you to seek your appointment at your convenience. 
    <br>
    Should you require any further assistance please contact us on <Our call center number> or visit our website : www.sangamlifespaces.com 
</p>
<?php
$fileName = '';
switch ($ProjectId) {
    case 1 :$fileName = 'The Luxor by ( Sangam Lifespaces ).pdf';
        break;
    case 2 :$fileName = 'Veda.pdf';
        break;
    case 3 :$fileName = 'NERO PDF.pdf';
        break;
    case 4 :$fileName = 'Prive-Maison.pdf';
        break;
}
?>
<P>
    You can download broucher by clicking the link: <a href="http://crmsangam.clu.pw/public/admin/ProjectStorage/{{@$fileName}}">Download Broucher</a>
</P><br>

Thank you for your time,<br>
<img src="{{URL::to('/')."public/admin/images/sangamLogo.png"}}"></img>