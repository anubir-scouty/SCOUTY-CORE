<?php

$key = file_get_contents('thekey.txt');

require_once '../../../equinox/libs/Google/autoload.php';
require_once '../../../equinox/libs/Google/Client.php';
require_once '../../../equinox/libs/Google/Service/YouTube.php';
 
$application_name = 'Footballers FC Youtube Upload'; 
$client_id        = '1065797451514-tq6uhdq4jv9lpomo673hv0fjk65vhrc5.apps.googleusercontent.com';
$client_secret    = 'NnSK8tkryrI6z0iDapzenLDd';
$scope 	          = array('https://www.googleapis.com/auth/youtube.upload', 'https://www.googleapis.com/auth/youtube', 'https://www.googleapis.com/auth/youtubepartner');
$videoTags        = array('1','2','3');
$videoDescription = "A video tutorial on how to upload to YouTube";
 
try{
    // Client init
    $client = new Google_Client();
    $client->setApplicationName($application_name);
    $client->setClientId($client_id);
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
    $client->setAccessToken($key);
    $client->setScopes($scope);
    $client->setClientSecret($client_secret);
 
    if ($client->getAccessToken()) {		 
        /**
         * Check to see if our access token has expired. If so, get a new one and save it to file for future use.
         */
        if($client->isAccessTokenExpired()) {
        	
            $newToken = json_decode($client->getAccessToken());
            $client->refreshToken($newToken->refresh_token);
            file_put_contents('thekey.txt', $client->getAccessToken());
        }
        // var_dump($newToken);
        $youtube = new Google_Service_YouTube($client);		 
        // Create a snipet with title, description, tags and category id
        $snippet = new Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle($videoTitle);
        $snippet->setDescription($videoDescription);
        // $snippet->setCategoryId($videoCategory);
        $snippet->setTags($videoTags);		 
        // Create a video status with privacy status. Options are "public", "private" and "unlisted".
        $status = new Google_Service_YouTube_VideoStatus();
        $status->setPrivacyStatus('public');		 
        // Create a YouTube video with snippet and status
        $video = new Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);		 
        // Size of each chunk of data in bytes. Setting it higher leads faster upload (less chunks,
        // for reliable connections). Setting it lower leads better recovery (fine-grained chunks)
        $chunkSizeBytes = 2 * 1024 * 1024;		 
        // Setting the defer flag to true tells the client to return a request which can be called
        // with ->execute(); instead of making the API call immediately.
        $client->setDefer(true);		 
        // Create a request for the API's videos.insert method to create and upload the video.
        $insertRequest = $youtube->videos->insert("status,snippet", $video);		 
        // Create a MediaFileUpload object for resumable uploads.
        $media = new Google_Http_MediaFileUpload(
            $client,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
        );
        $videoPath = '../../../uploads/test.mp4';
        $media->setFileSize(filesize($videoPath));
 
 
        // Read the media file and upload it chunk by chunk.
        $status = false;
        $handle = fopen($videoPath, "rb");
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }
 
        fclose($handle);
 
        /**
         * Video has successfully been upload, now lets perform some cleanup functions for this video
         */
        if ($status->status['uploadStatus'] == 'uploaded') {
            // Actions to perform for a successful upload
    		$result = array(
				'r' => 'success',
	 				'videoid' => $status['id']
			);
    		return $result;
    		exit;
        }
 
        // If you want to make other calls after the file upload, set setDefer back to false
        $client->setDefer(true);
 
    } else{
        // @TODO Log error
        $result = array(
			'r' => 'error',
			'html' => 'Problems creating the client'
		);
		return $result;
		exit;
    }
 
} catch(Google_Service_Exception $e) {

        $result = array(
			'r' => 'error',
			'html' => 'Caught Google service Exception '.$e->getCode(). ' message is '.$e->getMessage(). '<br/>Stack trace is '.$e->getTraceAsString()
		);
		return $result;
		exit;
} catch (Exception $e) {

        $result = array(
			'r' => 'error',
			'html' => 'Caught Google service Exception '.$e->getCode(). ' message is '.$e->getMessage(). '<br/>Stack trace is '.$e->getTraceAsString()
		);
		return $result;
		exit;
}