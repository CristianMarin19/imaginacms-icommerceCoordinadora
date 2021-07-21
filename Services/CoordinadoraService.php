<?php

namespace Modules\Icommercecoordinadora\Services;

use Modules\Icommerce\Repositories\ShippingMethodRepository;



class CoordinadoraService
{

	private $shippingMethod;
	
	/*
    *
    */
	public function __construct(
       ShippingMethodRepository $shippingMethod
    ){
        $this->shippingMethod = $shippingMethod;
    }

    /*
    *
    */
	public function getShippingMethodConfiguration(){

		 
        $shippingName = config('asgard.icommercecoordinadora.config.shippingName');
        $attribute = array('name' => $shippingName);
        $shippingMethod = $this->shippingMethod->findByAttributes($attribute);

        return $shippingMethod;

	}

    /**
     * 
     * @param Request Array  products(items,total)
     * @return array
    */
    public function getInforCotizar($products,$methodConfig){

        //dd($methodConfig);


        $inforCotizar = [
                'origen' => 'VE', //Codigo dane de la ciudad origen "13001000"
                'destino' => 'VE', //Codigo dane de la ciudad destino '25175000
                'valoracion' => $products['total'], // Valor declarado del envio
                'detalle' => $this->getDetalleEmpaques($products),
                'apikey' => 'xxx', //Api key provisto por Coordinadora
                'clave' => 'xxx' // clave
        ];

        return $inforCotizar;

    }

    /**
    *
    *
    */
    public function getDetalleEmpaques($products){

        $detalleEmpaques = [];

        $items = json_decode($products['items']);

        //dd($items);

        foreach ($items as $key => $item) {

            array_push($detalleEmpaques,[
                'alto' => $item->height,
                'ancho' => $item->width,
                'largo' => $item->length ?? 0,
                'peso' => $item->weight, 
                'unidades' => $item->quantity
            ]);
           
        }

        dd($detalleEmpaques);
        
        /*
        $detalleEmpaques[0] = [
                'alto' => 1,
                'ancho' => 1,
                'largo' => 1,
                'peso' => 1,
                'unidades' => 1
        ];
        */

        return $detalleEmpaques;

    }


}