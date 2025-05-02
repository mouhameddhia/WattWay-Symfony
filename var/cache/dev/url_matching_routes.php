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
        '/api/sentiment/analyze' => [[['_route' => 'api_sentiment_analyze', '_controller' => 'App\\Controller\\Api\\SentimentAnalysisController::analyze'], null, ['POST' => 0], null, false, false, null]],
        '/dashboard' => [[['_route' => 'dashboard', '_controller' => 'App\\Controller\\DashboardController::index'], null, null, null, false, false, null]],
        '/Front' => [[['_route' => 'Front', '_controller' => 'App\\Controller\\FrontController::index'], null, null, null, false, false, null]],
        '/checkout' => [[['_route' => 'checkout', '_controller' => 'App\\Controller\\FrontController::checkout'], null, ['POST' => 0], null, false, false, null]],
        '/update-cart' => [[['_route' => 'update_cart', '_controller' => 'App\\Controller\\FrontController::updateCart'], null, ['POST' => 0], null, false, false, null]],
        '/items/create' => [[['_route' => 'create_item', '_controller' => 'App\\Controller\\Order\\ItemsController::create'], null, null, null, false, false, null]],
        '/ai/suggest-price' => [[['_route' => 'ai_suggest_price', '_controller' => 'App\\Controller\\Order\\ItemsController::suggestPrice'], null, ['POST' => 0], null, false, false, null]],
        '/items/check-name' => [[['_route' => 'check_item_name', '_controller' => 'App\\Controller\\Order\\ItemsController::checkItemName'], null, ['POST' => 0], null, false, false, null]],
        '/ai/is-car-related' => [[['_route' => 'ai_is_car_related', '_controller' => 'App\\Controller\\Order\\ItemsController::checkCarRelevance'], null, ['POST' => 0], null, false, false, null]],
        '/api/validate-image' => [[['_route' => 'api_validate_image', '_controller' => 'App\\Controller\\Order\\ItemsController::validateImage'], null, ['POST' => 0], null, false, false, null]],
        '/orders' => [
            [['_route' => 'orders', '_controller' => 'App\\Controller\\Order\\OrdersController::orders'], null, null, null, false, false, null],
            [['_route' => 'orders_index', '_controller' => 'App\\Controller\\Order\\OrdersController::index'], null, null, null, false, false, null],
        ],
        '/items' => [[['_route' => 'items', '_controller' => 'App\\Controller\\Order\\OrdersController::items'], null, null, null, false, false, null]],
        '/order/createOrder' => [[['_route' => 'order_create', '_controller' => 'App\\Controller\\Order\\OrdersController::createOrder'], null, null, null, false, false, null]],
        '/supplier-map' => [[['_route' => 'supplier_map', '_controller' => 'App\\Controller\\Order\\OrdersController::supplierMap'], null, null, null, false, false, null]],
        '/reset-password' => [[['_route' => 'app_forgot_password_request', '_controller' => 'App\\Controller\\ResetPasswordController::request'], null, null, null, false, false, null]],
        '/reset-password/check-email' => [[['_route' => 'app_check_email', '_controller' => 'App\\Controller\\ResetPasswordController::checkEmail'], null, null, null, false, false, null]],
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
        '/dashboard/car/sort_price_asc' => [[['_route' => 'sortCarsByPriceASC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByPriceASC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_price_desc' => [[['_route' => 'sortCarsByPriceDESC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByPriceDESC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_kilometrage_asc' => [[['_route' => 'sortCarsByKilometrageASC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByKilometrageASC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_kilometrage_desc' => [[['_route' => 'sortCarsByKilometrageDESC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByKilometrageDESC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_brand_model_asc' => [[['_route' => 'sortCarsByBrandModelASC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByBrandModelASC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_brand_model_desc' => [[['_route' => 'sortCarsByBrandModelDESC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByBrandModelDESC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_year_asc' => [[['_route' => 'sortCarsByYearASC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByYearASC'], null, null, null, false, false, null]],
        '/dashboard/car/sort_year_desc' => [[['_route' => 'sortCarsByYearDESC', '_controller' => 'App\\Controller\\Warehouse\\CarController::sortCarsByYearDESC'], null, null, null, false, false, null]],
        '/dashboard/car/search' => [[['_route' => 'searchCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::searchCar'], null, ['POST' => 0], null, false, false, null]],
        '/notify/new-car' => [[['_route' => 'notify_new_car', '_controller' => 'App\\Controller\\Warehouse\\CarController::notifyNewCar'], null, ['POST' => 0], null, false, false, null]],
        '/dashboard/warehouse' => [[['_route' => 'warehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::listWarehouses'], null, null, null, false, false, null]],
        '/dashboard/warehouse/capacity_asc' => [[['_route' => 'sortByCapacityASC', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::sortByCapacityASC'], null, null, null, false, false, null]],
        '/dashboard/warehouse/capacity_desc' => [[['_route' => 'sortByCapacityDESC', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::sortByCapacityDESC'], null, null, null, false, false, null]],
        '/dashboard/warehouse/city_asc' => [[['_route' => 'sortByCityASC', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::sortByCityASC'], null, null, null, false, false, null]],
        '/dashboard/warehouse/city_desc' => [[['_route' => 'sortByCityDESC', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::sortByCityDESC'], null, null, null, false, false, null]],
        '/dashboard/warehouse/storage' => [[['_route' => 'storageWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::storageWarehouse'], null, null, null, false, false, null]],
        '/dashboard/warehouse/repair' => [[['_route' => 'repairWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::repairWarehouse'], null, null, null, false, false, null]],
        '/dashboard/warehouse/search' => [[['_route' => 'searchWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::searchWarehouse'], null, ['POST' => 0], null, false, false, null]],
        '/get-city-from-coordinates' => [[['_route' => 'get_city_from_coordinates', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::getCityFromCoordinates'], null, ['POST' => 0], null, false, false, null]],
        '/submission/create' => [[['_route' => 'Front_Submission_create', '_controller' => 'App\\Controller\\submission\\FrontSubmissionController::create'], null, ['POST' => 0], null, false, false, null]],
        '/dashboard/response/filter' => [[['_route' => 'app_response_filter', '_controller' => 'App\\Controller\\submission\\ResponseController::filter'], null, ['GET' => 0], null, false, false, null]],
        '/dashboard/response' => [[['_route' => 'app_response_index', '_controller' => 'App\\Controller\\submission\\ResponseController::index'], null, ['GET' => 0], null, false, false, null]],
        '/dashboard/response/new' => [[['_route' => 'app_response_new', '_controller' => 'App\\Controller\\submission\\ResponseController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/api/analyze' => [[['_route' => 'analyze_sentiment', '_controller' => 'App\\Controller\\submission\\SentimentController::analyze'], null, ['POST' => 0], null, false, false, null]],
        '/dashboard/submission' => [[['_route' => 'app_submission_index', '_controller' => 'App\\Controller\\submission\\SubmissionController::index'], null, ['GET' => 0], null, true, false, null]],
        '/dashboard/submission/new' => [[['_route' => 'app_submission_new', '_controller' => 'App\\Controller\\submission\\SubmissionController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/dashboard/submission/dashboard/submission/kanban' => [[['_route' => 'app_submission_kanban', '_controller' => 'App\\Controller\\submission\\SubmissionController::kanban'], null, ['GET' => 0], null, false, false, null]],
        '/connect/google' => [[['_route' => 'connect_google_start', '_controller' => 'App\\Controller\\user\\ConnectGoogleController::connectAction'], null, null, null, false, false, null]],
        '/connect/google/check' => [[['_route' => 'connect_google_check', '_controller' => 'App\\Controller\\user\\ConnectGoogleController::connectCheckAction'], null, null, null, false, false, null]],
        '/account/delete' => [[['_route' => 'app_delete_account', '_controller' => 'App\\Controller\\user\\DeleteController::deleteAccount'], null, ['DELETE' => 0], null, false, false, null]],
        '/submit-feedback' => [[['_route' => 'submit_feedback', '_controller' => 'App\\Controller\\user\\FeedbackController::submitFeedback'], null, ['POST' => 0], null, false, false, null]],
        '/feedback/list' => [[['_route' => 'feedback_list', '_controller' => 'App\\Controller\\user\\FeedbackManagementController::list'], null, null, null, false, false, null]],
        '/feedback/dashboard' => [[['_route' => 'feedback_dashboard', '_controller' => 'App\\Controller\\user\\FeedbackManagementController::dashboard'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\user\\LoginController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\user\\LoginController::logout'], null, null, null, false, false, null]],
        '/api/get-face-descriptor' => [[['_route' => 'api_get_face_descriptor', '_controller' => 'App\\Controller\\user\\LoginController::getFaceDescriptor'], null, ['GET' => 0], null, false, false, null]],
        '/api/face-login' => [[['_route' => 'api_face_login', '_controller' => 'App\\Controller\\user\\LoginController::faceLogin'], null, ['POST' => 0], null, false, false, null]],
        '/profile/edit' => [[['_route' => 'app_profile_edit', '_controller' => 'App\\Controller\\user\\ProfileController::edit'], null, null, null, false, false, null]],
        '/save-face-descriptor' => [[['_route' => 'app_save_face_descriptor', '_controller' => 'App\\Controller\\user\\ProfileController::saveFaceDescriptor'], null, ['POST' => 0], null, false, false, null]],
        '/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\user\\UserController::register'], null, null, null, false, false, null]],
        '/create-admin-now' => [[['_route' => 'create_admin_now', '_controller' => 'App\\Controller\\user\\UserController::createAdminNow'], null, null, null, false, false, null]],
        '/generate-password' => [[['_route' => 'generate_password', '_controller' => 'App\\Controller\\user\\UserController::generatePassword'], null, null, null, false, false, null]],
        '/list' => [[['_route' => 'user_list', '_controller' => 'App\\Controller\\user\\UserManagementController::list'], null, null, null, false, false, null]],
        '/dashboard/submission/submission/create' => [[['_route' => 'app_submission_Front_Submission_create', '_controller' => 'App\\Controller\\submission\\FrontSubmissionController::create'], null, ['POST' => 0], null, false, false, null]],
        '/dashboard/submission/dashboard/response/filter' => [[['_route' => 'app_submission_app_response_filter', '_controller' => 'App\\Controller\\submission\\ResponseController::filter'], null, ['GET' => 0], null, false, false, null]],
        '/dashboard/submission/dashboard/response' => [[['_route' => 'app_submission_app_response_index', '_controller' => 'App\\Controller\\submission\\ResponseController::index'], null, ['GET' => 0], null, false, false, null]],
        '/dashboard/submission/dashboard/response/new' => [[['_route' => 'app_submission_app_response_new', '_controller' => 'App\\Controller\\submission\\ResponseController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/dashboard/submission/api/analyze' => [[['_route' => 'app_submission_analyze_sentiment', '_controller' => 'App\\Controller\\submission\\SentimentController::analyze'], null, ['POST' => 0], null, false, false, null]],
        '/dashboard/submission/dashboard/submission' => [[['_route' => 'app_submission_app_submission_index', '_controller' => 'App\\Controller\\submission\\SubmissionController::index'], null, ['GET' => 0], null, true, false, null]],
        '/dashboard/submission/dashboard/submission/new' => [[['_route' => 'app_submission_app_submission_new', '_controller' => 'App\\Controller\\submission\\SubmissionController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/dashboard/submission/dashboard/submission/dashboard/submission/kanban' => [[['_route' => 'app_submission_app_submission_kanban', '_controller' => 'App\\Controller\\submission\\SubmissionController::kanban'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/a(?'
                    .'|pi(?'
                        .'|/(?'
                            .'|docs(?:\\.([^/]++))?(*:40)'
                            .'|\\.well\\-known/genid/([^/]++)(*:75)'
                            .'|validation_errors/([^/]++)(*:108)'
                        .')'
                        .'|(?:/(index)(?:\\.([^/]++))?)?(*:145)'
                        .'|/(?'
                            .'|contexts/([^.]+)(?:\\.(jsonld))?(*:188)'
                            .'|errors/(\\d+)(?:\\.([^/]++))?(*:223)'
                            .'|validation_errors/([^/]++)(?'
                                .'|(*:260)'
                            .')'
                            .'|\\.well\\-known/genid/([^/\\.]++)(?:\\.([^/]++))?(*:314)'
                            .'|submission/([^/]++)/(?'
                                .'|predict\\-priority(*:362)'
                                .'|update\\-priority(*:386)'
                            .')'
                            .'|item/([^/]++)/quantity(*:417)'
                        .')'
                    .')'
                    .'|vatar/default/([^/]++)/([^/]++)(*:458)'
                .')'
                .'|/js/routing(?:\\.(js|json))?(*:494)'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:533)'
                    .'|wdt/([^/]++)(*:553)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:595)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:632)'
                                .'|router(*:646)'
                                .'|exception(?'
                                    .'|(*:666)'
                                    .'|\\.css(*:679)'
                                .')'
                            .')'
                            .'|(*:689)'
                        .')'
                    .')'
                .')'
                .'|/media/cache/resolve/(?'
                    .'|([A-z0-9_-]*)/rc/([^/]++)/(.+)(*:754)'
                    .'|([A-z0-9_-]*)/(.+)(*:780)'
                .')'
                .'|/item(?'
                    .'|/([^/]++)/edit(*:811)'
                    .'|s/delete/([^/]++)(*:836)'
                .')'
                .'|/order(?'
                    .'|/(?'
                        .'|item(?'
                            .'|s/([^/]++)(*:875)'
                            .'|/(?'
                                .'|update/([^/]++)(*:902)'
                                .'|delete/([^/]++)(*:925)'
                            .')'
                        .')'
                        .'|delete/([^/]++)(*:950)'
                        .'|update/([^/]++)(*:973)'
                    .')'
                    .'|s/by\\-month/([^/]++)(*:1002)'
                .')'
                .'|/reset\\-password/reset(?:/([^/]++))?(*:1048)'
                .'|/d(?'
                    .'|ashboard/(?'
                        .'|bill/(?'
                            .'|delete([^/]++)(*:1096)'
                            .'|update([^/]++)(*:1119)'
                        .')'
                        .'|car/(?'
                            .'|delete([^/]++)(*:1150)'
                            .'|update([^/]++)(*:1173)'
                        .')'
                        .'|warehouse/(?'
                            .'|delete([^/]++)(*:1210)'
                            .'|update([^/]++)(*:1233)'
                        .')'
                        .'|response/([^/]++)(?'
                            .'|(*:1263)'
                            .'|/edit(*:1277)'
                            .'|(*:1286)'
                        .')'
                        .'|submission/(?'
                            .'|([^/]++)(?'
                                .'|(*:1321)'
                                .'|/(?'
                                    .'|e(?'
                                        .'|dit(*:1341)'
                                        .'|xtract\\-keyterms(*:1366)'
                                    .')'
                                    .'|status(*:1382)'
                                    .'|predict\\-priority(*:1408)'
                                    .'|update\\-priority(*:1433)'
                                .')'
                                .'|(*:1443)'
                            .')'
                            .'|submission/(?'
                                .'|delete/([^/]++)(*:1482)'
                                .'|edit/([^/]++)(*:1504)'
                            .')'
                            .'|dashboard/(?'
                                .'|response/([^/]++)(?'
                                    .'|(*:1547)'
                                    .'|/edit(*:1561)'
                                    .'|(*:1570)'
                                .')'
                                .'|submission/([^/]++)(?'
                                    .'|(*:1602)'
                                    .'|/(?'
                                        .'|e(?'
                                            .'|dit(*:1622)'
                                            .'|xtract\\-keyterms(*:1647)'
                                        .')'
                                        .'|status(*:1663)'
                                        .'|predict\\-priority(*:1689)'
                                        .'|update\\-priority(*:1714)'
                                    .')'
                                    .'|(*:1724)'
                                .')'
                            .')'
                        .')'
                    .')'
                    .'|elete(?'
                        .'|Feedback/([^/]++)(*:1762)'
                        .'|/([^/]++)(*:1780)'
                    .')'
                .')'
                .'|/submission/(?'
                    .'|delete/([^/]++)(*:1821)'
                    .'|edit/([^/]++)(*:1843)'
                .')'
                .'|/feedback/delete/([^/]++)(*:1878)'
                .'|/ban/([^/]++)(*:1900)'
                .'|/unban/([^/]++)(*:1924)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        40 => [[['_route' => 'api_doc', '_controller' => 'api_platform.action.documentation', '_format' => '', '_api_respond' => 'true'], ['_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        75 => [[['_route' => 'api_genid', '_controller' => 'api_platform.action.not_exposed', '_api_respond' => 'true'], ['id'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        108 => [[['_route' => 'api_validation_errors', '_controller' => 'api_platform.action.not_exposed'], ['id'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        145 => [[['_route' => 'api_entrypoint', '_controller' => 'api_platform.action.entrypoint', '_format' => '', '_api_respond' => 'true', 'index' => 'index'], ['index', '_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        188 => [[['_route' => 'api_jsonld_context', '_controller' => 'api_platform.jsonld.action.context', '_format' => 'jsonld', '_api_respond' => 'true'], ['shortName', '_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        223 => [[['_route' => '_api_errors', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => true, '_api_resource_class' => 'ApiPlatform\\State\\ApiResource\\Error', '_api_operation_name' => '_api_errors'], ['status', '_format'], ['GET' => 0], null, false, true, null]],
        260 => [
            [['_route' => '_api_validation_errors_problem', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_problem'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_hydra', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_hydra'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_jsonapi', '_controller' => 'api_platform.symfony.main_controller', '_format' => null, '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_jsonapi'], ['id'], ['GET' => 0], null, false, true, null],
        ],
        314 => [[['_route' => '_api_/.well-known/genid/{id}{._format}_get', '_controller' => 'api_platform.action.not_exposed', '_format' => null, '_stateless' => true, '_api_resource_class' => 'App\\ApiResource\\SentimentAnalysis', '_api_operation_name' => '_api_/.well-known/genid/{id}{._format}_get'], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        362 => [[['_route' => 'api_submission_predict_priority', '_controller' => 'App\\Controller\\Api\\PriorityController::predictPriority'], ['id'], ['POST' => 0], null, false, false, null]],
        386 => [[['_route' => 'api_submission_update_priority', '_controller' => 'App\\Controller\\Api\\PriorityController::updatePriority'], ['id'], ['POST' => 0], null, false, false, null]],
        417 => [[['_route' => 'api_item_quantity', '_controller' => 'App\\Controller\\Order\\ItemsController::getQuantity'], ['id'], ['GET' => 0], null, false, false, null]],
        458 => [[['_route' => 'app_default_avatar', '_controller' => 'App\\Controller\\user\\AvatarController::defaultAvatar'], ['seed', 'size'], null, null, false, true, null]],
        494 => [[['_route' => 'fos_js_routing_js', '_controller' => 'fos_js_routing.controller::indexAction', '_format' => 'js'], ['_format'], ['GET' => 0], null, false, true, null]],
        533 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        553 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        595 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        632 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        646 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        666 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        679 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        689 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        754 => [[['_route' => 'liip_imagine_filter_runtime', '_controller' => 'Liip\\ImagineBundle\\Controller\\ImagineController::filterRuntimeAction'], ['filter', 'hash', 'path'], ['GET' => 0], null, false, true, null]],
        780 => [[['_route' => 'liip_imagine_filter', '_controller' => 'Liip\\ImagineBundle\\Controller\\ImagineController::filterAction'], ['filter', 'path'], ['GET' => 0], null, false, true, null]],
        811 => [[['_route' => 'edit_item', '_controller' => 'App\\Controller\\Order\\ItemsController::editItem'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        836 => [[['_route' => 'item_delete', '_controller' => 'App\\Controller\\Order\\ItemsController::deleteItem'], ['idItem'], ['GET' => 0], null, false, true, null]],
        875 => [[['_route' => 'order_items', '_controller' => 'App\\Controller\\Order\\OrdersController::getOrderItems'], ['orderId'], null, null, false, true, null]],
        902 => [[['_route' => 'update_item_quantity', '_controller' => 'App\\Controller\\Order\\OrdersController::updateItemQuantity'], ['itemId'], ['POST' => 0], null, false, true, null]],
        925 => [[['_route' => 'delete_item', '_controller' => 'App\\Controller\\Order\\OrdersController::deleteItem'], ['itemId'], ['DELETE' => 0], null, false, true, null]],
        950 => [[['_route' => 'order_delete', '_controller' => 'App\\Controller\\Order\\OrdersController::deleteOrder'], ['idOrder'], ['GET' => 0], null, false, true, null]],
        973 => [[['_route' => 'order_update', '_controller' => 'App\\Controller\\Order\\OrdersController::updateOrder'], ['idOrder'], ['POST' => 0], null, false, true, null]],
        1002 => [[['_route' => 'orders_by_month', '_controller' => 'App\\Controller\\Order\\OrdersController::getOrdersByMonth'], ['month'], null, null, false, true, null]],
        1048 => [[['_route' => 'app_reset_password', 'token' => null, '_controller' => 'App\\Controller\\ResetPasswordController::reset'], ['token'], null, null, false, true, null]],
        1096 => [[['_route' => 'deleteBill', '_controller' => 'App\\Controller\\Warehouse\\BillController::deleteBill'], ['id'], null, null, false, true, null]],
        1119 => [[['_route' => 'updateBill', '_controller' => 'App\\Controller\\Warehouse\\BillController::updateBill'], ['id'], null, null, false, true, null]],
        1150 => [[['_route' => 'deleteCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::deleteCar'], ['id'], null, null, false, true, null]],
        1173 => [[['_route' => 'updateCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::updateCar'], ['id'], null, null, false, true, null]],
        1210 => [[['_route' => 'deleteWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::deleteWarehouse'], ['id'], null, null, false, true, null]],
        1233 => [[['_route' => 'updateWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::updateWarehouse'], ['id'], null, null, false, true, null]],
        1263 => [[['_route' => 'app_response_show', '_controller' => 'App\\Controller\\submission\\ResponseController::show'], ['idResponse'], ['GET' => 0], null, false, true, null]],
        1277 => [[['_route' => 'app_response_edit', '_controller' => 'App\\Controller\\submission\\ResponseController::edit'], ['idResponse'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        1286 => [[['_route' => 'app_response_delete', '_controller' => 'App\\Controller\\submission\\ResponseController::delete'], ['idResponse'], ['POST' => 0], null, false, true, null]],
        1321 => [[['_route' => 'app_submission_show', '_controller' => 'App\\Controller\\submission\\SubmissionController::show'], ['idSubmission'], ['GET' => 0], null, false, true, null]],
        1341 => [[['_route' => 'app_submission_edit', '_controller' => 'App\\Controller\\submission\\SubmissionController::edit'], ['idSubmission'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        1366 => [[['_route' => 'app_submission_extract_keyterms', '_controller' => 'App\\Controller\\submission\\SubmissionController::extractKeyTerms'], ['idSubmission'], ['GET' => 0], null, false, false, null]],
        1382 => [[['_route' => 'app_submission_update_status', '_controller' => 'App\\Controller\\submission\\SubmissionController::updateStatus'], ['idSubmission'], ['PUT' => 0], null, false, false, null]],
        1408 => [[['_route' => 'app_submission_predict_priority', '_controller' => 'App\\Controller\\submission\\SubmissionController::predictPriority'], ['idSubmission'], ['POST' => 0], null, false, false, null]],
        1433 => [[['_route' => 'app_submission_update_priority', '_controller' => 'App\\Controller\\submission\\SubmissionController::updatePriority'], ['idSubmission'], ['POST' => 0], null, false, false, null]],
        1443 => [[['_route' => 'app_submission_delete', '_controller' => 'App\\Controller\\submission\\SubmissionController::delete'], ['idSubmission'], ['POST' => 0], null, false, true, null]],
        1482 => [[['_route' => 'app_submission_Front_Submission_delete', '_controller' => 'App\\Controller\\submission\\FrontSubmissionController::delete'], ['idSubmission'], ['POST' => 0], null, false, true, null]],
        1504 => [[['_route' => 'app_submission_Front_Submission_edit', '_controller' => 'App\\Controller\\submission\\FrontSubmissionController::edit'], ['idSubmission'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        1547 => [[['_route' => 'app_submission_app_response_show', '_controller' => 'App\\Controller\\submission\\ResponseController::show'], ['idResponse'], ['GET' => 0], null, false, true, null]],
        1561 => [[['_route' => 'app_submission_app_response_edit', '_controller' => 'App\\Controller\\submission\\ResponseController::edit'], ['idResponse'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        1570 => [[['_route' => 'app_submission_app_response_delete', '_controller' => 'App\\Controller\\submission\\ResponseController::delete'], ['idResponse'], ['POST' => 0], null, false, true, null]],
        1602 => [[['_route' => 'app_submission_app_submission_show', '_controller' => 'App\\Controller\\submission\\SubmissionController::show'], ['idSubmission'], ['GET' => 0], null, false, true, null]],
        1622 => [[['_route' => 'app_submission_app_submission_edit', '_controller' => 'App\\Controller\\submission\\SubmissionController::edit'], ['idSubmission'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        1647 => [[['_route' => 'app_submission_app_submission_extract_keyterms', '_controller' => 'App\\Controller\\submission\\SubmissionController::extractKeyTerms'], ['idSubmission'], ['GET' => 0], null, false, false, null]],
        1663 => [[['_route' => 'app_submission_app_submission_update_status', '_controller' => 'App\\Controller\\submission\\SubmissionController::updateStatus'], ['idSubmission'], ['PUT' => 0], null, false, false, null]],
        1689 => [[['_route' => 'app_submission_app_submission_predict_priority', '_controller' => 'App\\Controller\\submission\\SubmissionController::predictPriority'], ['idSubmission'], ['POST' => 0], null, false, false, null]],
        1714 => [[['_route' => 'app_submission_app_submission_update_priority', '_controller' => 'App\\Controller\\submission\\SubmissionController::updatePriority'], ['idSubmission'], ['POST' => 0], null, false, false, null]],
        1724 => [[['_route' => 'app_submission_app_submission_delete', '_controller' => 'App\\Controller\\submission\\SubmissionController::delete'], ['idSubmission'], ['POST' => 0], null, false, true, null]],
        1762 => [[['_route' => 'feedbackdelete', '_controller' => 'App\\Controller\\user\\FeedbackManagementController::delete'], ['idFeedback'], null, null, false, true, null]],
        1780 => [[['_route' => 'user_delete', '_controller' => 'App\\Controller\\user\\UserManagementController::delete'], ['idUser'], null, null, false, true, null]],
        1821 => [[['_route' => 'Front_Submission_delete', '_controller' => 'App\\Controller\\submission\\FrontSubmissionController::delete'], ['idSubmission'], ['POST' => 0], null, false, true, null]],
        1843 => [[['_route' => 'Front_Submission_edit', '_controller' => 'App\\Controller\\submission\\FrontSubmissionController::edit'], ['idSubmission'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        1878 => [[['_route' => 'feedback_delete', '_controller' => 'App\\Controller\\user\\FeedbackController::delFeedback'], ['id'], ['POST' => 0], null, false, true, null]],
        1900 => [[['_route' => 'user_ban', '_controller' => 'App\\Controller\\user\\UserManagementController::ban'], ['idUser'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        1924 => [
            [['_route' => 'user_unban', '_controller' => 'App\\Controller\\user\\UserManagementController::unban'], ['idUser'], ['GET' => 0, 'POST' => 1], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
