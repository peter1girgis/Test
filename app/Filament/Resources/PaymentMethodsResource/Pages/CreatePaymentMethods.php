<?php

namespace App\Filament\Resources\PaymentMethodsResource\Pages;

use App\Filament\Resources\PaymentMethodsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethods extends CreateRecord
{
    protected static string $resource = PaymentMethodsResource::class;
}
