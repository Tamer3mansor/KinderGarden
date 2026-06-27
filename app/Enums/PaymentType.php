<?php

namespace App\Enums;

enum PaymentType: string
{
    case Regular = 'regular';
    case Extra = 'extra';
}
