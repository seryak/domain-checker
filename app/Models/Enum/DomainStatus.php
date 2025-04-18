<?php

namespace App\Models\Enum;

enum DomainStatus: int
{
    case ERROR = 0;
    case OK = 1;
    case EXPIRED = 2;

//    public function __toString(): string
//    {
//        return $this->value;
//    }
}