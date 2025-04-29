<?php

namespace Database\Seeders;

use App\Models\Timezone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('timezones')->truncate();
        $timezones = [
            [
                'name' => '(GMT-11:00) Niue Time',
                'timezone' => 'Pacific/Niue'
            ],
            [
                'name' => '(GMT-11:00) Samoa Standard Time',
                'timezone' => 'Pacific/Pago_Pago'
            ],
            [
                'name' => '(GMT-10:00) Cook Islands Standard Time',
                'timezone' => 'Pacific/Rarotonga'
            ],
            [
                'name' => '(GMT-10:00) Hawaii-Aleutian Standard Time',
                'timezone' => 'Pacific/Honolulu'
            ],
            [
                'name' => '(GMT-10:00) Hawaii-Aleutian Time',
                'timezone' => 'America/Adak'
            ],
            [
                'name' => '(GMT-10:00) Tahiti Time',
                'timezone' => 'Pacific/Tahiti'
            ],
            [
                'name' => '(GMT-09:30) Marquesas Time',
                'timezone' => 'Pacific/Marquesas'
            ],
            [
                'name' => '(GMT-09:00) Alaska Time - Anchorage',
                'timezone' => 'America/Anchorage'
            ],
            [
                'name' => '(GMT-09:00) Alaska Time - Juneau',
                'timezone' => 'America/Juneau'
            ],
            [
                'name' => '(GMT-09:00) Alaska Time - Metlakatla',
                'timezone' => 'America/Metlakatla'
            ],
            [
                'name' => '(GMT-09:00) Alaska Time - Nome',
                'timezone' => 'America/Nome'
            ],
            [
                'name' => '(GMT-09:00) Alaska Time - Sitka',
                'timezone' => 'America/Sitka'
            ],
            [
                'name' => '(GMT-09:00) Alaska Time - Yakutat',
                'timezone' => 'America/Yakutat'
            ],
            [
                'name' => '(GMT-09:00) Gambier Time',
                'timezone' => 'Pacific/Gambier'
            ],
            [
                'name' => '(GMT-08:00) Pacific Time - Los Angeles',
                'timezone' => 'America/Los_Angeles'
            ],
            [
                'name' => '(GMT-08:00) Pacific Time - Tijuana',
                'timezone' => 'America/Tijuana'
            ],
            [
                'name' => '(GMT-08:00) Pacific Time - Vancouver',
                'timezone' => 'America/Vancouver'
            ],
            [
                'name' => '(GMT-08:00) Pitcairn Time',
                'timezone' => 'Pacific/Pitcairn'
            ],
            [
                'name' => '(GMT-07:00) Mexican Pacific Standard Time - Hermosillo',
                'timezone' => 'America/Hermosillo'
            ],
            [
                'name' => '(GMT-07:00) Mexican Pacific Standard Time - Mazatlan',
                'timezone' => 'America/Mazatlan'
            ],
            [
                'name' => '(GMT-07:00) Mountain Standard Time - Dawson Creek',
                'timezone' => 'America/Dawson_Creek'
            ],
            [
                'name' => '(GMT-07:00) Mountain Standard Time - Fort Nelson',
                'timezone' => 'America/Fort_Nelson'
            ],
            [
                'name' => '(GMT-07:00) Mountain Standard Time - Phoenix',
                'timezone' => 'America/Phoenix'
            ],
            [
                'name' => '(GMT-07:00) Mountain Time - Boise',
                'timezone' => 'America/Boise'
            ],
            [
                'name' => '(GMT-07:00) Mountain Time - Cambridge Bay',
                'timezone' => 'America/Cambridge_Bay'
            ],
            [
                'name' => '(GMT-07:00) Mountain Time - Ciudad Juárez',
                'timezone' => 'America/Ciudad_Juarez'
            ],
            [
                'name' => '(GMT-07:00) Mountain Time - Denver',
                'timezone' => 'America/Denver'
            ],
            [
                'name' => '(GMT-07:00) Mountain Time - Edmonton',
                'timezone' => 'America/Edmonton'
            ],
            [
                'name' => '(GMT-07:00) Mountain Time - Edmonton',
                'timezone' => 'America/Yellowknife'
            ],
            [
                'name' => '(GMT-07:00) Mountain Time - Inuvik',
                'timezone' => 'America/Inuvik'
            ],
            [
                'name' => '(GMT-07:00) Yukon Time - Dawson',
                'timezone' => 'America/Dawson'
            ],
            [
                'name' => '(GMT-07:00) Yukon Time - Whitehorse',
                'timezone' => 'America/Whitehorse'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Bahía de Banderas',
                'timezone' => 'America/Bahia_Banderas'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Belize',
                'timezone' => 'America/Belize'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Chihuahua',
                'timezone' => 'America/Chihuahua'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Costa Rica',
                'timezone' => 'America/Costa_Rica'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - El Salvador',
                'timezone' => 'America/El_Salvador'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Guatemala',
                'timezone' => 'America/Guatemala'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Managua',
                'timezone' => 'America/Managua'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Mérida',
                'timezone' => 'America/Merida'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Mexico City',
                'timezone' => 'America/Mexico_City'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Monterrey',
                'timezone' => 'America/Monterrey'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Regina',
                'timezone' => 'America/Regina'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Swift Current',
                'timezone' => 'America/Swift_Current'
            ],
            [
                'name' => '(GMT-06:00) Central Standard Time - Tegucigalpa',
                'timezone' => 'America/Tegucigalpa'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Beulah,
                North Dakota',
                'timezone' => 'America/North_Dakota/Beulah'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Center, North Dakota',
                'timezone' => 'America/North_Dakota/Center'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Chicago',
                'timezone' => 'America/Chicago'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Knox, Indiana',
                'timezone' => 'America/Indiana/Knox'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Matamoros',
                'timezone' => 'America/Matamoros'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Menominee',
                'timezone' => 'America/Menominee'
            ],
            [
                'name' => '(GMT-06:00) Central Time - New Salem, North Dakota',
                'timezone' => 'America/North_Dakota/New_Salem'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Ojinaga',
                'timezone' => 'America/Ojinaga'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Rankin Inlet',
                'timezone' => 'America/Rankin_Inlet'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Resolute',
                'timezone' => 'America/Resolute'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Tell City, Indiana',
                'timezone' => 'America/Indiana/Tell_City'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Winnipeg',
                'timezone' => 'America/Rainy_River'
            ],
            [
                'name' => '(GMT-06:00) Central Time - Winnipeg',
                'timezone' => 'America/Winnipeg'
            ],
            [
                'name' => '(GMT-06:00) Galapagos Time',
                'timezone' => 'Pacific/Galapagos'
            ],
            [
                'name' => '(GMT-05:00) Acre Standard Time - Eirunepe',
                'timezone' => 'America/Eirunepe'
            ],
            [
                'name' => '(GMT-05:00) Acre Standard Time - Rio Branco',
                'timezone' => 'America/Rio_Branco'
            ],
            [
                'name' => '(GMT-05:00) Colombia Standard Time',
                'timezone' => 'America/Bogota'
            ],
            [
                'name' => '(GMT-05:00) Cuba Time',
                'timezone' => 'America/Havana'
            ],
            [
                'name' => '(GMT-05:00) Easter Island Time',
                'timezone' => 'Pacific/Easter'
            ],
            [
                'name' => '(GMT-05:00) Eastern Standard Time - Cancún',
                'timezone' => 'America/Cancun'
            ],
            [
                'name' => '(GMT-05:00) Eastern Standard Time - Jamaica',
                'timezone' => 'America/Jamaica'
            ],
            [
                'name' => '(GMT-05:00) Eastern Standard Time - Panama',
                'timezone' => 'America/Panama'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Detroit',
                'timezone' => 'America/Detroit'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Grand Turk',
                'timezone' => 'America/Grand_Turk'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Indianapolis',
                'timezone' => 'America/Indiana/Indianapolis'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Iqaluit',
                'timezone' => 'America/Iqaluit'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Iqaluit',
                'timezone' => 'America/Pangnirtung'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Louisville',
                'timezone' => 'America/Kentucky/Louisville'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Marengo, Indiana',
                'timezone' => 'America/Indiana/Marengo'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Monticello, Kentucky',
                'timezone' => 'America/Kentucky/Monticello'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - New York',
                'timezone' => 'America/New_York'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Petersburg, Indiana',
                'timezone' => 'America/Indiana/Petersburg'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Port-au-Prince',
                'timezone' => 'America/Port-au-Prince'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Toronto',
                'timezone' => 'America/Nipigon'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Toronto',
                'timezone' => 'America/Thunder_Bay'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Toronto',
                'timezone' => 'America/Toronto'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Vevay, Indiana',
                'timezone' => 'America/Indiana/Vevay'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Vincennes, Indiana',
                'timezone' => 'America/Indiana/Vincennes'
            ],
            [
                'name' => '(GMT-05:00) Eastern Time - Winamac, Indiana',
                'timezone' => 'America/Indiana/Winamac'
            ],
            [
                'name' => '(GMT-05:00) Ecuador Time',
                'timezone' => 'America/Guayaquil'
            ],
            [
                'name' => '(GMT-05:00) Peru Standard Time',
                'timezone' => 'America/Lima'
            ],
            [
                'name' => '(GMT-04:00) Amazon Standard Time - Boa Vista',
                'timezone' => 'America/Boa_Vista'
            ],
            [
                'name' => '(GMT-04:00) Amazon Standard Time - Campo Grande',
                'timezone' => 'America/Campo_Grande'
            ],
            [
                'name' => '(GMT-04:00) Amazon Standard Time - Cuiaba',
                'timezone' => 'America/Cuiaba'
            ],
            [
                'name' => '(GMT-04:00) Amazon Standard Time - Manaus',
                'timezone' => 'America/Manaus'
            ],
            [
                'name' => '(GMT-04:00) Amazon Standard Time - Porto Velho',
                'timezone' => 'America/Porto_Velho'
            ],
            [
                'name' => '(GMT-04:00) Atlantic Standard Time - Barbados',
                'timezone' => 'America/Barbados'
            ],
            [
                'name' => '(GMT-04:00) Atlantic Standard Time - Martinique',
                'timezone' => 'America/Martinique'
            ],
            [
                'name' => '(GMT-04:00) Atlantic Standard Time - Puerto Rico',
                'timezone' => 'America/Puerto_Rico'
            ],
            [
                'name' => '(GMT-04:00) Atlantic Standard Time - Santo Domingo',
                'timezone' => 'America/Santo_Domingo'
            ],
            [
                'name' => '(GMT-04:00) Atlantic Time - Bermuda',
                'timezone' => 'Atlantic/Bermuda'
            ],
            [
                'name' => '(GMT-04:00) Atlantic Time - Glace Bay',
                'timezone' => 'America/Glace_Bay'
            ],
            [
                'name' => '(GMT-04:00) Atlantic Time - Goose Bay',
                'timezone' => 'America/Goose_Bay'
            ],
            [
                'name' => '(GMT-04:00) Atlantic Time - Halifax',
                'timezone' => 'America/Halifax'
            ],
            [
                'name' => '(GMT-04:00) Atlantic Time - Moncton',
                'timezone' => 'America/Moncton'
            ],
            [
                'name' => '(GMT-04:00) Atlantic Time - Thule',
                'timezone' => 'America/Thule'
            ],
            [
                'name' => '(GMT-04:00) Bolivia Time',
                'timezone' => 'America/La_Paz'
            ],
            [
                'name' => '(GMT-04:00) Guyana Time',
                'timezone' => 'America/Guyana'
            ],
            [
                'name' => '(GMT-04:00) Venezuela Time',
                'timezone' => 'America/Caracas'
            ],
            [
                'name' => '(GMT-03:30) Newfoundland Time',
                'timezone' => 'America/St_Johns'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - Buenos Aires',
                'timezone' => 'America/Argentina/Buenos_Aires'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - Catamarca',
                'timezone' => 'America/Argentina/Catamarca'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - Cordoba',
                'timezone' => 'America/Argentina/Cordoba'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - Jujuy',
                'timezone' => 'America/Argentina/Jujuy'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - La Rioja',
                'timezone' => 'America/Argentina/La_Rioja'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - Mendoza',
                'timezone' => 'America/Argentina/Mendoza'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - Rio Gallegos',
                'timezone' => 'America/Argentina/Rio_Gallegos'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - Salta',
                'timezone' => 'America/Argentina/Salta'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - San Juan',
                'timezone' => 'America/Argentina/San_Juan'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - San Luis',
                'timezone' => 'America/Argentina/San_Luis'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - Tucuman',
                'timezone' => 'America/Argentina/Tucuman'
            ],
            [
                'name' => '(GMT-03:00) Argentina Standard Time - Ushuaia',
                'timezone' => 'America/Argentina/Ushuaia'
            ],
            [
                'name' => '(GMT-03:00) Brasilia Standard Time - Araguaina',
                'timezone' => 'America/Araguaina'
            ],
            [
                'name' => '(GMT-03:00) Brasilia Standard Time - Bahia',
                'timezone' => 'America/Bahia'
            ],
            [
                'name' => '(GMT-03:00) Brasilia Standard Time - Belem',
                'timezone' => 'America/Belem'
            ],
            [
                'name' => '(GMT-03:00) Brasilia Standard Time - Fortaleza',
                'timezone' => 'America/Fortaleza'
            ],
            [
                'name' => '(GMT-03:00) Brasilia Standard Time - Maceio',
                'timezone' => 'America/Maceio'
            ],
            [
                'name' => '(GMT-03:00) Brasilia Standard Time - Recife',
                'timezone' => 'America/Recife'
            ],
            [
                'name' => '(GMT-03:00) Brasilia Standard Time - Santarem',
                'timezone' => 'America/Santarem'
            ],
            [
                'name' => '(GMT-03:00) Brasilia Standard Time - Sao Paulo',
                'timezone' => 'America/Sao_Paulo'
            ],
            [
                'name' => '(GMT-03:00) Chile Time',
                'timezone' => 'America/Santiago'
            ],
            [
                'name' => '(GMT-03:00) Falkland Islands Standard Time',
                'timezone' => 'Atlantic/Stanley'
            ],
            [
                'name' => '(GMT-03:00) French Guiana Time',
                'timezone' => 'America/Cayenne'
            ],
            [
                'name' => '(GMT-03:00) Palmer Time',
                'timezone' => 'Antarctica/Palmer'
            ],
            [
                'name' => '(GMT-03:00) Paraguay Time',
                'timezone' => 'America/Asuncion'
            ],
            [
                'name' => '(GMT-03:00) Punta Arenas Time',
                'timezone' => 'America/Punta_Arenas'
            ],
            [
                'name' => '(GMT-03:00) Rothera Time',
                'timezone' => 'Antarctica/Rothera'
            ],
            [
                'name' => '(GMT-03:00) St. Pierre & Miquelon Time',
                'timezone' => 'America/Miquelon'
            ],
            [
                'name' => '(GMT-03:00) Suriname Time',
                'timezone' => 'America/Paramaribo'
            ],
            [
                'name' => '(GMT-03:00) Uruguay Standard Time',
                'timezone' => 'America/Montevideo'
            ],
            [
                'name' => '(GMT-02:00) Fernando de Noronha Standard Time',
                'timezone' => 'America/Noronha'
            ],
            [
                'name' => '(GMT-02:00) South Georgia Time',
                'timezone' => 'Atlantic/South_Georgia'
            ],
            [
                'name' => '(GMT-02:00) West Greenland Time',
                'timezone' => 'America/Nuuk'
            ],
            [
                'name' => '(GMT-01:00) Azores Time',
                'timezone' => 'Atlantic/Azores'
            ],
            [
                'name' => '(GMT-01:00) Cape Verde Standard Time',
                'timezone' => 'Atlantic/Cape_Verde'
            ],
            [
                'name' => '(GMT-01:00) East Greenland Time',
                'timezone' => 'America/Scoresbysund'
            ],
            [
                'name' => '(GMT+00:00) Coordinated Universal Time',
                'timezone' => 'UTC'
            ],
            [
                'name' => '(GMT+00:00) Greenwich Mean Time',
                'timezone' => 'Etc/GMT'
            ],
            [
                'name' => '(GMT+00:00) Greenwich Mean Time - Abidjan',
                'timezone' => 'Africa/Abidjan'
            ],
            [
                'name' => '(GMT+00:00) Greenwich Mean Time - Bissau',
                'timezone' => 'Africa/Bissau'
            ],
            [
                'name' => '(GMT+00:00) Greenwich Mean Time - Danmarkshavn',
                'timezone' => 'America/Danmarkshavn'
            ],
            [
                'name' => '(GMT+00:00) Greenwich Mean Time - Monrovia',
                'timezone' => 'Africa/Monrovia'
            ],
            [
                'name' => '(GMT+00:00) Greenwich Mean Time - Reykjavik',
                'timezone' => 'Atlantic/Reykjavik'
            ],
            [
                'name' => '(GMT+00:00) Greenwich Mean Time - São Tomé',
                'timezone' => 'Africa/Sao_Tome'
            ],
            [
                'name' => '(GMT+00:00) Ireland Time',
                'timezone' => 'Europe/Dublin'
            ],
            [
                'name' => '(GMT+00:00) Troll Time',
                'timezone' => 'Antarctica/Troll'
            ],
            [
                'name' => '(GMT+00:00) United Kingdom Time',
                'timezone' => 'Europe/London'
            ],
            [
                'name' => '(GMT+00:00) Western European Time - Canary',
                'timezone' => 'Atlantic/Canary'
            ],
            [
                'name' => '(GMT+00:00) Western European Time - Faroe',
                'timezone' => 'Atlantic/Faroe'
            ],
            [
                'name' => '(GMT+00:00) Western European Time - Lisbon',
                'timezone' => 'Europe/Lisbon'
            ],
            [
                'name' => '(GMT+00:00) Western European Time - Madeira',
                'timezone' => 'Atlantic/Madeira'
            ],
            [
                'name' => '(GMT+01:00) Central European Standard Time - Algiers',
                'timezone' => 'Africa/Algiers'
            ],
            [
                'name' => '(GMT+01:00) Central European Standard Time - Tunis',
                'timezone' => 'Africa/Tunis'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Amsterdam',
                'timezone' => 'Europe/Amsterdam'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Andorra',
                'timezone' => 'Europe/Andorra'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Belgrade',
                'timezone' => 'Europe/Belgrade'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Berlin',
                'timezone' => 'Europe/Berlin'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Brussels',
                'timezone' => 'Europe/Brussels'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Budapest',
                'timezone' => 'Europe/Budapest'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Ceuta',
                'timezone' => 'Africa/Ceuta'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Copenhagen',
                'timezone' => 'Europe/Copenhagen'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Gibraltar',
                'timezone' => 'Europe/Gibraltar'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Luxembourg',
                'timezone' => 'Europe/Luxembourg'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Madrid',
                'timezone' => 'Europe/Madrid'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Malta',
                'timezone' => 'Europe/Malta'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Monaco',
                'timezone' => 'Europe/Monaco'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Oslo',
                'timezone' => 'Europe/Oslo'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Paris',
                'timezone' => 'Europe/Paris'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Prague',
                'timezone' => 'Europe/Prague'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Rome',
                'timezone' => 'Europe/Rome'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Stockholm',
                'timezone' => 'Europe/Stockholm'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Tirane',
                'timezone' => 'Europe/Tirane'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Vienna',
                'timezone' => 'Europe/Vienna'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Warsaw',
                'timezone' => 'Europe/Warsaw'
            ],
            [
                'name' => '(GMT+01:00) Central European Time - Zurich',
                'timezone' => 'Europe/Zurich'
            ],
            [
                'name' => '(GMT+01:00) Morocco Time',
                'timezone' => 'Africa/Casablanca'
            ],
            [
                'name' => '(GMT+01:00) West Africa Standard Time - Lagos',
                'timezone' => 'Africa/Lagos'
            ],
            [
                'name' => '(GMT+01:00) West Africa Standard Time - Ndjamena',
                'timezone' => 'Africa/Ndjamena'
            ],
            [
                'name' => '(GMT+01:00) Western Sahara Time',
                'timezone' => 'Africa/El_Aaiun'
            ],
            [
                'name' => '(GMT+02:00) Central Africa Time - Juba',
                'timezone' => 'Africa/Juba'
            ],
            [
                'name' => '(GMT+02:00) Central Africa Time - Khartoum',
                'timezone' => 'Africa/Khartoum'
            ],
            [
                'name' => '(GMT+02:00) Central Africa Time - Maputo',
                'timezone' => 'Africa/Maputo'
            ],
            [
                'name' => '(GMT+02:00) Central Africa Time - Windhoek',
                'timezone' => 'Africa/Windhoek'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Standard Time - Kaliningrad',
                'timezone' => 'Europe/Kaliningrad'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Standard Time - Tripoli',
                'timezone' => 'Africa/Tripoli'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Athens',
                'timezone' => 'Europe/Athens'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Beirut',
                'timezone' => 'Asia/Beirut'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Bucharest',
                'timezone' => 'Europe/Bucharest'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Cairo',
                'timezone' => 'Africa/Cairo'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Chisinau',
                'timezone' => 'Europe/Chisinau'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Gaza',
                'timezone' => 'Asia/Gaza'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Hebron',
                'timezone' => 'Asia/Hebron'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Helsinki',
                'timezone' => 'Europe/Helsinki'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Kyiv',
                'timezone' => 'Europe/Kiev'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Kyiv',
                'timezone' => 'Europe/Uzhgorod'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Kyiv',
                'timezone' => 'Europe/Zaporozhye'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Nicosia',
                'timezone' => 'Asia/Nicosia'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Riga',
                'timezone' => 'Europe/Riga'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Sofia',
                'timezone' => 'Europe/Sofia'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Tallinn',
                'timezone' => 'Europe/Tallinn'
            ],
            [
                'name' => '(GMT+02:00) Eastern European Time - Vilnius',
                'timezone' => 'Europe/Vilnius'
            ],
            [
                'name' => '(GMT+02:00) Famagusta Time',
                'timezone' => 'Asia/Famagusta'
            ],
            [
                'name' => '(GMT+02:00) Israel Time',
                'timezone' => 'Asia/Jerusalem'
            ],
            [
                'name' => '(GMT+02:00) South Africa Standard Time',
                'timezone' => 'Africa/Johannesburg'
            ],
            [
                'name' => '(GMT+03:00) Arabian Standard Time - Baghdad',
                'timezone' => 'Asia/Baghdad'
            ],
            [
                'name' => '(GMT+03:00) Arabian Standard Time - Qatar',
                'timezone' => 'Asia/Qatar'
            ],
            [
                'name' => '(GMT+03:00) Arabian Standard Time - Riyadh',
                'timezone' => 'Asia/Riyadh'
            ],
            [
                'name' => '(GMT+03:00) East Africa Time',
                'timezone' => 'Africa/Nairobi'
            ],
            [
                'name' => '(GMT+03:00) Jordan Time',
                'timezone' => 'Asia/Amman'
            ],
            [
                'name' => '(GMT+03:00) Kirov Time',
                'timezone' => 'Europe/Kirov'
            ],
            [
                'name' => '(GMT+03:00) Moscow Standard Time - Minsk',
                'timezone' => 'Europe/Minsk'
            ],
            [
                'name' => '(GMT+03:00) Moscow Standard Time - Moscow',
                'timezone' => 'Europe/Moscow'
            ],
            [
                'name' => '(GMT+03:00) Moscow Standard Time - Simferopol',
                'timezone' => 'Europe/Simferopol'
            ],
            [
                'name' => '(GMT+03:00) Syria Time',
                'timezone' => 'Asia/Damascus'
            ],
            [
                'name' => '(GMT+03:00) Türkiye Time',
                'timezone' => 'Europe/Istanbul'
            ],
            [
                'name' => '(GMT+03:00) Volgograd Standard Time',
                'timezone' => 'Europe/Volgograd'
            ],
            [
                'name' => '(GMT+03:30) Iran Standard Time',
                'timezone' => 'Asia/Tehran'
            ],
            [
                'name' => '(GMT+04:00) Armenia Standard Time',
                'timezone' => 'Asia/Yerevan'
            ],
            [
                'name' => '(GMT+04:00) Astrakhan Time',
                'timezone' => 'Europe/Astrakhan'
            ],
            [
                'name' => '(GMT+04:00) Azerbaijan Standard Time',
                'timezone' => 'Asia/Baku'
            ],
            [
                'name' => '(GMT+04:00) Georgia Standard Time',
                'timezone' => 'Asia/Tbilisi'
            ],
            [
                'name' => '(GMT+04:00) Gulf Standard Time',
                'timezone' => 'Asia/Dubai'
            ],
            [
                'name' => '(GMT+04:00) Mauritius Standard Time',
                'timezone' => 'Indian/Mauritius'
            ],
            [
                'name' => '(GMT+04:00) Réunion Time',
                'timezone' => 'Indian/Reunion'
            ],
            [
                'name' => '(GMT+04:00) Samara Standard Time',
                'timezone' => 'Europe/Samara'
            ],
            [
                'name' => '(GMT+04:00) Saratov Time',
                'timezone' => 'Europe/Saratov'
            ],
            [
                'name' => '(GMT+04:00) Seychelles Time',
                'timezone' => 'Indian/Mahe'
            ],
            [
                'name' => '(GMT+04:00) Ulyanovsk Time',
                'timezone' => 'Europe/Ulyanovsk'
            ],
            [
                'name' => '(GMT+04:30) Afghanistan Time',
                'timezone' => 'Asia/Kabul'
            ],
            [
                'name' => '(GMT+05:00) French Southern & Antarctic Time',
                'timezone' => 'Indian/Kerguelen'
            ],
            [
                'name' => '(GMT+05:00) Maldives Time',
                'timezone' => 'Indian/Maldives'
            ],
            [
                'name' => '(GMT+05:00) Mawson Time',
                'timezone' => 'Antarctica/Mawson'
            ],
            [
                'name' => '(GMT+05:00) Pakistan Standard Time',
                'timezone' => 'Asia/Karachi'
            ],
            [
                'name' => '(GMT+05:00) Tajikistan Time',
                'timezone' => 'Asia/Dushanbe'
            ],
            [
                'name' => '(GMT+05:00) Turkmenistan Standard Time',
                'timezone' => 'Asia/Ashgabat'
            ],
            [
                'name' => '(GMT+05:00) Uzbekistan Standard Time - Samarkand',
                'timezone' => 'Asia/Samarkand'
            ],
            [
                'name' => '(GMT+05:00) Uzbekistan Standard Time - Tashkent',
                'timezone' => 'Asia/Tashkent'
            ],
            [
                'name' => '(GMT+05:00) West Kazakhstan Time - Aqtau',
                'timezone' => 'Asia/Aqtau'
            ],
            [
                'name' => '(GMT+05:00) West Kazakhstan Time - Aqtobe',
                'timezone' => 'Asia/Aqtobe'
            ],
            [
                'name' => '(GMT+05:00) West Kazakhstan Time - Atyrau',
                'timezone' => 'Asia/Atyrau'
            ],
            [
                'name' => '(GMT+05:00) West Kazakhstan Time - Oral',
                'timezone' => 'Asia/Oral'
            ],
            [
                'name' => '(GMT+05:00) West Kazakhstan Time - Qyzylorda',
                'timezone' => 'Asia/Qyzylorda'
            ],
            [
                'name' => '(GMT+05:00) Yekaterinburg Standard Time',
                'timezone' => 'Asia/Yekaterinburg'
            ],
            [
                'name' => '(GMT+05:30) India Standard Time - Colombo',
                'timezone' => 'Asia/Colombo'
            ],
            [
                'name' => '(GMT+05:30) India Standard Time - Kolkata',
                'timezone' => 'Asia/Kolkata'
            ],
            [
                'name' => '(GMT+05:45) Nepal Time',
                'timezone' => 'Asia/Kathmandu'
            ],
            [
                'name' => '(GMT+06:00) Bangladesh Standard Time',
                'timezone' => 'Asia/Dhaka'
            ],
            [
                'name' => '(GMT+06:00) Bhutan Time',
                'timezone' => 'Asia/Thimphu'
            ],
            [
                'name' => '(GMT+06:00) East Kazakhstan Time - Almaty',
                'timezone' => 'Asia/Almaty'
            ],
            [
                'name' => '(GMT+06:00) East Kazakhstan Time - Kostanay',
                'timezone' => 'Asia/Qostanay'
            ],
            [
                'name' => '(GMT+06:00) Indian Ocean Time',
                'timezone' => 'Indian/Chagos'
            ],
            [
                'name' => '(GMT+06:00) Kyrgyzstan Time',
                'timezone' => 'Asia/Bishkek'
            ],
            [
                'name' => '(GMT+06:00) Omsk Standard Time',
                'timezone' => 'Asia/Omsk'
            ],
            [
                'name' => '(GMT+06:00) Urumqi Time',
                'timezone' => 'Asia/Urumqi'
            ],
            [
                'name' => '(GMT+06:00) Vostok Time',
                'timezone' => 'Antarctica/Vostok'
            ],
            [
                'name' => '(GMT+06:30) Cocos Islands Time',
                'timezone' => 'Indian/Cocos'
            ],
            [
                'name' => '(GMT+06:30) Myanmar Time',
                'timezone' => 'Asia/Yangon'
            ],
            [
                'name' => '(GMT+07:00) Barnaul Time',
                'timezone' => 'Asia/Barnaul'
            ],
            [
                'name' => '(GMT+07:00) Christmas Island Time',
                'timezone' => 'Indian/Christmas'
            ],
            [
                'name' => '(GMT+07:00) Davis Time',
                'timezone' => 'Antarctica/Davis'
            ],
            [
                'name' => '(GMT+07:00) Hovd Standard Time',
                'timezone' => 'Asia/Hovd'
            ],
            [
                'name' => '(GMT+07:00) Indochina Time - Bangkok',
                'timezone' => 'Asia/Bangkok'
            ],
            [
                'name' => '(GMT+07:00) Indochina Time - Ho Chi Minh City',
                'timezone' => 'Asia/Ho_Chi_Minh'
            ],
            [
                'name' => '(GMT+07:00) Krasnoyarsk Standard Time - Krasnoyarsk',
                'timezone' => 'Asia/Krasnoyarsk'
            ],
            [
                'name' => '(GMT+07:00) Krasnoyarsk Standard Time - Novokuznetsk',
                'timezone' => 'Asia/Novokuznetsk'
            ],
            [
                'name' => '(GMT+07:00) Novosibirsk Standard Time',
                'timezone' => 'Asia/Novosibirsk'
            ],
            [
                'name' => '(GMT+07:00) Tomsk Time',
                'timezone' => 'Asia/Tomsk'
            ],
            [
                'name' => '(GMT+07:00) Western Indonesia Time - Jakarta',
                'timezone' => 'Asia/Jakarta'
            ],
            [
                'name' => '(GMT+07:00) Western Indonesia Time - Pontianak',
                'timezone' => 'Asia/Pontianak'
            ],
            [
                'name' => '(GMT+08:00) Australian Western Standard Time',
                'timezone' => 'Australia/Perth'
            ],
            [
                'name' => '(GMT+08:00) Brunei Darussalam Time',
                'timezone' => 'Asia/Brunei'
            ],
            [
                'name' => '(GMT+08:00) Central Indonesia Time',
                'timezone' => 'Asia/Makassar'
            ],
            [
                'name' => '(GMT+08:00) China Standard Time - Macao',
                'timezone' => 'Asia/Macau'
            ],
            [
                'name' => '(GMT+08:00) China Standard Time - Shanghai',
                'timezone' => 'Asia/Shanghai'
            ],
            [
                'name' => '(GMT+08:00) Hong Kong Standard Time',
                'timezone' => 'Asia/Hong_Kong'
            ],
            [
                'name' => '(GMT+08:00) Irkutsk Standard Time',
                'timezone' => 'Asia/Irkutsk'
            ],
            [
                'name' => '(GMT+08:00) Malaysia Time - Kuala Lumpur',
                'timezone' => 'Asia/Kuala_Lumpur'
            ],
            [
                'name' => '(GMT+08:00) Malaysia Time - Kuching',
                'timezone' => 'Asia/Kuching'
            ],
            [
                'name' => '(GMT+08:00) Philippine Standard Time',
                'timezone' => 'Asia/Manila'
            ],
            [
                'name' => '(GMT+08:00) Singapore Standard Time',
                'timezone' => 'Asia/Singapore'
            ],
            [
                'name' => '(GMT+08:00) Taipei Standard Time',
                'timezone' => 'Asia/Taipei'
            ],
            [
                'name' => '(GMT+08:00) Ulaanbaatar Standard Time - Choibalsan',
                'timezone' => 'Asia/Choibalsan'
            ],
            [
                'name' => '(GMT+08:00) Ulaanbaatar Standard Time - Ulaanbaatar',
                'timezone' => 'Asia/Ulaanbaatar'
            ],
            [
                'name' => '(GMT+08:45) Australian Central Western Standard Time',
                'timezone' => 'Australia/Eucla'
            ],
            [
                'name' => '(GMT+09:00) East Timor Time',
                'timezone' => 'Asia/Dili'
            ],
            [
                'name' => '(GMT+09:00) Eastern Indonesia Time',
                'timezone' => 'Asia/Jayapura'
            ],
            [
                'name' => '(GMT+09:00) Japan Standard Time',
                'timezone' => 'Asia/Tokyo'
            ],
            [
                'name' => '(GMT+09:00) Korean Standard Time - Pyongyang',
                'timezone' => 'Asia/Pyongyang'
            ],
            [
                'name' => '(GMT+09:00) Korean Standard Time - Seoul',
                'timezone' => 'Asia/Seoul'
            ],
            [
                'name' => '(GMT+09:00) Palau Time',
                'timezone' => 'Pacific/Palau'
            ],
            [
                'name' => '(GMT+09:00) Yakutsk Standard Time - Chita',
                'timezone' => 'Asia/Chita'
            ],
            [
                'name' => '(GMT+09:00) Yakutsk Standard Time - Khandyga',
                'timezone' => 'Asia/Khandyga'
            ],
            [
                'name' => '(GMT+09:00) Yakutsk Standard Time - Yakutsk',
                'timezone' => 'Asia/Yakutsk'
            ],
            [
                'name' => '(GMT+09:30) Australian Central Standard Time',
                'timezone' => 'Australia/Darwin'
            ],
            [
                'name' => '(GMT+10:00) Australian Eastern Standard Time - Brisbane',
                'timezone' => 'Australia/Brisbane'
            ],
            [
                'name' => '(GMT+10:00) Australian Eastern Standard Time - Lindeman',
                'timezone' => 'Australia/Lindeman'
            ],
            [
                'name' => '(GMT+10:00) Chamorro Standard Time',
                'timezone' => 'Pacific/Guam'
            ],
            [
                'name' => '(GMT+10:00) Chuuk Time',
                'timezone' => 'Pacific/Chuuk'
            ],
            [
                'name' => '(GMT+10:00) Papua New Guinea Time',
                'timezone' => 'Pacific/Port_Moresby'
            ],
            [
                'name' => '(GMT+10:00) Vladivostok Standard Time - Ust-Nera',
                'timezone' => 'Asia/Ust-Nera'
            ],
            [
                'name' => '(GMT+10:00) Vladivostok Standard Time - Vladivostok',
                'timezone' => 'Asia/Vladivostok'
            ],
            [
                'name' => '(GMT+10:30) Central Australia Time - Adelaide',
                'timezone' => 'Australia/Adelaide'
            ],
            [
                'name' => '(GMT+10:30) Central Australia Time - Broken Hill',
                'timezone' => 'Australia/Broken_Hill'
            ],
            [
                'name' => '(GMT+11:00) Bougainville Time',
                'timezone' => 'Pacific/Bougainville'
            ],
            [
                'name' => '(GMT+11:00) Casey Time',
                'timezone' => 'Antarctica/Casey'
            ],
            [
                'name' => '(GMT+11:00) Eastern Australia Time - Hobart',
                'timezone' => 'Australia/Hobart'
            ],
            [
                'name' => '(GMT+11:00) Eastern Australia Time - Macquarie',
                'timezone' => 'Antarctica/Macquarie'
            ],
            [
                'name' => '(GMT+11:00) Eastern Australia Time - Melbourne',
                'timezone' => 'Australia/Melbourne'
            ],
            [
                'name' => '(GMT+11:00) Eastern Australia Time - Sydney',
                'timezone' => 'Australia/Sydney'
            ],
            [
                'name' => '(GMT+11:00) Kosrae Time',
                'timezone' => 'Pacific/Kosrae'
            ],
            [
                'name' => '(GMT+11:00) Lord Howe Time',
                'timezone' => 'Australia/Lord_Howe'
            ],
            [
                'name' => '(GMT+11:00) Magadan Standard Time',
                'timezone' => 'Asia/Magadan'
            ],
            [
                'name' => '(GMT+11:00) New Caledonia Standard Time',
                'timezone' => 'Pacific/Noumea'
            ],
            [
                'name' => '(GMT+11:00) Ponape Time',
                'timezone' => 'Pacific/Pohnpei'
            ],
            [
                'name' => '(GMT+11:00) Sakhalin Standard Time',
                'timezone' => 'Asia/Sakhalin'
            ],
            [
                'name' => '(GMT+11:00) Solomon Islands Time',
                'timezone' => 'Pacific/Guadalcanal'
            ],
            [
                'name' => '(GMT+11:00) Srednekolymsk Time',
                'timezone' => 'Asia/Srednekolymsk'
            ],
            [
                'name' => '(GMT+11:00) Vanuatu Standard Time',
                'timezone' => 'Pacific/Efate'
            ],
            [
                'name' => '(GMT+12:00) Anadyr Standard Time',
                'timezone' => 'Asia/Anadyr'
            ],
            [
                'name' => '(GMT+12:00) Fiji Standard Time',
                'timezone' => 'Pacific/Fiji'
            ],
            [
                'name' => '(GMT+12:00) Gilbert Islands Time',
                'timezone' => 'Pacific/Tarawa'
            ],
            [
                'name' => '(GMT+12:00) Marshall Islands Time - Kwajalein',
                'timezone' => 'Pacific/Kwajalein'
            ],
            [
                'name' => '(GMT+12:00) Marshall Islands Time - Majuro',
                'timezone' => 'Pacific/Majuro'
            ],
            [
                'name' => '(GMT+12:00) Nauru Time',
                'timezone' => 'Pacific/Nauru'
            ],
            [
                'name' => '(GMT+12:00) Norfolk Island Time',
                'timezone' => 'Pacific/Norfolk'
            ],
            [
                'name' => '(GMT+12:00) Petropavlovsk-Kamchatski Standard Time',
                'timezone' => 'Asia/Kamchatka'
            ],
            [
                'name' => '(GMT+12:00) Tuvalu Time',
                'timezone' => 'Pacific/Funafuti'
            ],
            [
                'name' => '(GMT+12:00) Wake Island Time',
                'timezone' => 'Pacific/Wake'
            ],
            [
                'name' => '(GMT+12:00) Wallis & Futuna Time',
                'timezone' => 'Pacific/Wallis'
            ],
            [
                'name' => '(GMT+13:00) Apia Standard Time',
                'timezone' => 'Pacific/Apia'
            ],
            [
                'name' => '(GMT+13:00) New Zealand Time',
                'timezone' => 'Pacific/Auckland'
            ],
            [
                'name' => '(GMT+13:00) Phoenix Islands Time',
                'timezone' => 'Pacific/Kanton'
            ],
            [
                'name' => '(GMT+13:00) Tokelau Time',
                'timezone' => 'Pacific/Fakaofo'
            ],
            [
                'name' => '(GMT+13:00) Tonga Standard Time',
                'timezone' => 'Pacific/Tongatapu'
            ],
            [
                'name' => '(GMT+13:45) Chatham Time',
                'timezone' => 'Pacific/Chatham'
            ],
            [
                'name' => '(GMT+14:00) Line Islands Time',
                'timezone' => 'Pacific/Kiritimati'
            ],
        ];

        Timezone::insert($timezones);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
