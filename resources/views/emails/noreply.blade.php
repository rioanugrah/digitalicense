<p>Terima kasih {{ $billing_name }} telah belanja di Digital License.</p>
<p>Billing Code : {{ $billing_code }}</p>
<p>Billing Name : {{ $billing_name }}</p>
<p>Billing Email : {{ $billing_email }}</p>
<p>Billing Amount : {{ 'Rp. '.number_format($billing_amount,0,',','.') }}</p>
<p>Billing Status : {{ $billing_status }}</p>
{!! $comment !!}
<br>
<p>Regards</p>
<p>Team Digital License</p>
