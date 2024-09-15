<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shipping;

class UpdateShippingStatus extends Command
{
    protected $signature = 'shipping:update-status';
    protected $description = 'Update the status of all shipments based on tracking information';

    public function handle()
    {
        $shippings = Shipping::where('shipping_status', '!=', 'Delivered')->get();

        foreach ($shippings as $shipping) {
            try {
                $trackingInfo = $shipping->trackShipment();
                $shipping->update(['shipping_status' => $trackingInfo['status']]);
            } catch (\Exception $e) {
                $this->error('Failed to update shipping status for ID ' . $shipping->id . ': ' . $e->getMessage());
            }
        }

        $this->info('Shipping statuses updated successfully.');
    }
}
