<?php

if (!function_exists('maskAccountNumber')) {
    /**
     * Mask account number for display
     * Shows only first 2 and last 4 digits
     * Example: 1234567890 becomes 12****7890
     */
    function maskAccountNumber($accountNumber)
    {
        if (!$accountNumber || strlen($accountNumber) < 6) {
            return '****';
        }

        $length = strlen($accountNumber);
        $visibleStart = 2;
        $visibleEnd = 4;

        return substr($accountNumber, 0, $visibleStart) .
               str_repeat('*', $length - $visibleStart - $visibleEnd) .
               substr($accountNumber, -$visibleEnd);
    }
}

if (!function_exists('maskIban')) {
    /**
     * Mask IBAN for display
     * Shows only first 4 and last 4 characters
     * Example: PK36XXXX0000001234567890 becomes PK36****************7890
     */
    function maskIban($iban)
    {
        if (!$iban || strlen($iban) < 8) {
            return '****';
        }

        $length = strlen($iban);
        $visibleStart = 4;
        $visibleEnd = 4;

        return substr($iban, 0, $visibleStart) .
               str_repeat('*', $length - $visibleStart - $visibleEnd) .
               substr($iban, -$visibleEnd);
    }
}
