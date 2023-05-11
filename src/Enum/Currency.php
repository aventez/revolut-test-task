<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum Currency: string {
    use EnumToArray;

    case AED = 'app.currency.aed';
    case AUD = 'app.currency.aud';
    case BGN = 'app.currency.bgn';
    case CAD = 'app.currency.cad';
    case CHF = 'app.currency.chf';
    case CZK = 'app.currency.czk';
    case DKK = 'app.currency.dkk';
    case EUR = 'app.currency.eur';
    case GBP = 'app.currency.gbp';
    case ZAR = 'app.currency.zar';
}
