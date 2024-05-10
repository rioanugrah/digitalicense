<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Notifications\NotificationNotif;
use App\Models\User;
use App\Models\Orders;
use App\Models\OrdersDetail;
use Notification;

class TripayController extends Controller
{
    function __construct(
        Orders $orders,
        OrdersDetail $orders_detail
    ){
        $this->orders = $orders;
        $this->orders_detail = $orders_detail;

        if (env('TRIPAY_IS_PRODUCTION') == false) {
            $this->tripay_api_key = env('TRIPAY_API_KEY_SANDBOX');
            $this->tripay_private_key = env('TRIPAY_PRIVATE_KEY_SANDBOX');
            $this->tripay_merchant = env('TRIPAY_MERCHANT_SANDBOX');
            $this->tripay_url = env('TRIPAY_SANDBOX');
        }else{
            $this->tripay_api_key = env('TRIPAY_API_KEY_PRODUCTION');
            $this->tripay_private_key = env('TRIPAY_PRIVATE_KEY_PRODUCTION');
            $this->tripay_merchant = env('TRIPAY_MERCHANT_PRODUCTION');
            $this->tripay_url = env('TRIPAY_PRODUCTION');
        }

    }

    public function getPayment()
    {
        $apiKey = $this->tripay_api_key;
        $url_tripay = $this->tripay_url;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $url_tripay.'/merchant/payment-channel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        return $response ? $response : $error;
    }

    public function requestTransaction(
        $product,
        $method,$amount,
        $billing_name,$email,$phone,
        $merchantRef,$url
        )
    {
        $apiKey       = $this->tripay_api_key;
        $privateKey   = $this->tripay_private_key;
        $merchantCode = $this->tripay_merchant;
        $merchantRef  = $merchantRef;
        $amount       = $amount;
        $url_tripay   = $this->tripay_url;
        $return_url   = $url;

        $data = [
            'method'         => $method,
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $billing_name,
            'customer_email' => $email,
            'customer_phone' => $phone,
            'order_items'    => [
                [
                    'name'        => $product,
                    'price'       => $amount,
                    'quantity'    => 1,
                    // 'product_url' => 'https://tokokamu.com/product/nama-produk-1',
                    // 'image_url'   => 'https://tokokamu.com/product/nama-produk-1.jpg',
                    ]
                ],
            // 'return_url'   => 'https://domainanda.com/redirect',
            'callback_url'   => env('APP_URL').'/api/callback',
            'return_url'   => $return_url,
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => hash_hmac('sha256', $merchantCode.$merchantRef.$amount, $privateKey)
            ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $url_tripay.'/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);
        return $response ? $response : $error;
    }

    public function detailTransaction($reference)
    {
        $apiKey = $this->tripay_api_key;

        $payload = ['reference'	=> $reference];

        $curl = curl_init();

        $url_tripay = $this->tripay_url;

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $url_tripay.'/transaction/detail?'.http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);
        return $response ? $response : $error;
    }

    public function handle(Request $request)
    {
        // dd($request->all());
        $privateKey = $this->tripay_private_key;
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        // dd($callbackSignature);
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $privateKey);
        if ($signature !== (string) $callbackSignature) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid signature',
            ]);
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            return Response::json([
                'success' => false,
                'message' => 'Unrecognized callback event, no action was taken',
            ]);
        }

        $data = json_decode($json);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid data sent by tripay',
            ]);
        }

        $invoiceId = $data->merchant_ref;
        $tripayReference = $data->reference;
        $status = strtoupper((string) $data->status);

        if ($data->is_closed_payment === 1) {
            $order = $this->orders->where('order_code',$data->merchant_ref)->first();
            // $transaction = $this->transactions->where('transaction_code',$data->merchant_ref)
            //                                 // ->where('status','Unpaid')
            //                                 ->first();
            if (!$order) {
                return Response::json([
                    'success' => false,
                    'message' => 'No invoice found or already paid: ' . $invoiceId,
                ]);
            }
            switch ($status) {
                case 'PAID':
                    $order->update([
                        // 'transaction_reference' => $data->reference,
                        'status' => 'Paid'
                    ]);
                    // $user = User::where('id',1)
                    //                 // ->orWhere('id',auth()->user()->id)
                    //                 ->get();
                    $user = auth()->user();
                    $notif = [
                        'id' => $input['id'],
                        'url' => route('transactions.detail',['order_code' => $order->order_code]),
                        'title' => 'Pesanan Baru',
                        'message' => 'Kode Order '.$order->order_code.' - Berhasil Melakukan Pembayaran Sejumlah '.'Rp. '.number_format($order->price,0,',','.'),
                        'color_icon' => 'success',
                        'icon' => 'bx bx-check-circle',
                        'publish' => Carbon::now(),
                    ];
                    Notification::send($user,new NotificationNotif($notif));
                    // $notifMail = $this->sendMail;
                    // $notifMail->sendMail(
                    //     $transaction->status,$transaction->transaction_code,$transaction->transaction_price,
                    //     json_decode($transaction->transaction_order)->first_name.' '.json_decode($transaction->transaction_order)->last_name,
                    //     json_decode($transaction->transaction_order)->email,json_decode($transaction->transaction_order)->phone,json_decode($transaction->transaction_order)->address,
                    //     $transaction->transaction_qty,$transaction->transaction_reference,$transaction->verifikasi_tiket->kode_tiket
                    // );
                    break;

                case 'EXPIRED':
                    $order->update([
                        // 'transaction_reference' => $data->reference,
                        'status' => 'Expired'
                    ]);
                    break;

                case 'FAILED':
                    $order->update([
                        // 'transaction_reference' => $data->reference,
                        'status' => 'Failed'
                    ]);
                    break;

                default:
                    return Response::json([
                        'success' => false,
                        'message' => 'Unrecognized payment status',
                    ]);
            }

            return Response::json(['success' => true]);
        }
    }
}
