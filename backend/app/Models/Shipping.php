<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shipping';

    protected $fillable = [
        'order_id',
        'seller_id',
        'shipping_address',
        'city',
        'state',
        'postal_code',
        'country',
        'shipping_type',
        'shipping_cost',
        'shipping_carrier',
        'shipping_method',
        'shipping_status',
        'recipient_name',
        'estimated_delivery_date',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function trackShipment()
    {
        $client = new Client();
        $carrierApiUrl = $this->getCarrierApiUrl();

        $response = $client->get($carrierApiUrl, [
            'query' => [
                'tracking_number' => $this->tracking_number,
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.carrier_api.token'),
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    private function getCarrierApiUrl()
    {
        // Example for UPS
        switch ($this->shipping_carrier) {
            case 'UPS':
                return 'https://onlinetools.ups.com/track/v1/details/' . $this->tracking_number;
            case 'FedEx':
                return 'https://api.fedex.com/track/v1/' . $this->tracking_number;
            // Add other carriers here
            default:
                throw new \Exception('Unsupported carrier');
        }
    }
}
