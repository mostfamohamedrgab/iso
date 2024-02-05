<?php
    

use App\Models\Setting;

    function isActiveURLSegment($pageSlug, $segment, $output = "active"){
        if (Request::segment($segment) == $pageSlug) return $output;
    }


    function taskStatus()
    {
        return [
            'not started yet',
            'Working',
            'complated',
            'Completed with assisted',
            'It cannot be terminated',
        ];
    }


    function isDoneTask($status)
    {
        return $status != 'not started yet' && $status != 'Working';
    }

    /*
    |--------------------------------------------------------------------------
    | Detect Active Panel Segment Function
    |--------------------------------------------------------------------------
    |
    | Compare given url segment with current url and return output if they match.
    | Very useful for navigation, marking if the link is active.
    |
    */
    function isActivePanelSegment($pageSlug, $segment, $output = "show"){
        if (Request::segment($segment) == $pageSlug) return $output;
    }
    
   
    // invoices
    // settings

    /*
    |--------------------------------------------------------------------------
    | Detect Active Route Function
    |--------------------------------------------------------------------------
    |
    | Compare given route with current route and return output if they match.
    | Very useful for navigation, marking if the link is active.
    |
    */
    function isActiveRoute($route, $output = "active"){
        if (Route::currentRouteName() == $route) return $output;
    }

    /*
    |--------------------------------------------------------------------------
    | Detect Active Routes Function
    |--------------------------------------------------------------------------
    |
    | Compare given routes with current route and return output if they match.
    | Very useful for navigation, marking if the link is active.
    |
    */
    function areActiveRoutes(Array $routes, $output = "active show"){
        foreach ($routes as $route){
            if (Route::currentRouteName() == $route) return $output;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Format SEO Page Slug Function
    |--------------------------------------------------------------------------
    |
    | Used to format the SEO Page Slug and return the formatted slug for
    | translations.
    |
    */
    function formatSEOPageSlug($pageSlug){
        return __('general.seo_'.str_replace("-", "_", $pageSlug).'_title');
    }


    function lang(){
        return App() -> getLocale();
    }
    
    function generateRandomCode(){
        return '1234';
        return rand(1111,4444);
    }
    
    if (!function_exists('languages')) {
      function languages() {
        return ['ar', 'en'];
      }
    }
    
    if (!function_exists('defaultLang')) {
      function defaultLang() {
        return 'ar';
      }
    }



    function pushNotification($tokens , $data , $platforms)
    {
      
        $url = 'https://fcm.googleapis.com/fcm/send';
        $SERVER_API_KEY = Setting::where('key' , 'firebase_key')->first()->value ;
 
        // $SERVER_API_KEY = 'AAAAi0Y_HnY:APA91bGeuHqUXsXiwWMDlJ-tenEOiKmRZ7pfifFPvI0XUzUiIRD6togg468docAR0gdTpY40Yvr50I8610Fdm9jG3RT-iYakNLthfVcxViBSJ6lIzt5gVh77Y_4VY3oqYyP64Svx6QxR';
         
        // $data = [
        //     "registration_ids" => $tokens,
        //     "notification" => [
        //         'title'                  => $data['title_'.lang()],
        //         'body'                   => $data['body_'.lang()] ,
        //         'data'                   => json_encode(array('type' => $data['type'] ,'order_id' => $data['order_id']??null ,'provider_id' => $data['provider_id'] ??null,'delegate_id' => $data['delegate_id']??null,'status' => $data['status']??null)),
        //         'sound'                  => 'default', 
        //     ]
        // ];

        foreach($platforms as $device_type){
            if($device_type == 'ios'){
                $Notify_data = [
                    "registration_ids" => $tokens,
                    "notification" => [
                        "title"    => $data['title_'.lang()],
                        "body"     =>  $data['body_'.lang()]  ,
                        "mutable_content" => true,
                        'sound'    => true,
                    ],
                    'data'  => $data
                ];
            }else{
                $Notify_data = [
                    "registration_ids" => $tokens,
                    'data'  => $data
                ];
            }
        }
        $dataString = json_encode($Notify_data);
    
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
    
        return response()->json();
    
    }
    

    
?>
