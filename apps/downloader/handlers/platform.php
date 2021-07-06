<?php

class Platform{
    private $validSites = ['4anime', 'mxtakatak', 'twitter', '9gag', 'bandcamp', 'bilibili', 'bitchute', 'douyin', 'facebook', 'tiktok', 'espn', 'flickr', 'likee','like', 'youtube', 'youtu', 'febspot', 'instagram','flic'];
    public function findPlatform($requestedSite){
        foreach($this->validSites as $validSite){
            if(strpos($requestedSite, $validSite)!==false){
                return json_encode(array("status"=>"success","site"=>$validSite));
            }
        }
        return json_encode(array("status"=>"failure","site"=>"","downloadurl"=>""));
    }
    public function download($result){
        $post = [
            'site' => $result['site'],
            'downloadurl' => $result['downloadurl'],
        ];
        $ch = curl_init('https://api.allvideodownload.ga/api/index.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

