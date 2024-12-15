<?php

namespace App\ApiResource\Order;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Common\ArrayDTO;
use App\ApiResource\Common\EmptyDTO;
use App\ApiResource\Common\IdDTO;
use App\State\Order\OrderItemStateProcessor;
use App\State\Order\OrderMenuItemStateProcessor;
use App\State\Order\OrderStateProcessor;
use App\State\Order\SetStatusStateProcessor;
use App\State\RoleSignUpStateProcessor;


#[ApiResource(
    operations: [
        new Post(
            uriTemplate: 'order',
            input: OrderDTO::class,
            output: IdDTO::class,
            processor: OrderStateProcessor::class,
        ),
        new Post(
            uriTemplate: 'order_item',
            input: OrderItemDTO::class,
            output: IdDTO::class,
            processor: OrderItemStateProcessor::class,
        ),
        new Post(

            uriTemplate: 'order_menu_item',
            /*
             * @var OrderMenuItemDTO[]
             */
            input: ArrayDTO::class,
            processor: OrderMenuItemStateProcessor::class,
        ),
        new Patch(
            uriTemplate: 'order_item',
            input: SetStatusDTO::class,
            processor: SetStatusStateProcessor::class,
        )
    ]
)]
class OrderResource
{

}