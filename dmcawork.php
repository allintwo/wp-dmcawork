<?php
/*
 * Plugin Name: DMCA WORK
 * Plugin URI: https://mimosait.com/plugins/dmcawork
 * Description: Replace DMCA related URLs
 * Version: 1.0
 * Author: rahulaminroktim@gmail.com
 * Author URI: https://www.facebook.com/rahul.roktim/
 */


$admca_logs = [];

add_action( 'admin_menu', 'admca_updating' );

function admca_updating() {

///	add_options_page( 'Upload Prodcut Cs ', '<span style="font-size:13px;font-weight:bold;">Upload Product Csv</span>', 'manage_options', 'settings-upload-csv', 'upload_csv' );

    add_options_page( 'Update Urls', '<span style="font-size:13px;font-weight:bold;">Update DMCA URL </span>', 'manage_options', 'admca-update-urls', 'admca_update_urls' );
  //  add_options_page( 'About admca', '<span style="font-size:13px;font-weight:bold;">About ADMCA Plugin</span>', 'manage_options', 'admca-about-plugin', 'admca_about' );
}

function admca_update_slug($post_id,$old_slug)
{
    global $admca_logs;

    // update the post slug
    $newlink = $old_slug . '-'.rand(9999,999999);
    $newlink = str_replace('--','-',$newlink);
    $newlink = trim($newlink);
    if(1)  // avoid long url system
    {
      $pattarn = '#-\d+-\d+-\d+#';
      $newlink =  preg_replace($pattarn,'-'.rand(0,99999),$newlink);
      $pattarn = '#-\d+-\d+#';
      $newlink =  preg_replace($pattarn,'-'.rand(0,99999),$newlink);
    }


    wp_update_post( array(
        'ID' => $post_id,
        'post_name' => $newlink
    ));
    $admca_logs[] = [$old_slug,$newlink];
}


function admca_update_urls()
{
    global $admca_logs;
    $urls = '';
    if(isset($_POST['admcaurls']))
    {
       $urls =  admca_filter_urls($_POST['admcaurls']);
       foreach ($urls as $url)
       {
           $post_id = url_to_postid($url);
           if($post_id)
           {
              // $paramlink =get_permalink($post_id);
              // print_r($paramlink);
               $post = get_post($post_id);
               $slug = $post->post_name;
              // print_r($slug);
               admca_update_slug($post_id,$slug);
           }
       }
    }

    $admca_logs_html = '';
    if($admca_logs)
    {
        $html_logs = '';
        foreach ($admca_logs as $admca_log)
        {
            $html_logs .= "<li><i>{$admca_log[0]}</i> <b>TO</b> {$admca_log[1]}</li>";
        }
        
        
        $admca_logs_html = <<<wdfkhdskfjgdskufgdsf
<div>
<h3>Url Changes Logs </h3>
<ul>
{$html_logs}
</ul>
</div>
wdfkhdskfjgdskufgdsf;

    }

    echo <<<llfdhjglfhdglfdhgiodtyhgreoihgldfv
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<div class="container-fluid">
    <div class="bg-primary">
    <div class="card-header"> Insert URLS </div>
    <form action="" method="post">
    <div class="card-body">
    <textarea class="form-control" rows="4" cols="4" name="admcaurls"></textarea>
    </div>
<div class="card-footer">
<input type="submit" value="Change" class="btn btn-success btn-block">
</div>
 </form>
    </div>
 <div class="card-body">
    {$admca_logs_html}
</div>
</div>
llfdhjglfhdglfdhgiodtyhgreoihgldfv;
}

function admca_about()
{
    echo <<<cjkfhdskfjdsgkfdsgfkds
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<div class="container-fluid">
    <div class="bg-primary">
    <div class="card-header"> About Plugins </div>
    
    </div>
</div>
<div>
 </div>
cjkfhdskfjdsgkfdsgfkds;

}


function admca_filter_urls($urltext)
{
    $urls = [];
    $host = $_SERVER['HTTP_HOST'];
   // $host = 'movieloverhd.com';

    $pattarn = "#(http|https)\://{$host}/[^\s]+#";

 //  $pattarn = '|(http|ftp|https)://([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-])?|';

    if(preg_match_all($pattarn,$urltext,$mtc))
    {
       // print_r($mtc);
        // return $mtc[0];
        foreach ($mtc[0] as $item)
        {
            $urls[] = $item;
        }
    }
    return $urls;
}
