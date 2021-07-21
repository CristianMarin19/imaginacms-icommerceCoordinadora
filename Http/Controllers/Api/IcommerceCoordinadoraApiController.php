<?php

namespace Modules\Icommercecoordinadora\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base Api
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;

use Modules\Icommercecoordinadora\Http\Controllers\Api\CoordinadoraApiController;

// Repositories
use Modules\Icommercecoordinadora\Repositories\IcommerceCoordinadoraRepository;
use Modules\Icommerce\Repositories\ShippingMethodRepository;

class IcommerceCoordinadoraApiController extends BaseApiController
{

    private $coordinadoraApi;
   
    public function __construct(
       CoordinadoraApiController $coordinadoraApi
    ){
        $this->coordinadoraApi = $coordinadoraApi;
    }
    
     /**
     * Init data
     * @param Requests array products - items (object)
     * @param Requests array products - total
     * @param Requests Opcionales (countryCode, country, postalCode)
     * @return response
     * String status = success
     * JSON items - (Optional - Default null) - (Each item: name and price)
     * Float price
     * Boolean priceShow
     */
    public function init(Request $request){

        try {

            //$cotizacion = $this->coordinadoraApi->cotizacion($request);
            

            //$response = $this->icommercecoordinadora->calculate($request->all(),$shippingMethod->options);
            
            $response["status"] = "success";
            // Items
            $response["items"] = null;
            // Price
            $response["price"] = 10000;
            $response["priceshow"] = true;
            

          } catch (\Exception $e) {
            //Message Error
            $status = 500;
            $response = [
              'errors' => $e->getMessage()
            ];
        }

        return response()->json($response, $status ?? 200);

    }
    
    

}