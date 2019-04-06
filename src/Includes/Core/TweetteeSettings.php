<?php

namespace Wladweb\Tweettee\Includes\Core;

use Wladweb\Tweettee\Includes\Core\I\SettingsInterface;

/**
 * Manage plugin options
 */
class TweetteeSettings implements SettingsInterface
{

    /**
     * Options storage
     * @var array
     */
    private $options = [];

    public function __construct()
    {
        $this->options = get_option('tweettee');
        $this->acceptSettings();
    }

    /**
     * Check if option data has delivered
     * @return boolean
     */
    public function isFormRecieved()
    {
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && filter_has_var(INPUT_POST, 'tweettee_change_settings')) {
            return true;
        }
        return false;
    }
    
    /**
     *  Update options
     */
    protected function acceptSettings()
    {
        if ($this->isFormRecieved()) {
            $this->updateOptions($this->recieveFormData());
        }
    }

    /**
     * Filter data from POST request
     * @return array
     */
    private function recieveFormData()
    {
        $filters = [
            'show_main_page_settings' => FILTER_SANITIZE_STRING,
            'w_content_type' => FILTER_VALIDATE_INT,
            'w_another_timeline' => FILTER_SANITIZE_STRING,
            'w_search_type' => FILTER_VALIDATE_INT,
            'w_search_word' => FILTER_SANITIZE_STRING,
            'w_count' => FILTER_VALIDATE_INT,
            'w_rel_nofollow' => FILTER_SANITIZE_STRING,
            'w_noindex' => FILTER_SANITIZE_STRING,
            'w_only_text' => FILTER_SANITIZE_STRING,
            'w_result_type' => FILTER_SANITIZE_STRING,
            'w_language' => FILTER_SANITIZE_STRING,
            'm_position' => FILTER_VALIDATE_INT,
            'm_content_type' => FILTER_VALIDATE_INT,
            'm_another_timeline' => FILTER_SANITIZE_STRING,
            'm_search_type' => FILTER_VALIDATE_INT,
            'm_search_word' => FILTER_SANITIZE_STRING,
            'm_count' => FILTER_VALIDATE_INT,
            'm_rel_nofollow' => FILTER_SANITIZE_STRING,
            'm_noindex' => FILTER_SANITIZE_STRING,
            'm_only_text' => FILTER_SANITIZE_STRING,
            'm_result_type' => FILTER_SANITIZE_STRING,
            'm_language' => FILTER_SANITIZE_STRING,
            'cache_enabled' => FILTER_SANITIZE_STRING,
            'cache_interval' => FILTER_SANITIZE_STRING
        ];

        return filter_input_array(INPUT_POST, $filters);
    }

    /**
     * Store & return languages collection
     * @return array
     */
    public function getLanguage()
    {
        return array(
            'all' => 'All',
            'ab' => 'Abkhazian',
            'ae' => 'Avestan',
            'af' => 'Afrikaans',
            'ak' => 'Akan',
            'am' => 'Amharic',
            'an' => 'Aragonese',
            'ar' => 'Arabic',
            'as' => 'Assamese',
            'av' => 'Avaric',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'ba' => 'Bashkir',
            'be' => 'Belarusian',
            'bg' => 'Bulgarian',
            'bh' => 'Bihari',
            'bi' => 'Bislama',
            'bm' => 'Bambara',
            'bn' => 'Bengali',
            'bo' => 'Tibetan',
            'br' => 'Breton',
            'bs' => 'Bosnian',
            'ca' => 'Catalan; Valencian',
            'ce' => 'Chechen',
            'ch' => 'Chamorro',
            'co' => 'Corsican',
            'cr' => 'Cree',
            'cs' => 'Czech',
            'cv' => 'Chuvash',
            'cy' => 'Welsh',
            'da' => 'Danish',
            'de' => 'German',
            'en' => 'English',
            'eo' => 'Esperanto',
            'es' => 'Spanish; Castilian',
            'et' => 'Estonian',
            'eu' => 'Basque',
            'fa' => 'Persian',
            'ff' => 'Fulah',
            'fi' => 'Finnish',
            'fj' => 'Fijian',
            'fo' => 'Faroese',
            'fr' => 'French',
            'fy' => 'Western Frisian',
            'ga' => 'Irish',
            'gl' => 'Galician',
            'gn' => 'Guarani',
            'gu' => 'Gujarati',
            'gv' => 'Manx',
            'ha' => 'Hausa',
            'he' => 'Hebrew',
            'hi' => 'Hindi',
            'ho' => 'Hiri Motu',
            'hr' => 'Croatian',
            'ht' => 'Haitian; Haitian Creole',
            'hu' => 'Hungarian',
            'hy' => 'Armenian',
            'hz' => 'Herero',
            'id' => 'Indonesian',
            'ie' => 'Interlingue; Occidental',
            'ig' => 'Igbo',
            'ii' => 'Sichuan Yi; Nuosu',
            'ik' => 'Inupiaq',
            'io' => 'Ido',
            'is' => 'Icelandic',
            'it' => 'Italian',
            'iu' => 'Inuktitut',
            'ja' => 'Japanese',
            'jv' => 'Javanese',
            'ka' => 'Georgian',
            'kg' => 'Kongo',
            'ki' => 'Kikuyu; Gikuyu',
            'kj' => 'Kuanyama; Kwanyama',
            'kk' => 'Kazakh',
            'kl' => 'Kalaallisut; Greenlandic',
            'km' => 'Central Khmer',
            'kn' => 'Kannada',
            'ko' => 'Korean',
            'kr' => 'Kanuri',
            'ks' => 'Kashmiri',
            'ku' => 'Kurdish',
            'kv' => 'Komi',
            'kw' => 'Cornish',
            'ky' => 'Kirghiz; Kyrgyz',
            'la' => 'Latin',
            'lb' => 'Luxembourgish; Letzeburgesch',
            'lg' => 'Ganda',
            'li' => 'Limburgan; Limburger; Limburgish',
            'ln' => 'Lingala',
            'lo' => 'Lao',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'lv' => 'Latvian',
            'mg' => 'Malagasy',
            'mh' => 'Marshallese',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolian',
            'mr' => 'Marathi',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'my' => 'Burmese',
            'na' => 'Nauru',
            'ne' => 'Nepali',
            'ng' => 'Ndonga',
            'nl' => 'Dutch; Flemish',
            'no' => 'Norwegian',
            'oj' => 'Ojibwa',
            'om' => 'Oromo',
            'or' => 'Oriya',
            'os' => 'Ossetian; Ossetic',
            'pa' => 'Panjabi; Punjabi',
            'pi' => 'Pali',
            'pl' => 'Polish',
            'ps' => 'Pushto; Pashto',
            'pt' => 'Portuguese',
            'qu' => 'Quechua',
            'rm' => 'Romansh',
            'rn' => 'Rundi',
            'ro' => 'Romanian; Moldavian; Moldovan',
            'ru' => 'Russian',
            'rw' => 'Kinyarwanda',
            'sa' => 'Sanskrit',
            'sc' => 'Sardinian',
            'sd' => 'Sindhi',
            'se' => 'Northern Sami',
            'sg' => 'Sango',
            'si' => 'Sinhala; Sinhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'sm' => 'Samoan',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'ss' => 'Swati',
            'su' => 'Sundanese',
            'sv' => 'Swedish',
            'sw' => 'Swahili',
            'ta' => 'Tamil',
            'te' => 'Telugu',
            'tg' => 'Tajik',
            'th' => 'Thai',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmen',
            'tl' => 'Tagalog',
            'tn' => 'Tswana',
            'to' => 'Tonga (Tonga Islands)',
            'tr' => 'Turkish',
            'ts' => 'Tsonga',
            'tt' => 'Tatar',
            'tw' => 'Twi',
            'ty' => 'Tahitian',
            'ug' => 'Uighur; Uyghur',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            've' => 'Venda',
            'vi' => 'Vietnamese',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'za' => 'Zhuang; Chuang',
            'zh' => 'Chinese',
            'zu' => 'Zulu'
        );
    }

    /**
     * Filter & update options
     * @param array $opts
     * @return void
     */
    private function updateOptions(array $opts = [])
    {
        foreach ($opts as $key => $val) {
            if (!array_key_exists($key, $this->options)) {
                continue;
            }
            $this->options[$key] = $val;
        }
        \update_option('tweettee', $this->options);
    }

    /**
     * Set options in db
     * @param array|string $opt
     * @param mixed $value
     * @return void
     */
    public function setOption($opt, $value = null)
    {
        if (is_array($opt)) {
            $this->updateOptions($opt);
        } else {
            $this->updateOptions([$opt => $value]);
        }
    }

    /**
     * Get option(s)
     * @param array|string $opt
     * @return array|string|false
     */
    public function getOption($opt)
    {
        if (is_array($opt)) {

            $result = [];
            foreach ($opt as $name) {
                if ($this->hasOption($name)) {
                    $result[$name] = $this->options[$name];
                }
            }
            return $result;
        } elseif ($this->hasOption($opt)) {
            return $this->options[$opt];
        }

        return false;
    }

    /**
     * Check if option exists
     * @param string $name
     * @return boolean
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * Get all options array
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

}
