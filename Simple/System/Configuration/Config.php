<?php

//     _____ _                 _       _____ __  __  _____ 
//    / ____(_)               | |     / ____|  \/  |/ ____|
//   | (___  _ _ __ ___  _ __ | | ___| |    | \  / | (___  
//    \___ \| | '_ ` _ \| '_ \| |/ _ \ |    | |\/| |\___ \ 
//    ____) | | | | | | | |_) | |  __/ |____| |  | |____) |
//   |_____/|_|_| |_| |_| .__/|_|\___|\_____|_|  |_|_____/ 
//                      | |                                
//                      |_|                                
// Arcturus Edition

// MYSQL settings
CMS::$Config['mysql']['host'] = '127.0.0.1';
CMS::$Config['mysql']['user'] = 'root';
CMS::$Config['mysql']['pass'] = '';
CMS::$Config['mysql']['dbname'] = 'test';
CMS::$Config['mysql']['port'] = 3306;

// Cache settings
CMS::$Config['cms']['cachehost'] = '127.0.0.1';
CMS::$Config['cms']['cacheport'] = 11211;

// Default language setting
CMS::$Config['cms']['language'] = 'nl_NL';

// Global settings
CMS::$Config['cms']['url'] = 'http://localhost';
CMS::$Config['cms']['contactmail'] = 'info@Yabbis.nl';
CMS::$Config['cms']['twittername'] = '';
CMS::$Config['cms']['facebookname'] = 'yabbisbe';
CMS::$Config['cms']['hotelname'] = 'Bright';

// Register settings
CMS::$Config['register']['motto'] = 'Bleep bleep! Ik ben nieuw op Yabbis!';
CMS::$Config['register']['avatar'] = 'hr-155-1028.hd-180-1.ch-3015-1426.lg-275-110.sh-290-1408.fa-3296-61.ca-1814-61.wa-2001-61&gender=M';
CMS::$Config['register']['credits'] = 50000000;
CMS::$Config['register']['duckets'] = 100;
CMS::$Config['register']['diamonds'] = 0;
CMS::$Config['register']['crowns'] = 0;
CMS::$Config['register']['home_room'] = 0;

// Client settings
CMS::$Config['client']['production'] = 'http://localhost/cdn/gordon/PRODUCTION-201904011212-888653470';
CMS::$Config['client']['flashtexts'] = 'http://localhost/cdn/gamedata/external_flash_texts.txt';
CMS::$Config['client']['variables'] = 'http://localhost/cdn/gamedata/external_variables.txt?2';
CMS::$Config['client']['figurepart'] = 'http://localhost/cdn/gamedata/figuredata.xml?4';
CMS::$Config['client']['productdata'] = 'http://localhost/cdn/gamedata/productdata.txt';
CMS::$Config['client']['furnidata'] = 'http://localhost/cdn/gamedata/furnidata.xml?4';
CMS::$Config['client']['overridetexts'] = 'http://localhost/cdn/gamedata/override/external_flash_override_texts.txt';
CMS::$Config['client']['overridevariables'] = 'http://localhost/cdn/gamedata/override/external_override_variables.txt?2';
CMS::$Config['client']['hotelswf'] = 'patched-habbo.swf'; //face_U_leet36.swf
CMS::$Config['client']['loadtext'] = 'Tags inladen.../Wow! Dit is Yabbis.../DataController inladen...';
CMS::$Config['client']['url'] = 'http://localhost';

// Emulator settings
CMS::$Config['client']['host'] = '127.0.0.1';
CMS::$Config['client']['port'] = 3000;
CMS::$Config['cms']['mushost'] = '127.0.0.1';
CMS::$Config['cms']['musport'] = 3001;

// Forgot password settings
CMS::$Config['mail']['sender'] = 'noreply@yabbis.nl';
CMS::$Config['mail']['smtphost'] = 'in-v3.mailjet.com';
CMS::$Config['mail']['smtpauth'] = true;
CMS::$Config['mail']['smtpusername'] = '10fc75e7a40b504a109b56be1f779c66';
CMS::$Config['mail']['smtppassword'] = 'f31d5107004ff4ead2ed3cee3cb8a9b3';
CMS::$Config['mail']['smtppsecure'] = 'tls';
CMS::$Config['mail']['smtpport'] = 587;
CMS::$Config['mail']['timebetweennextmail'] = 60; // in minutes

// Radio settings
CMS::$Config['cms']['radio'] = false;
CMS::$Config['cms']['radiostream'] = 'https://stream.habplay.nl/live';

// Ads settings
CMS::$Config['cms']['ads'] = false;
CMS::$Config['cms']['data-ad-client'] = 'ca-pub-6650641383139753';
CMS::$Config['cms']['data-ad-slot'] = '4965058936';

// Badgeshop settings
CMS::$Config['cms']['discountperitem'] = 2; // in percentage
CMS::$Config['cms']['maxdiscount'] = 25; // in percentage

// Housekeeping ranks
CMS::$Config['manage']['dashboard'] = '|31|30|29|28|';
CMS::$Config['manage']['news'] = '|31|30|29|28|';
CMS::$Config['manage']['users'] = '|31|30|29|';
CMS::$Config['manage']['wordfilter'] = '|31|30|';
CMS::$Config['manage']['ban'] = '|31|30|29|28|';
CMS::$Config['manage']['values'] = '|31|30|29|28|';
CMS::$Config['manage']['filter'] = '|31|30|29|';
CMS::$Config['manage']['chatlogs'] = '|31|';

// Social media settings
CMS::$Config['cms']['facebooksecret'] = '';
CMS::$Config['cms']['facebookappid'] = '';

// Captcha settings
CMS::$Config['cms']['captcha'] = false;
CMS::$Config['cms']['captchapublickey'] = '6LcQR-gUAAAAALv0-WBeAlx-M2X2sqSqEHTeZHR1';
CMS::$Config['cms']['captchaprivatekey'] = '6LcQR-gUAAAAAJ4k2PwFsdabCEss5JFwv71bCJYA';
CMS::$Config['cms']['captchamaxlogins'] = 5;
CMS::$Config['cms']['captchamaxloginstime'] = 3600;

// Tags settings
CMS::$Config['cms']['maxtags'] = 25; // Max tags users can add
CMS::$Config['cms']['maxpopulartags'] = 25; // How much popular tags can be showed

// Name change settings
CMS::$Config['cms']['namechangecost'] = 50; // in diamonds
CMS::$Config['cms']['timebetweennamechange'] = 1; // in days

// Security settings
CMS::$Config['cms']['staffonlyemail'] = false; // Staff can only login with their email
CMS::$Config['cms']['maxaccountsperip'] = 5; // How much accounts can you create on 1 ip

// News settings
CMS::$Config['cms']['deletenewscommentrank'] = 3; // minimum rank
CMS::$Config['cms']['timebetweennewspost'] = 5; // in minutes

// Presents settings
CMS::$Config['cms']['minpresentreward'] = 1; // in diamonds
CMS::$Config['cms']['maxpresentreward'] = 5; // in diamonds

// Map settings
CMS::$Config['cms']['badgemap'] = 'http://localhost/cdn/c_images/album1584';
CMS::$Config['cms']['groupbadgemap'] = 'http://localhost/cdn/c_images/Badgeparts/generated/';
CMS::$Config['cms']['furniiconmap'] = 'http://localhost/cdn/dcr/hof_furni/';
CMS::$Config['cms']['avatarlocation'] = 'http://localhost/avatar/avatarimage.php';

// Voucher settings
CMS::$Config['cms']['vouchercreatecost'] = 5; // in diamonds
CMS::$Config['cms']['vouchertimetillexpire'] = 31; // in days
CMS::$Config['cms']['voucherminimalamount'] = 25; // minimal required amount of currency in total

// Time
CMS::$Config['cms']['timebetweenemailchange'] = 1; // in hours
CMS::$Config['cms']['timebetweenpasschange'] = 1; // in hours


CMS::$Config['cms']['maxwebphotos'] = 6;


