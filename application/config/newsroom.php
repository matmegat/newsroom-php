<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
global $env;

// the hostname pattern such that the newsroom 
// name can be extracted as the matched part
$config['host_pattern'] = '#^([a-z0-9\-]+)\.i-newswire\.com$#is';

// this is appended to newsroom name 
$config['host_suffix'] = '.i-newswire.com';

// the detached hostname pattern such that the newsroom 
// name can be extracted as the matched part
$config['detached_pattern'] = '#^([a-z0-9\-]+)\.preview\.i-newswire\.com$#is';

// this is appended to newsroom name 
$config['detached_suffix'] = '.preview.i-newswire.com';

// this is prepended to newsroom name 
$config['detached_prefix'] = '';

// base directory of i-newswire site
$config['compat_dir'] = '/home/freepr/public_html';

// the directory that accepts file uploads
$config['upload_dir'] = '/home/freepr/public_html_newsroom/files';

// the url prefix for file uploads
$config['upload_url'] = 'files';

// max filesize for audio uploads
$config['max_audio_size'] = 52428800; 

// max filesize for web file uploads
$config['max_web_file_size'] = 10485760; 

// common functions hostname name
$config['common_host_name'] = 'co';

// common functions hostname (must have host suffix)
$config['common_host'] = "{$config['common_host_name']}{$config['host_suffix']}";

// the detached hostname pattern such that the newsroom 
// name can be extracted as the matched part
$config['admo_pattern'] = '#^([0-9]+)\.admo\.i-newswire\.com$#is';

// this is appended to newsroom name 
$config['admo_suffix'] = '.admo.i-newswire.com';

// this is prepended to newsroom name 
$config['admo_prefix'] = '';

// iPublish facebook application
$config['facebook_app'] = array();
$config['facebook_app']['api'] = array();
$config['facebook_app']['api']['appId'] = '586234651427775';
$config['facebook_app']['api']['secret'] = '3aad98ff71ed68db2fe731b54d9babfd';

// the base url for all facebook authorization logic
$config['facebook_app']['base_url'] = "http://{$config['common_host']}/common/facebook_auth_request";

// iPublish twitter application
$config['twitter_app'] = array();
$config['twitter_app']['api'] = array();
$config['twitter_app']['api']['key'] = 'PyzgPqcqpgQ4U1pE9TQQ';
$config['twitter_app']['api']['secret'] = 'Z4KOIkSpZXSvgdXEYJl7osfl3dg6fTI6qD5V35dw';

// the base url for all twitter authorization logic
$config['twitter_app']['base_url'] = "http://{$config['common_host']}/common/twitter_auth_request";

// the base url for piwik stats
$config['piwik_base_url'] = 'http://stats.i-newswire.com';

// the site id for use with piwiki
$config['piwik_site_id'] = 1;

// authentication token for piwik api
$config['piwik_auth_token'] = 'db0268c3f7852f6894d06448d53f62b6';

// admin authentication token for piwik api
$config['piwik_admin_auth_token'] = '92b6058d7ee184166c6a1f985bfb800d';

// address to send emails from
$config['email_address'] = 'notification@i-newswire.com';

// name to send emails from
$config['email_name'] = 'iNewswire Notifications';

// whether to copy all email campaigns to given email
$config['campaign_recorded_active'] = false;

// email to BCC for all email campaigns
$config['campaign_recorded_email'] = '';

// secret used to generate unsubscribe hashes
$config['unsubscribe_secret'] = 'a281e825d55b7ca9081be43134975261';

// base url for contact unsubscribe action
$config['unsubscribe_base_url'] = "http://{$config['common_host']}/common/contact_unsubscribe";

// file that contains authentication secret
$config['auth_secret_file'] = 'application/config/auth_secret.php';

// file that contains api secret
$config['iella_secret_file'] = 'application/config/iella_secret.php';

// the base url for the iella api (optional use)
$config['iella_base_url'] = "http://{$config['common_host']}/api/iella/";

// the hostname of the main i-newswire website
$config['website_host'] = $env['website_host'];

// the url of the main i-newswire website (end in slash)
$config['website_url'] = "{$env['protocol']}{$env['website_host']}/";

// this is the new website hostname
$config['website_tunnel_host'] = $env['website_tunnel_host'];

// the url of the main i-newswire panel (account)
$config['non_migrated_url'] = "{$config['website_url']}MyAccount";

// the path and arguments to msmtp
$config['mailer_exec'] = '/usr/bin/msmtp -t';

// the fin distribution check url prefix
$config['fin_distribution_url'] = 'http://tracking.prconnect.com/inewswire?Module=clipping-top100&SourceID=%s';

// the base url for assets folder
$config['assets_base'] = "{$env['protocol']}{$config['website_host']}/assets/";

// the ip address used for the main website
// newsroom domains must use this
$config['ip_address'] = '72.52.139.138';

// the default timezone to use when there is 
// no user timezone specified
// DO NOT CHANGE THIS
$config['timezone'] = 'UTC';

// the api access credentials for ultracart
$config['ultracart_api'] = array();
$config['ultracart_api']['login'] = 'jonpike';
$config['ultracart_api']['merchantId'] = 'SCURE';
$config['ultracart_api']['password'] = 'v3XK03i85h67XDi';

// the api access details for scribed
$config['docsite_scribd'] = array();
$config['docsite_scribd']['url'] = 'http://api.scribd.com/api';
$config['docsite_scribd']['api_key'] = '1otk2p5d67v6moc0fffum';
$config['docsite_scribd']['url_doc'] = 'http://www.scribd.com/doc/%d';

// the api access details for issuu
$config['docsite_issuu'] = array();
$config['docsite_issuu']['url_upload'] = 'http://upload.issuu.com/1_0';
$config['docsite_issuu']['url_api'] = 'http://api.issuu.com/1_0';
$config['docsite_issuu']['api_key'] = 'xchfsd40futii22s63gj8tno1uw6amp2';
$config['docsite_issuu']['secret'] = 'ssrtx9v8p2insohyogj27aw98e7apecx';
$config['docsite_issuu']['url_doc'] = 'http://issuu.com/inewswire/docs/%s';

// the api access details for issuu
$config['docsite_issuu'] = array();
$config['docsite_issuu']['url_upload'] = 'http://upload.issuu.com/1_0';
$config['docsite_issuu']['url_api'] = 'http://api.issuu.com/1_0';
$config['docsite_issuu']['api_key'] = 'j5jQ2sg8';
$config['docsite_issuu']['secret'] = '3Waxu7CQ';
$config['docsite_issuu']['username'] = 'i-newswire';
$config['docsite_issuu']['password'] = 'mikeesan11';
$config['docsite_issuu']['url_doc'] = 'http://issuu.com/inewswire/docs/%s';

// a list of names that are not allowed
$config['reserved_names'] = array('^detached$', '^admo$');

// the sizes of the default images
$config['v_sizes'] = array();

// the sizes of the default thumb images
$config['v_sizes']['thumb'] = new stdClass();
$config['v_sizes']['thumb']->width = 184;
$config['v_sizes']['thumb']->height = 106;
$config['v_sizes']['thumb']->cropped = true;

// the sizes of the default header logo images
$config['v_sizes']['header'] = new stdClass(); 
$config['v_sizes']['header']->format = Image::FORMAT_PNG;
$config['v_sizes']['header']->width = 200;
$config['v_sizes']['header']->height = 60;
$config['v_sizes']['header']->cropped = false;

// the sizes of the header thumb images
$config['v_sizes']['header-thumb'] = new stdClass();
$config['v_sizes']['header-thumb']->width = 184;
$config['v_sizes']['header-thumb']->height = 106;
$config['v_sizes']['header-thumb']->cropped = true;
$config['v_sizes']['header-thumb']->max_ratio_diff = 0.25;
$config['v_sizes']['header-thumb']->max_ratio_diff_margin = 5;

// the sizes of the header finger images
$config['v_sizes']['header-finger'] = new stdClass();
$config['v_sizes']['header-finger']->width = 76;
$config['v_sizes']['header-finger']->height = 76;
$config['v_sizes']['header-finger']->cropped = true;

// the sizes of the default header-sidebar logo images
$config['v_sizes']['header-sidebar'] = new stdClass(); 
$config['v_sizes']['header-sidebar']->format = Image::FORMAT_PNG;
$config['v_sizes']['header-sidebar']->width = 200;
$config['v_sizes']['header-sidebar']->height = 80;
$config['v_sizes']['header-sidebar']->cropped = false;

// the sizes of the default finger images
$config['v_sizes']['finger'] = new stdClass(); 
$config['v_sizes']['finger']->width = 80;
$config['v_sizes']['finger']->height = 50;
$config['v_sizes']['finger']->cropped = true;

// the sizes of the default web images
$config['v_sizes']['web'] = new stdClass(); 
$config['v_sizes']['web']->width = 140;
$config['v_sizes']['web']->height = 140;
$config['v_sizes']['web']->cropped = true;

// the sizes of the default web images (view page)
$config['v_sizes']['view-web'] = new stdClass(); 
$config['v_sizes']['view-web']->width = 160;
$config['v_sizes']['view-web']->height = 110;
$config['v_sizes']['view-web']->cropped = true;

// the sizes of the default web images (view page cover)
$config['v_sizes']['view-cover'] = new stdClass(); 
$config['v_sizes']['view-cover']->width = 170;
$config['v_sizes']['view-cover']->height = 120;
$config['v_sizes']['view-cover']->cropped = true;

// the sizes of the default cover images
$config['v_sizes']['cover'] = new stdClass(); 
$config['v_sizes']['cover']->width = 256;
$config['v_sizes']['cover']->max_height = 256;

// the sizes of the default contact images
$config['v_sizes']['contact'] = new stdClass(); 
$config['v_sizes']['contact']->width = 92;
$config['v_sizes']['contact']->height = 92;
$config['v_sizes']['contact']->cropped = true;

// the sizes of the contact cover images
$config['v_sizes']['contact-cover'] = new stdClass(); 
$config['v_sizes']['contact-cover']->width = 162;
$config['v_sizes']['contact-cover']->height = 162;
$config['v_sizes']['contact-cover']->cropped = true;

// the sizes of the full width image
$config['v_sizes']['view-full'] = new stdClass(); 
$config['v_sizes']['view-full']->width = 540;
$config['v_sizes']['view-full']->min_width = 540;

// the sizes of the distribution logo thumbnails
$config['v_sizes']['dist-thumb'] = new stdClass(); 
$config['v_sizes']['dist-thumb']->width = 200;
$config['v_sizes']['dist-thumb']->height = 100;
$config['v_sizes']['dist-thumb']->cropped = false;

// the sizes of the distribution logo fingers
$config['v_sizes']['dist-finger'] = new stdClass(); 
$config['v_sizes']['dist-finger']->width = 100;
$config['v_sizes']['dist-finger']->height = 50;
$config['v_sizes']['dist-finger']->cropped = false;

// the sizes of the video guide thumbnails
$config['v_sizes']['video-guide'] = new stdClass(); 
$config['v_sizes']['video-guide']->width = 100;
$config['v_sizes']['video-guide']->height = 100;
$config['v_sizes']['video-guide']->cropped = true;

// load any local version of config
require_once 'newsroom.local.php';

?>
