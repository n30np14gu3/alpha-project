<?php
namespace App\Http\Helpers;

use Illuminate\Http\Request;


class CostHelper
{
    public static function GetBalance($balance, Request $request)
    {
        $location_info = json_decode(Geolocation::getLocationInfo());
        $currencies = self::get_currencies($request);
        $locale = 'en_US';

        if($location_info->geoplugin_countryCode)
            $locale = self::country2locale(strtolower($location_info->geoplugin_countryCode));
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $eur_info = $currencies['EUR'];

        if(!@$location_info->geoplugin_currencyCode)
            $location_info->geoplugin_currencyCode = "EUR";

        if($location_info->geoplugin_currencyCode == "RUB")
            $balance *= $eur_info;
        else
        {
            if(!$currencies[$location_info->geoplugin_currencyCode])
                $self_info = $currencies['EUR'];
            else
                $self_info = $currencies[$location_info->geoplugin_currencyCode];

            $balance = ($balance * $eur_info) / $self_info;
        }
        return $formatter->formatCurrency($balance, $location_info->geoplugin_currencyCode);
    }

    public static function Convert($cost, Request $request){
        $location_info = json_decode(Geolocation::getLocationInfo());
        $currencies = self::get_currencies($request);
        $locale = 'en_US';

        if($location_info->geoplugin_countryCode)
            $locale = self::country2locale(strtolower($location_info->geoplugin_countryCode));
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $eur_info = $currencies['EUR'];
        $formatted_cost = 0;

        if(!@$location_info->geoplugin_currencyCode)
            $location_info->geoplugin_currencyCode = "EUR";

        if($location_info->geoplugin_currencyCode == "RUB"){
            $cost *= $eur_info;
            $formatted_cost = $cost;
        }
        else
        {
            if(!$currencies[$location_info->geoplugin_currencyCode])
                $self_info = $currencies['EUR'];
            else
                $self_info = $currencies[$location_info->geoplugin_currencyCode];

            $cost = ($cost * $eur_info) / $self_info;
            $formatted_cost = $cost * $eur_info;
        }
        return [
            $formatter->formatCurrency(round($cost, 1), $location_info->geoplugin_currencyCode),
            round($formatted_cost, 1)
        ];
    }
    private static function get_currencies(Request $request)
    {
        if($request->session()->has('wallets'))
            return (array)json_decode($request->session()->get('wallets'));

        $xml = simplexml_load_file('http://cbr.ru/scripts/XML_daily.asp');
        $currencies = array();
        foreach ($xml->xpath('//Valute') as $val) {
            $currencies[(string)$val->CharCode] = (float)str_replace(',', '.', $val->Value);
        }
        $request->session()->put('wallets', json_encode($currencies));
        return $currencies;
    }

    /**
     * @param $code
     * @return string
     */
    private static function country2locale($code)
    {
        $arr = array(
            'ad' => 'ca',
            'ae' => 'ar',
            'af' => 'fa,ps',
            'ag' => 'en',
            'ai' => 'en',
            'al' => 'sq',
            'am' => 'hy',
            'an' => 'nl,en',
            'ao' => 'pt',
            'aq' => 'en',
            'ar' => 'es',
            'as' => 'en,sm',
            'at' => 'de',
            'au' => 'en',
            'aw' => 'nl,pap',
            'ax' => 'sv',
            'az' => 'az',
            'ba' => 'bs,hr,sr',
            'bb' => 'en',
            'bd' => 'bn',
            'be' => 'nl,fr,de',
            'bf' => 'fr',
            'bg' => 'bg',
            'bh' => 'ar',
            'bi' => 'fr',
            'bj' => 'fr',
            'bl' => 'fr',
            'bm' => 'en',
            'bn' => 'ms',
            'bo' => 'es,qu,ay',
            'br' => 'pt',
            'bq' => 'nl,en',
            'bs' => 'en',
            'bt' => 'dz',
            'bv' => 'no',
            'bw' => 'en,tn',
            'by' => 'be,ru',
            'bz' => 'en',
            'ca' => 'en,fr',
            'cc' => 'en',
            'cd' => 'fr',
            'cf' => 'fr',
            'cg' => 'fr',
            'ch' => 'de,fr,it,rm',
            'ci' => 'fr',
            'ck' => 'en,rar',
            'cl' => 'es',
            'cm' => 'fr,en',
            'cn' => 'zh',
            'co' => 'es',
            'cr' => 'es',
            'cu' => 'es',
            'cv' => 'pt',
            'cw' => 'nl',
            'cx' => 'en',
            'cy' => 'el,tr',
            'cz' => 'cs',
            'de' => 'de',
            'dj' => 'fr,ar,so',
            'dk' => 'da',
            'dm' => 'en',
            'do' => 'es',
            'dz' => 'ar',
            'ec' => 'es',
            'ee' => 'et',
            'eg' => 'ar',
            'eh' => 'ar,es,fr',
            'er' => 'ti,ar,en',
            'es' => 'es,ast,ca,eu,gl',
            'et' => 'am,om',
            'fi' => 'fi,sv,se',
            'fj' => 'en',
            'fk' => 'en',
            'fm' => 'en',
            'fo' => 'fo',
            'fr' => 'fr',
            'ga' => 'fr',
            'gb' => 'en,ga,cy,gd,kw',
            'gd' => 'en',
            'ge' => 'ka',
            'gf' => 'fr',
            'gg' => 'en',
            'gh' => 'en',
            'gi' => 'en',
            'gl' => 'kl,da',
            'gm' => 'en',
            'gn' => 'fr',
            'gp' => 'fr',
            'gq' => 'es,fr,pt',
            'gr' => 'el',
            'gs' => 'en',
            'gt' => 'es',
            'gu' => 'en,ch',
            'gw' => 'pt',
            'gy' => 'en',
            'hk' => 'zh,en',
            'hm' => 'en',
            'hn' => 'es',
            'hr' => 'hr',
            'ht' => 'fr,ht',
            'hu' => 'hu',
            'id' => 'id',
            'ie' => 'en,ga',
            'il' => 'he',
            'im' => 'en',
            'in' => 'hi,en',
            'io' => 'en',
            'iq' => 'ar,ku',
            'ir' => 'fa',
            'is' => 'is',
            'it' => 'it,de,fr',
            'je' => 'en',
            'jm' => 'en',
            'jo' => 'ar',
            'jp' => 'ja',
            'ke' => 'sw,en',
            'kg' => 'ky,ru',
            'kh' => 'km',
            'ki' => 'en',
            'km' => 'ar,fr',
            'kn' => 'en',
            'kp' => 'ko',
            'kr' => 'ko,en',
            'kw' => 'ar',
            'ky' => 'en',
            'kz' => 'kk,ru',
            'la' => 'lo',
            'lb' => 'ar,fr',
            'lc' => 'en',
            'li' => 'de',
            'lk' => 'si,ta',
            'lr' => 'en',
            'ls' => 'en,st',
            'lt' => 'lt',
            'lu' => 'lb,fr,de',
            'lv' => 'lv',
            'ly' => 'ar',
            'ma' => 'ar',
            'mc' => 'fr',
            'md' => 'ru,uk,ro',
            'me' => 'srp,sq,bs,hr,sr',
            'mf' => 'fr',
            'mg' => 'mg,fr',
            'mh' => 'en,mh',
            'mk' => 'mk',
            'ml' => 'fr',
            'mm' => 'my',
            'mn' => 'mn',
            'mo' => 'zh,en,pt',
            'mp' => 'ch',
            'mq' => 'fr',
            'mr' => 'ar,fr',
            'ms' => 'en',
            'mt' => 'mt,en',
            'mu' => 'mfe,fr,en',
            'mv' => 'dv',
            'mw' => 'en,ny',
            'mx' => 'es',
            'my' => 'ms,zh,en',
            'mz' => 'pt',
            'na' => 'en,sf,de',
            'nc' => 'fr',
            'ne' => 'fr',
            'nf' => 'en,pih',
            'ng' => 'en',
            'ni' => 'es',
            'nl' => 'nl',
            'no' => 'nb,nn,no,se',
            'np' => 'ne',
            'nr' => 'na,en',
            'nu' => 'niu,en',
            'nz' => 'en,mi',
            'om' => 'ar',
            'pa' => 'es',
            'pe' => 'es',
            'pf' => 'fr',
            'pg' => 'en,tpi,ho',
            'ph' => 'en,tl',
            'pk' => 'en,ur',
            'pl' => 'pl',
            'pm' => 'fr',
            'pn' => 'en,pih',
            'pr' => 'es,en',
            'ps' => 'ar,he',
            'pt' => 'pt',
            'pw' => 'en,pau,ja,sov,tox',
            'py' => 'es,gn',
            'qa' => 'ar',
            're' => 'fr',
            'ro' => 'ro',
            'rs' => 'sr',
            'ru' => 'ru',
            'rw' => 'rw,fr,en',
            'sa' => 'ar',
            'sb' => 'en',
            'sc' => 'fr,en,crs',
            'sd' => 'ar,en',
            'se' => 'sv',
            'sg' => 'en,ms,zh,ta',
            'sh' => 'en',
            'si' => 'sl',
            'sj' => 'no',
            'sk' => 'sk',
            'sl' => 'en',
            'sm' => 'it',
            'sn' => 'fr',
            'so' => 'so,ar',
            'sr' => 'nl',
            'st' => 'pt',
            'ss' => 'en',
            'sv' => 'es',
            'sx' => 'nl,en',
            'sy' => 'ar',
            'sz' => 'en,ss',
            'tc' => 'en',
            'td' => 'fr,ar',
            'tf' => 'fr',
            'tg' => 'fr',
            'th' => 'th',
            'tj' => 'tg,ru',
            'tk' => 'tkl,en,sm',
            'tl' => 'pt,tet',
            'tm' => 'tk',
            'tn' => 'ar',
            'to' => 'en',
            'tr' => 'tr',
            'tt' => 'en',
            'tv' => 'en',
            'tw' => 'zh',
            'tz' => 'sw,en',
            'ua' => 'uk',
            'ug' => 'en,sw',
            'um' => 'en',
            'us' => 'en,es',
            'uy' => 'es',
            'uz' => 'uz,kaa',
            'va' => 'it',
            'vc' => 'en',
            've' => 'es',
            'vg' => 'en',
            'vi' => 'en',
            'vn' => 'vi',
            'vu' => 'bi,en,fr',
            'wf' => 'fr',
            'ws' => 'sm,en',
            'ye' => 'ar',
            'yt' => 'fr',
            'za' => 'zu,xh,af,st,tn,en',
            'zm' => 'en',
            'zw' => 'en,sn,nd'
        );
        #----
        $code = strtolower($code);
        if ($code == 'eu') {
            return 'en_GB';
        }
        elseif ($code == 'ap') { # Asia Pacific
            return 'en_US';
        }
        elseif ($code == 'cs') {
            return 'sr_RS';
        }
        #----
        if ($code == 'uk') {
            $code = 'gb';
        }
        if (array_key_exists($code, $arr)) {
            if (strpos($arr[$code], ',') !== false) {
                $new = explode(',', $arr[$code]);
                $loc = array();
                foreach ($new as $key => $val) {
                    $loc[] = $val.'_'.strtoupper($code);
                }
                return implode(',', $loc); # string; comma-separated values 'en_GB,ga_GB,cy_GB,gd_GB,kw_GB'
            }
            else {
                return $arr[$code].'_'.strtoupper($code); # string 'en_US'
            }
        }
        return 'en_US';
    }
}