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
        $lang['please_wait'] = __('Please Wait!');
        $lang['something_wrong'] = __('Some thing is wrong.');
        $lang['enter_required_fields'] = __("Please enter the required fields.");
        switch($page){
            case "category":
                $lang['something_wrong'] = __('Some thing is wrong.');
                break;
        }

        return $lang;
    }
}
