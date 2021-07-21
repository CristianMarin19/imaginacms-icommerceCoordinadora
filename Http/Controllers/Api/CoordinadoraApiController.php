<?php

namespace Modules\Icommercecoordinadora\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base Api
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;

// Repositories
use Modules\Icommerce\Repositories\ShippingMethodRepository;

// Services
use Modules\Icommercecoordinadora\Services\CoordinadoraService;

class CoordinadoraApiController extends BaseApiController
{

    const SANDBOX_URL = "http://sandbox.coordinadora.com/ags/1.5/server.php?wsdl";
    const PRODUCTION_URL = "http://sandbox.coordinadora.com/ags/1.5/server.php?wsdl";

    private $shippingMethod;
    private $coordinadoraService;

    private $clientSoap;
    private $methodConfiguration;
    private $cities;
   
    public function __construct(
        ShippingMethodRepository $shippingMethod,
        CoordinadoraService $coordinadoraService
    ){

        $this->shippingMethod = $shippingMethod;
        $this->coordinadoraService = $coordinadoraService;

        $this->methodConfiguration = $this->coordinadoraService->getShippingMethodConfiguration();

        $this->clientSoap = $this->initClientSoap();

        $this->cities = $this->getCities();

    }

    /**
    * Init Client
    * @param 
    * @return client
    */
    public function initClientSoap(){

        // Params
        $opts = array(
            'ssl' => array(
                'verify_peer'=>false, 
                'verify_peer_name'=>false,
                'allow_self_signed' => true
            )
        );

        $params = array (
            'encoding' => 'UTF-8',
            'soap_version' => SOAP_1_2, 
            'trace' => true, 
            'exceptions' => true, 
            "connection_timeout" => 180, 
            'stream_context' => stream_context_create($opts) 
        );

        // URL
        $url = self::SANDBOX_URL;
        if($this->methodConfiguration->options->mode=="production")
             $url = self::PRODUCTION_URL;

        // Create Client
        try{
            
            $client = new \SoapClient($url,$params);
            return $client;

        }catch (\Exception $e){

            \Log::error('Module IcommerceCoordinadora: Init Client - Message: '.$e->getMessage());
            \Log::error('Module IcommerceCoordinadora: Init Client - Code: '.$e->getCode());

            //throw new  \Exception($e->getMessage());
        }

    }
    
    /**
    * @param Request Array  products  (items,total)
    * @param Request String shipping_country 
    * @param Request String shipping_country_code
    * @return response Cotizador
    */
    public function cotizacion(Request $request){

        try {

            //dd($this->clientSoap->__getTypes());
            //dd($request);
            
            $inforCotizar = $this->coordinadoraService->getInforCotizar($request->products,$this->methodConfiguration);

            dd($inforCotizar);

            //dd($this->clientSoap->Cotizador_cotizar($inforCotizar));
  
            //dd($this->cities);
            //dd($this->searchCity("aksdjsk"));
            

           //SoapFault $fault
          } catch (\Exception $e) {

            dd($e);
            //Message Error
            $status = 500;
            $response = [
              'errors' => $e->getMessage()
            ];
        }

        return response()->json($response, $status ?? 200);

    }

    /**
    * @param 
    * @return response Array of Json City
    */
    public function getCities(){

        $items = null;
        $result = $this->clientSoap->Cotizador_ciudades();

        if(!empty($result->Cotizador_ciudadesResult))
            $items = $result->Cotizador_ciudadesResult->item;
            
        return $items;
        
    }

    /**
    * @param TESTING
    * @return
    */
    public function searchCity($data){

        $city = null;
        foreach ($this->cities as $key => $city) {
            // OJOOOOO ESTO CAMBIAR
            if($city->nombre_departamento=="Antioquia"){
                return $city;
            }    
        }

        return $city; 

    }
    

    

}