<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/dashboard' => [[['_route' => 'dashboard', '_controller' => 'App\\Controller\\DashboardController::index'], null, null, null, false, false, null]],
        '/dashboard/bill' => [[['_route' => 'bill', '_controller' => 'App\\Controller\\Warehouse\\BillController::listBills'], null, null, null, false, false, null]],
        '/dashboard/bill/paid_bills' => [[['_route' => 'filterPaidBills', '_controller' => 'App\\Controller\\Warehouse\\BillController::filterPaidBills'], null, null, null, false, false, null]],
        '/dashboard/bill/unpaid_bills' => [[['_route' => 'filterUnpaidBills', '_controller' => 'App\\Controller\\Warehouse\\BillController::filterUnpaidBills'], null, null, null, false, false, null]],
        '/dashboard/bill/search' => [[['_route' => 'searchBill', '_controller' => 'App\\Controller\\Warehouse\\BillController::searchBill'], null, ['POST' => 0], null, false, false, null]],
        '/dashboard/bill/sort_date_asc' => [[['_route' => 'sortBillsByDateASC', '_controller' => 'App\\Controller\\Warehouse\\BillController::sortBillsByDateASC'], null, null, null, false, false, null]],
        '/dashboard/bill/sort_date_desc' => [[['_route' => 'sortBillsByDateDESC', '_controller' => 'App\\Controller\\Warehouse\\BillController::sortBillsByDateDESC'], null, null, null, false, false, null]],
        '/dashboard/bill/sort_amount_asc' => [[['_route' => 'sortBillsByAmountASC', '_controller' => 'App\\Controller\\Warehouse\\BillController::sortBillsByAmountASC'], null, null, null, false, false, null]],
        '/dashboard/bill/sort_amount_desc' => [[['_route' => 'sortBillsByAmountDESC', '_controller' => 'App\\Controller\\Warehouse\\BillController::sortBillsByAmountDESC'], null, null, null, false, false, null]],
        '/dashboard/car' => [[['_route' => 'car', '_controller' => 'App\\Controller\\Warehouse\\CarController::listCars'], null, null, null, false, false, null]],
        '/dashboard/car/available' => [[['_route' => 'availableCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::availableCars'], null, null, null, false, false, null]],
        '/dashboard/car/not_available' => [[['_route' => 'notAvailableCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::notAvailableCars'], null, null, null, false, false, null]],
        '/dashboard/car/rented' => [[['_route' => 'rentedCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::rentedCars'], null, null, null, false, false, null]],
        '/dashboard/car/sold' => [[['_route' => 'soldCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::soldCars'], null, null, null, false, false, null]],
        '/dashboard/car/new' => [[['_route' => 'newCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::newCars'], null, null, null, false, false, null]],
        '/dashboard/car/used' => [[['_route' => 'usedCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::usedCars'], null, null, null, false, false, null]],
        '/dashboard/car/under_repair' => [[['_route' => 'underRepairCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::underRepairCars'], null, null, null, false, false, null]],
        '/dashboard/car/sort_price_desc' => [[['_route' => 'sortCarsByPriceDESC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByPriceDESC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_kilometrage_asc' => [[['_route' => 'sortCarsByKilometrageASC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByKilometrageASC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_kilometrage_desc' => [[['_route' => 'sortCarsByKilometrageDESC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByKilometrageDESC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_brand_model_asc' => [[['_route' => 'sortCarsByBrandModelASC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByBrandModelASC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_brand_model_desc' => [[['_route' => 'sortCarsByBrandModelDESC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByBrandModelDESC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_year_asc' => [[['_route' => 'sortCarsByYearASC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByYearASC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_year_desc' => [[['_route' => 'sortCarsByYearDESC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByYearDESC'], null, null, null, false, false, null]],
        '/dashboard/car/search' => [[['_route' => 'searchCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::searchCar'], null, ['POST' => 0], null, false, false, null]],
        '/Front' => [[['_route' => 'Front', '_controller' => 'App\\Controller\\Warehouse\\CarController::index'], null, null, null, false, false, null]],
        '/dashboard/warehouse' => [[['_route' => 'warehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::listWarehouses'], null, null, null, false, false, null]],
        '/dashboard/warehouse/capacity_asc' => [[['_route' => 'sortByCapacityASC', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::sortByCapacityASC'], null, null, null, false, false, null]],
        '/dashboard/warehouse/capacity_desc' => [[['_route' => 'sortByCapacityDESC', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::sortByCapacityDESC'], null, null, null, false, false, null]],
        '/dashboard/warehouse/city_asc' => [[['_route' => 'sortByCityASC', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::sortByCityASC'], null, null, null, false, false, null]],
        '/dashboard/warehouse/city_desc' => [[['_route' => 'sortByCityDESC', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::sortByCityDESC'], null, null, null, false, false, null]],
        '/dashboard/warehouse/storage' => [[['_route' => 'storageWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::storageWarehouse'], null, null, null, false, false, null]],
        '/dashboard/warehouse/repair' => [[['_route' => 'repairWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::repairWarehouse'], null, null, null, false, false, null]],
        '/dashboard/warehouse/search' => [[['_route' => 'searchWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::searchWarehouse'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:98)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:134)'
                                .'|router(*:148)'
                                .'|exception(?'
                                    .'|(*:168)'
                                    .'|\\.css(*:181)'
                                .')'
                            .')'
                            .'|(*:191)'
                        .')'
                    .')'
                .')'
                .'|/dashboard/(?'
                    .'|bill/(?'
                        .'|delete([^/]++)(*:238)'
                        .'|update([^/]++)(*:260)'
                    .')'
                    .'|car/(?'
                        .'|delete([^/]++)(*:290)'
                        .'|update([^/]++)(*:312)'
                    .')'
                    .'|warehouse/(?'
                        .'|delete([^/]++)(*:348)'
                        .'|update([^/]++)(*:370)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        98 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        134 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        148 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        168 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        181 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        191 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        238 => [[['_route' => 'deleteBill', '_controller' => 'App\\Controller\\Warehouse\\BillController::deleteBill'], ['id'], null, null, false, true, null]],
        260 => [[['_route' => 'updateBill', '_controller' => 'App\\Controller\\Warehouse\\BillController::updateBill'], ['id'], null, null, false, true, null]],
        290 => [[['_route' => 'deleteCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::deleteCar'], ['id'], null, null, false, true, null]],
        312 => [[['_route' => 'updateCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::updateCar'], ['id'], null, null, false, true, null]],
        348 => [[['_route' => 'deleteWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::deleteWarehouse'], ['id'], null, null, false, true, null]],
        370 => [
            [['_route' => 'updateWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::updateWarehouse'], ['id'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
