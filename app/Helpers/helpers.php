<?php

if (!function_exists('jsonResponse')) {
    /**
     * Generate a standardized JSON error response.
     *
     * @param array $request
     * @return \Illuminate\Http\JsonResponse
     */
    function jsonResponse(array $request)
    {
        $success = isset($request['success']) ? $request['success'] : false;
        $message = isset($request['message']) ? $request['message'] : __('Default message.');;
        $errors = isset($request['errors']) ? $request['errors'] : [];
        $statusCode = isset($request['statusCode']) ? $request['statusCode'] : 409;
        
        return response()->json([
            'success' => $success,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}

if (!function_exists('getJSLang')) {
    /**
     * Generate a standardized JSON error response.
     *
     * @param string $request
     * @return \Illuminate\Http\JsonResponse
     */
    function getJSLang($page = '')
    {
        $lang = array();

        /*Configs */
        $lang['default_color_code'] = '#ffffff';

        /**Labels */
        $lang['please_wait'] = __('Please Wait!');
        $lang['something_wrong'] = __('Some thing is wrong.');
        $lang['enter_required_fields'] = __("Please enter the required fields.");

        $lang['auction_url'] = route('auction.index');
        //$lang['set_league_id_url'] = route('set.league.id');

        $lang['view'] = __("View");
        $lang['edit'] = __("Edit");
        $lang['delete'] = __("Delete");
        $lang['trash'] = __("Trash");
        $lang['auction'] = __("Auction");
        $lang['booster'] = __("Booster");        
        $lang['not_found'] = __('Item not found.');
        $lang['remove'] = __("Remove");

        switch($page){
            case "category":
                $lang['not_found'] = __('Category not found.');
                break;
            case "player":
                $lang['not_found'] = __('Player not found.');
                break;
            case "league":
                $lang['not_found'] = __('League not found.');
                break;
            case "sponsor":
                $lang['not_found'] = __('Sponsor not found.');
                break;
        }

        return $lang;
    }
}

if(!function_exists('print_rd')){
    function print_rd($ar){
        print("<pre>");
        print_r($ar);
        print("</pre>");
        die;
    }    
}