<?php
// Service: TokenService.php
// Small helper for generating and hashing tokens used for password resets.

class TokenService
{
    // Generate a secure random token and its hash.
    // Returns ['token' => <raw token>, 'hash' => <sha256 hash>]
    public static function generateToken(int $bytes = 32): array
    {
        $token = bin2hex(random_bytes($bytes));
        $hash = self::hashToken($token);
        return ['token' => $token, 'hash' => $hash];
    }

    // Hash a token for storage (use SHA-256)
    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    public static function isValidFormat(string $token, int $bytes = 32): bool
    {
        // bin2hex(random_bytes($bytes)) length in chars = $bytes * 2
        $expectedLength = $bytes * 2;

        if (strlen($token) !== $expectedLength) {
            return false;
        }

        return ctype_xdigit($token);
    }
}
