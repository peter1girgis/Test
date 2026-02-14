<?php

namespace Database\Seeders;

use App\Models\requirements;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['key' => 'phone',       'label' => 'Phone Number',     'type' => 'phone'],
            ['key' => 'receipt',     'label' => 'Receipt Image',    'type' => 'file'],
            ['key' => 'payment_id',  'label' => 'Payment ID',       'type' => 'text'],
            ['key' => 'payer_name',  'label' => 'Payer Name',       'type' => 'text'],
            ['key' => 'note',        'label' => 'Note',             'type' => 'textarea'],
            ['key' => 'paid_on',     'label' => 'Payment Date',     'type' => 'date'],
            ['key' => 'amount_conf', 'label' => 'Amount Confirmed', 'type' => 'number'],
        ];
        


        foreach ($items as $item) {
            requirements::firstOrCreate(['key' => $item['key']], $item);
        }
    }
}
