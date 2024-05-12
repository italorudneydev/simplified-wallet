<?php

namespace App\Enums;

enum WalletOperation: string
{
    case ADDITION = 'addition';
    case SUBTRACTION = 'subtraction';

    public function description(): string
    {
        return match($this) {
            self::ADDITION => 'Adicionar saldo à carteira',
            self::SUBTRACTION => 'Subtrair saldo da carteira',
        };
    }
}
