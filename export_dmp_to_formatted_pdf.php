<?php
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

clearstatcache();

//$jsondata = file_get_contents($_FILES['json_file']['name']);
$jsondata = file_get_contents('../text/dmp1.json');
$data = json_decode($jsondata, true);
$dmpCreatedDate = $data['dmpCreatedDate'];
$dmplastUpdateDate = $data['lastUpdateDate'];
$dmplastAccessDate = $data['lastAccessDate'];

$project_title = mysql_real_escape_string($data['projectDetails']['title']);
$project_description = mysql_real_escape_string($data['projectDetails']['description']);

$fieldOfResearch = $data['projectDetails']['fieldOfResearch'];

if (is_array($fieldOfResearch))
{
    foreach ($fieldOfResearch as $key=>$value)
    {
        $field_code[$key]=$value['code'];
        $field_name[$key]=$value['name'];
    }
}

$project_startDate = $data['projectDetails']['startDate'];
$project_endDate = $data['projectDetails']['endDate'];

$contributor=$data['contributor'];
if (is_array($contributor))
{
    foreach ($contributor as $key=>$value)
    {
        $contrib_firstname[$key]=$value['firstname'];
        $contrib_lastname[$key]=$value['lastname'];
        $contrib_role[$key]=$value['role'];
        if (is_array($contrib_role[$key]))
        {
            foreach ($contrib_role[$key] as $subkey=>$subvalue)
            {
                $subrole[$subkey]=$subvalue;
                $role[$key][]=$subrole[$subkey];
                
                //echo $role[$key];
            }
        }
        $contrib_affiliation[$key]=$value['affiliation'];
        $contrib_email[$key]=$value['email'];
        $contrib_username[$key]=$value['username'];
        $contrib_orcid[$key]=$value['orcid'];
    }
}

$funding = $data['funding'];
if (is_array($funding))
{
    foreach ($funding as $key=>$value)
    {
        $funder_name[$key]=$value['funder'];
        $funder_code[$key]=$value['funderID'];
        $researchOfficeID[$key]=$value['researchOfficeID'];
    }
}

$ethicsRequired = $data['ethicsRequired'];
$iwiConsultationRequired = $data['iwiConsultationRequired'];

$document = $data['document'];
if (is_array($document))
{
    foreach ($document as $key=>$value)
    {
        $doc_shortname[$key]=$value['shortname'];
        $doc_summary[$key]=$value['summary'];
        $doc_link[$key]=$value['link'];
    }
}

$dataAsset = $data['dataAsset'];
if (is_array($dataAsset))
{
    foreach ($dataAsset as $key=>$value)
    {
        $da_shortname[$key]=$value['shortname'];
        $da_description[$key]=$value['description'];
        $da_collectionProcess[$key]=$value['collectionProcess'];
        $da_organisationProcess[$key]=$value['organisationProcess'];
        $da_storageProcess[$key]=$value['storageProcess'];
        $da_metadataRequirements[$key]=$value['metadataRequirements'];
        $da_copyrightOwner[$key]=$value['copyrightOwner'];
        $da_accessControl[$key]=$value['accessControl'];

        if (is_array($da_accessControl[$key]))
        {
            $ac_status[$key]=$da_accessControl[$key]['status'];
            $ac_details[$key]=$da_accessControl[$key]['details'];
            $ac_releaseDate[$key]=$da_accessControl[$key]['releaseDate'];
            $ac_complianceProcess[$key]=$da_accessControl[$key]['complianceProcess'];
        }
        
        $da_retention[$key] = $value['retention'];
        if (is_array($da_retention[$key]))
        {
            $retention_type[$key]=$da_retention[$key]['retentionType'];
            $retention_untildate[$key]=$da_retention[$key]['retainUntil'];
        }
        
        $da_publicationProcess[$key]=$value['publicationProcess'];
        
        $da_license[$key]=$value['license'];
        if (is_array($da_license[$key]))
        {
            $license_name[$key]=$da_license[$key]['name'];
            $license_logo[$key]=$da_license[$key]['logo'];
        }
        
        $da_archiving[$key]=$value['archiving'];
        $da_dataContact[$key]=$value['dataContact'];
        $da_requiredResources[$key]=$value['requiredResources'];
        $da_issues[$key]=$value['issues'];
        if (is_array($da_issues[$key]))
        {
            foreach ($da_issues[$key] as $subkey=>$subvalue)
            {
                $issues_type[$subkey]=$subvalue['type'];
                $issues_description[$subkey]=$subvalue['description'];
                $issues_managementProcess[$subkey]=$subvalue['managementProcess'];
            }
        }
        $policyRequirements[$key]=$value['policyRequirements'];
        if (is_array($policyRequirements[$key]))
        {
            foreach ($policyRequirements[$key] as $subkey=>$subvalue)
            {
                $policyRequirements_id[$subkey]=$subvalue['id'];
                $policyreq_controllingBody[$subkey]=$subvalue['controllingBody'];
                $policyreq_relevantText[$subkey]=$subvalue['relevantText'];
            }
        }
    }
}
$html='<!DOCTYPE html>';
$html.='<html lang="en" style="font-family: sans-serif;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">';
$html.='<head>';
$html.='<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
$html.='<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>';
$html.='<title>Data Management Plan</title>';
  
//$html.='<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
//$html.='<link href="../css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>';
//$html.='<link href="../css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>';
$html.='</head>';
$html.='<body style="margin: 0;">';
    $html.='<nav style=" color: #fff;background-color: #ee6e73;width: 100%;height: 56px;line-height: 56px;background-color: #01579b" role="navigation">';
    //$html.='Last Updated Date: '. $dmplastUpdateDate;
    $html.='<div style="position: relative; height: 100%;  margin: 0 auto; max-width: 1280px; width: 90%;">Last Updated Date: '. substr($dmplastUpdateDate,0,10);
    //$html.='<div class="nav-wrapper container"><img id="logo-container" src="files/UOA-LR-RGB.png" class="brand-logo">Logo</a>';
    //$html.='<ul class="right hide-on-med-and-down">';
    //$html.='<li><a href="#">Navbar Link</a></li>';
    //$html.='</ul>';
      //  $html.='<ul id="nav-mobile" class="side-nav">';
      //  $html.='<li><a href="#">Navbar Link</a></li>';
      //  $html.='</ul>';
      //  $html.='<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>';
    $html.='</div>';
    $html.='</nav>';
    $html.='<div style=" padding-top: 1rem; padding-bottom: 1rem; padding-bottom: 0;">';
    $html.='<h1 style="font-size: 2.2rem;line-height: 110%;margin: 2.1rem 0 1.68rem 0;color: #ff9800; display: block; text-align: center;">Data Management Plan</h1>';
    $html.='<div style=" margin: 0 auto;max-width: 1280px;width: 90%;">';
    $html.='<br>';
    $html.='<div style="margin-left: auto;margin-right: auto;margin-bottom: 20px; text-align:center">';
    //$html.='<h5 class="header col s12 light-blue-text text-darken-4">Data Management Plan for Project "'. $project_title .'" </h5>';
    $html.='<h5 style="font-size: 1.64rem;line-height: 110%;margin: 0.82rem 0 0.656rem 0;font-weight:400; line-height: 1.1;display: block; width: 100%; margin-left:auto; left:auto; right:auto;color: #01579b">Project: "'. $project_title .'" </h5>';
    $html.='</div>';
      $html.='<br>';
    $html.='</div>';
  $html.='</div>';

$arr1=['Project Title', 'Description'];
$res1=[$project_title, $project_description];
$arr2=['DMP Created Date', 'DMP Last Updated Date', 'Project Start Date', 'Project End Date'];
$res2=[$dmpCreatedDate, $dmplastUpdateDate, $project_startDate, $project_endDate];
$arr3=['Full Name', 'Affiliation', 'Email', 'User Name', 'Role'];
//$res3=[$contrib_firstname, $contrib_lastname, $contrib_affiliation, $contrib_email, $contrib_username, $contrib_orcid];
$arr4=['Funding Agency', 'Fudning ID', 'Research Office ID'];
$res4=[$funder_name, $funder_code, $researchOfficeID];
$arr6=['Ethics Requirement', 'IWI Consultation Requirement'];
$res6=[$ethicsRequired, $iwiConsultationRequired];
$arr7=['Document Short Name', 'Summary', 'Link'];
$res7=[$doc_shortname, $doc_summary, $doc_link];
$arr8=['Data Type', 'Description', 'Data Collection Process', 'Data Organisation Process', 'Data Storage Process', 
    'Meta Data Requirement', 'Copyright Owner', 'Data Publication Process'];
$res8=[$da_shortname, $da_description, $da_collectionProcess, $da_organisationProcess, $da_storageProcess, 
     $da_metadataRequirements, $da_copyrightOwner,$da_publicationProcess];
$arr9=['Data Access', 'Data Access Detail', 'Release Date', 'Compliance Process'];
$res9=[$ac_status, $ac_details, $ac_releaseDate, $ac_complianceProcess];
$arr10=['Type of Data Retention', 'Data must be retained after submission of thesis or publication of results until'];
$res10=[$retention_type, $retention_untildate];
$arr11=['Data Licensing', 'License Logo'];
$res11=[$license_name, $license_logo];
$arr14=['The long-term preservation plan for the dataset', 'Research Data Management Contact','Required Resources'];
$res14=[$da_archiving, $da_dataContact, $da_requiredResources];
$arr12=['Data Issues' ,'Issues Description'];
$res12=[$issues_type, $issues_description, $issues_managementProcess];
$arr13=['Policy ID','Policy Control Body', 'Policy Requirements'];
$res13=[$policyRequirements_id, $policyreq_controllingBody, $policyreq_relevantText];

   
   $html.='<div style=" margin: 0 auto;max-width: 1280px;width: 90%;">';
   $html.='<div style=" padding-top: 1rem;padding-bottom: 1rem;">';

      $html.='<div style="margin-left: auto;margin-right: auto;margin-bottom: 20px;>';
            $html.='<span style=" padding: 10px 20px;padding-left: 10px;padding-right: 72px;text-align: left;box-sizing: border-box;position: absolute;font-size: 1.2rem;">'.$project_description.'<br>';
            $html.='<br>';
            //$html.='<p align="left" class="grey-text text-darken-1">Field of Research: ';
            $html.='<p style="text-align=left; color: #29b6f6;">Field of Research: ';
            for ($i=0;$i<count($field_code);$i++)
            {
                $html.=$field_code[$i] . ' - '. $field_name[$i];
            }
            $html.='<br>';
            $html.='Project Start Date: '.$project_startDate .'<br>';
            $html.='Project End Date: '.$project_endDate.'</p>';
            $html.='<br>';
            $html.='<h4 style=" font-weight: 200;line-height: 1.1;  font-size: 1.64rem;line-height: 110%;margin: 1.14rem 0 0.912rem 0;">Data Collected</h4></p>';
            for ($i=0;$i<count($da_shortname);$i++)
            {
            //$html.='<div style="box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12); transition: box-shadow .25s; border=2px; padding: 20px;margin: 0.5rem 0 1rem 0; border-radius: 2px;background-color: #fff;">';
            $html.='<div style="padding: 20px;margin: 0.5rem 0 1rem 0; border-radius: 2px;background-color: #fff;">';
            $var=$i+1;
            $html.='<p style="bgcolor:#B0E0E6;"><h5 style=" font-weight: 400;line-height: 1.1; font-size: 1.28rem;line-height: 110%;margin: 0.82rem 0 0.656rem 0;">'. (string)$var .' - '.$da_shortname[$i].'</h5></p>';
            $html.='<p>'.$da_collectionProcess[$i].'</p>';
            $html.='<p>'.$da_organisationProcess[$i].'</p>';
            $html.='<p>'.$da_storageProcess[$i].'</p>';
            $html.='<p>'.$da_metadataRequirements[$i].'</p>';
            $html.='<p>'.$da_copyrightOwner[$i].'</p>';
            $html.='<p>'.$da_publicationProcess[$i].'</p>';
            
            $html.='<p><b>Sharing and Access Control</b></p>';
            $html.='<p>'.$ac_status[$i].'</p>';
            $html.='<p>'.$ac_details[$i].'</p>';
            $html.='<p>'.$ac_releaseDate[$i].'</p>';
            $html.='<p>'.$ac_complianceProcess[$i].'</p>';
            
            $html.='<p><b>Retention and Disposal</b></p>';
            $html.='<p>'.$retention_type[$i].'</p>';
            $html.='<p>Data must be retained after submission of thesis or publication of results until '.$retention_untildate[$i].'</p>';
     
            $html.='<p><b>Data Publising and Discovery</b></p>';
            $html.='<p>'.$license_name[$i].'</p>';
        
            $html.='<p><b>Long-term Archive and Preservation</b></p>';
            $html.='<p>'.$da_archiving[$i].'</p>';
        
            $html.='<p>Research Data Management Contact person is'.$da_dataContact[$i].'</p>';
            $html.='<p>'.$da_requiredResources[$i].'</p>';
            
            $html.='<p><b>Data Issues</b></p>';
            $html.='<p>'.$issues_type[$i].'</p>';
            $html.='<p>'.$issues_description[$i].'</p>';
            $html.='<p>'.$issues_managementProcess[$i].'</p>';
           
            $html.='<p><b>Policies and Guidance</b></p>';
            $html.='<p>Policy Requirement Code: '. $policyRequirements_id[$i] . '</p>';
            $html.='<p>Policy Controlling Body: '. $policyreq_controllingBody[$i] . '</p>';
            $html.='<p>'. $policyreq_relevantText[$i] . '</p>';
            $html.='</div>';
            }
            
            $html.='<h4 style=" font-weight: 200;line-height: 1.1;  font-size: 1.64rem;line-height: 110%;margin: 1.14rem 0 0.912rem 0;">Roles and Responsibilites</h4></p>';
            for ($i=0;$i<count($contrib_firstname);$i++)
            {
                $name=$contrib_firstname[$i]. ' ' . $contrib_lastname[$i];
                $html.='<div style="box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12); transition: box-shadow .25s; border=2px; padding: 20px;margin: 0.5rem 0 1rem 0; border-radius: 2px;background-color: #fff;">';
                $html.='<p>' . $name .'</p>';
                $html.='<p>' . $contrib_affiliation[$i] .'</p>';
                $html.='<p>' . $contrib_email[$i] .'</p>';
                $html.='<p>' . $contrib_username[$i] .'</p>';
                $html.='<p>' . implode(" , ",$role[$i]) .'</p>';
                $html.='</div>';
            }
            
            $html.='<h4 style="  font-weight: 400;line-height: 1.1;  font-size: 1.64rem;line-height: 110%;margin: 1.14rem 0 0.912rem 0;">Funding</h4></p>';
            for ($i=0;$i<count($funder_name);$i++)
            {   
                $html.='<div style="box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12); transition: box-shadow .25s; border=2px; padding: 20px;margin: 0.5rem 0 1rem 0; border-radius: 2px;background-color: #fff;">';
                $html.='<p> Funding Agency: ' . $funder_name[$i] .'</p>';
                $html.='<p>Code: ' . $funder_code[$i] .'</p>';
                $html.='<p>Research Office ID: ' . $researchOfficeID[$i] .'</p>';
                $html.='</div>';
            }
            
            $html.='<h4 style="  font-weight: 400;line-height: 1.1;  font-size: 1.64rem;line-height: 110%;margin: 1.14rem 0 0.912rem 0;">Ethics and Privacy</h4></p>';
            $html.='<div style="box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12); transition: box-shadow .25s; border=2px; padding: 20px;margin: 0.5rem 0 1rem 0; border-radius: 2px;background-color: #fff;">';
            for ($i=0;$i<count($arr6);$i++)
            {
                $html.='<p>' . $arr6[$i] . ' : '. $res6[$i] .'</p>';
            }
            
            for ($i=0;$i<count($doc_shortname);$i++)
            {
                $html.='<p>' . $doc_shortname[$i] . '</p>';
                $html.='<p>' . $doc_summary[$i] . '</p>';
                $html.='<p>' . $doc_link[$i] . '</p>';
            }
            $html.='</div>';
        $html.='</span>';
      $html.='</div>';
    $html.='</div>';
   $html.='</div>';
$html.='</body></html>';
//echo $html;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
//$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
//$dompdf->outputHtml();
$dompdf->stream();
exit;