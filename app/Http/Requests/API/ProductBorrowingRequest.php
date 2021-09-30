<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Response;
use App\Jobs\MovementProcess;
use App\Exceptions\GeneralException;

use App\Models\ProductBorrowingDetail;
use App\Models\StockWarehouse;
use PhpParser\Node\Stmt\Switch_;

class ProductBorrowingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'products' => 'required|present',
            'status'   => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'products' => 'Products',
            'status'   => 'Status'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required.',
            'min'      => ':attribute at least :min.'
        ];
    }   

    public function checkStock()
    {
        foreach (json_decode($this->products) as $key => $product) {            
            $name         = $product->product;
            $product_id   = $product->product_id;
            $warehouse_id = $product->warehouse_id;
            $qty_request  = $product->qty_requested;

            $warehouse = StockWarehouse::where([
                ['warehouse_id','=', $warehouse_id],
                ['product_id','=', $product_id]
            ])->first();
    
            if(!$warehouse){
                $warehouse = StockWarehouse::create([
                    'product_id'    => $product_id,
                    'warehouse_id'  => $warehouse_id
                ]);
            }
            $stock     = $warehouse->stock;
            
            if($stock < $qty_request){
                switch ($stock) {
                    case 0:
                        $message = "$name out of stock.";
                        break;
                    
                    default:
                        $message = "Available $name stock is $stock. Qty requested must be less than or equal to $stock.";
                        break;
                }
                throw new GeneralException($message,Response::HTTP_UNPROCESSABLE_ENTITY);
                
            }

        }
    }
}
