<?php
define("CRYPTO_KEY", hash("sha256", "VetCare_SAEP_LGPD_Chave_2026", true));
define("CRYPTO_IV", "VetCareIV16bytes");

function criptografar_cpf(string $cpf): string {
    $cpf = preg_replace("/\D/", "", $cpf);
    if (strlen($cpf) === 11) {
        $cpf = substr($cpf,0,3).".".substr($cpf,3,3).".".substr($cpf,6,3)."-".substr($cpf,9,2);
    }
    $e = openssl_encrypt($cpf, "AES-256-CBC", CRYPTO_KEY, 0, CRYPTO_IV);
    return $e !== false ? $e : "";
}

function descriptografar_cpf(string $cipher): string {
    if ($cipher === "") return "";
    $p = openssl_decrypt($cipher, "AES-256-CBC", CRYPTO_KEY, 0, CRYPTO_IV);
    return $p !== false ? $p : "";
}
