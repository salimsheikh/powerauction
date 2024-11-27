<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    function get_columns()
    {
        // Define column names (localized)
        $columns = [];
        //$columns['id'] = __('ID');
        $columns['sr'] = __('Sr.');
        $columns['category_name'] = __('Category Name');
        $columns['base_price'] = __('Base Price');
        $columns['color_code'] = __('Color Code');
        $columns['description'] = __('Description');
        $columns['actions'] = __('Actions');

        return $columns;
    }

    public function index(Request $request)
    {
        
    }
}
