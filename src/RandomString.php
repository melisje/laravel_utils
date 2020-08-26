<?php


namespace Melit\Utils;


class RandomString
{
    const LETTERS = "abcdefghijklmnopqrstuvwxyz";
    const CIPHERS = "0123456789";
    const CONSONANTS = "bcdfghjklmnpqrstvwxz";
    const VOWELS = "aeiouy";

    /**
     * Generate a random string where you can provide a list of characters that will be used.
     * @param int $size
     * @param string $characters by default all normal letters and ciphers
     * @return string
     */
    static public function generate($size = 8, $characters = self::CIPHERS . self::LETTERS)
    {
        $randstring = '';
        for ($i = 0; $i < $size; $i++)
        {
            $randstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

    /**
     * Generate a randompassword like the auto generated password of Office 365
     *  - 3 letters + 5 ciphers,
     *  - first letter is a captial.
     *  - first letter is a consonant
     *  - second letter is a vowel
     *
     * @return string
     */
    static public function o365()
    {
        return strtoupper(self::generate(1,self::CONSONANTS)) . self::generate(1,self::VOWELS) . self::generate(1,self::LETTERS) .self::generate(5,self::CIPHERS);
    }
}
