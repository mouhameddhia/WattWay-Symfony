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
                        .')'
                    .')'
                    .'|vatar/default/([^/]++)/([^/]++)(*:428)'
                .')'
                .'|/js/routing(?:\\.(js|json))?(*:464)'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:503)'
                    .'|wdt/([^/]++)(*:523)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:565)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:602)'
                                .'|router(*:616)'
                                .'|exception(?'
                                    .'|(*:636)'
                                    .'|\\.css(*:649)'
                                .')'
                            .')'
                            .'|(*:659)'
                        .')'
                    .')'
                .')'
                .'|/reset\\-password/reset(?:/([^/]++))?(*:706)'
                .'|/d(?'
                    .'|ashboard/(?'
                        .'|bill/(?'
                            .'|delete([^/]++)(*:753)'
                            .'|update([^/]++)(*:775)'
                        .')'
                        .'|car/(?'
                            .'|delete([^/]++)(*:805)'
                            .'|update([^/]++)(*:827)'
                        .')'
                        .'|warehouse/(?'
                            .'|delete([^/]++)(*:863)'
                            .'|update([^/]++)(*:885)'
                        .')'
                        .'|response/([^/]++)(?'
                            .'|(*:914)'
                            .'|/edit(*:927)'
                            .'|(*:935)'
                        .')'
                        .'|submission/(?'
                            .'|([^/]++)(?'
                                .'|(*:969)'
                                .'|/(?'
                                    .'|e(?'
                                        .'|dit(*:988)'
                                        .'|xtract\\-keyterms(*:1012)'
                                    .')'
                                    .'|status(*:1028)'
                                    .'|predict\\-priority(*:1054)'
                                    .'|update\\-priority(*:1079)'
                                .')'
                                .'|(*:1089)'
                            .')'
                            .'|submission/(?'
                                .'|delete/([^/]++)(*:1128)'
                                .'|edit/([^/]++)(*:1150)'
                            .')'
                            .'|dashboard/(?'
                                .'|response/([^/]++)(?'
                                    .'|(*:1193)'
                                    .'|/edit(*:1207)'
                                    .'|(*:1216)'
                                .')'
                                .'|submission/([^/]++)(?'
                                    .'|(*:1248)'
                                    .'|/(?'
                                        .'|e(?'
                                            .'|dit(*:1268)'
                                            .'|xtract\\-keyterms(*:1293)'
                                        .')'
                                        .'|status(*:1309)'
                                        .'|predict\\-priority(*:1335)'
                                        .'|update\\-priority(*:1360)'
                                    .')'
                                    .'|(*:1370)'
                                .')'
                            .')'
                        .')'
                    .')'
                    .'|elete(?'
                        .'|Feedback/([^/]++)(*:1408)'
                        .'|/([^/]++)(*:1426)'
                    .')'
                .')'
                .'|/submission/(?'
                    .'|delete/([^/]++)(*:1467)'
                    .'|edit/([^/]++)(*:1489)'
                .')'
                .'|/feedback/delete/([^/]++)(*:1524)'
                .'|/ban/([^/]++)(*:1546)'
                .'|/unban/([^/]++)(*:1570)'
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
        428 => [[['_route' => 'app_default_avatar', '_controller' => 'App\\Controller\\user\\AvatarController::defaultAvatar'], ['seed', 'size'], null, null, false, true, null]],
        464 => [[['_route' => 'fos_js_routing_js', '_controller' => 'fos_js_routing.controller::indexAction', '_format' => 'js'], ['_format'], ['GET' => 0], null, false, true, null]],
        503 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        523 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        565 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        602 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        616 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        636 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        649 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        659 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        706 => [[['_route' => 'app_reset_password', 'token' => null, '_controller' => 'App\\Controller\\ResetPasswordController::reset'], ['token'], null, null, false, true, null]],
        753 => [[['_route' => 'deleteBill', '_controller' => 'App\\Controller\\Warehouse\\BillController::deleteBill'], ['id'], null, null, false, true, null]],
        775 => [[['_route' => 'updateBill', '_controller' => 'App\\Controller\\Warehouse\\BillController::updateBill'], ['id'], null, null, false, true, null]],
        805 => [[['_route' => 'deleteCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::deleteCar'], ['id'], null, null, false, true, null]],
        827 => [[['_route' => 'updateCar', '_controller' => 'App\\Controller\\Warehouse\\CarController::updateCar'], ['id'], null, null, false, true, null]],
        863 => [[['_route' => 'deleteWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::deleteWarehouse'], ['id'], null, null, false, true, null]],
        885 => [[['_route' => 'updateWarehouse', '_controller' => 'App\\Controller\\Warehouse\\WarehouseController::updateWarehouse'], ['id'], null, null, false, true, null]],
        914 => [[['_route' => 'app_response_show', '_controller' => 'App\\Controller\\submission\\ResponseController::show'], ['idResponse'], ['GET' => 0], null, false, true, null]],
        927 => [[['_route' => 'app_response_edit', '_controller' => 'App\\Controller\\submission\\ResponseController::edit'], ['idResponse'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        935 => [[['_route' => 'app_response_delete', '_controller' => 'App\\Controller\\submission\\ResponseController::delete'], ['idResponse'], ['POST' => 0], null, false, true, null]],
        969 => [[['_route' => 'app_submission_show', '_controller' => 'App\\Controller\\submission\\SubmissionController::show'], ['idSubmission'], ['GET' => 0], null, false, true, null]],
        988 => [[['_route' => 'app_submission_edit', '_controller' => 'App\\Controller\\submission\\SubmissionController::edit'], ['idSubmission'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        1012 => [[['_route' => 'app_submission_extract_keyterms', '_controller' => 'App\\Controller\\submission\\SubmissionController::extractKeyTerms'], ['idSubmission'], ['GET' => 0], null, false, false, null]],
        1028 => [[['_route' => 'app_submission_update_status', '_controller' => 'App\\Controller\\submission\\SubmissionController::updateStatus'], ['idSubmission'], ['PUT' => 0], null, false, false, null]],
        1054 => [[['_route' => 'app_submission_predict_priority', '_controller' => 'App\\Controller\\submission\\SubmissionController::predictPriority'], ['idSubmission'], ['POST' => 0], null, false, false, null]],
        1079 => [[['_route' => 'app_submission_update_priority', '_controller' => 'App\\Controller\\submission\\SubmissionController::updatePriority'], ['idSubmission'], ['POST' => 0], null, false, false, null]],
        1089 => [[['_route' => 'app_submission_delete', '_controller' => 'App\\Controller\\submission\\SubmissionController::delete'], ['idSubmission'], ['POST' => 0], null, false, true, null]],
        1128 => [[['_route' => 'app_submission_Front_Submission_delete', '_controller' => 'App\\Controller\\submission\\FrontSubmissionController::delete'], ['idSubmission'], ['POST' => 0], null, false, true, null]],
        1150 => [[['_route' => 'app_submission_Front_Submission_edit', '_controller' => 'App\\Controller\\submission\\FrontSubmissionController::edit'], ['idSubmission'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        1193 => [[['_route' => 'app_submission_app_response_show', '_controller' => 'App\\Controller\\submission\\ResponseController::show'], ['idResponse'], ['GET' => 0], null, false, true, null]],
        1207 => [[['_route' => 'app_submission_app_response_edit', '_controller' => 'App\\Controller\\submission\\ResponseController::edit'], ['idResponse'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        1216 => [[['_route' => 'app_submission_app_response_delete', '_controller' => 'App\\Controller\\submission\\ResponseController::delete'], ['idResponse'], ['POST' => 0], null, false, true, null]],
        1248 => [[['_route' => 'app_submission_app_submission_show', '_controller' => 'App\\Controller\\submission\\SubmissionController::show'], ['idSubmission'], ['GET' => 0], null, false, true, null]],
        1268 => [[['_route' => 'app_submission_app_submission_edit', '_controller' => 'App\\Controller\\submission\\SubmissionController::edit'], ['idSubmission'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        1293 => [[['_route' => 'app_submission_app_submission_extract_keyterms', '_controller' => 'App\\Controller\\submission\\SubmissionController::extractKeyTerms'], ['idSubmission'], ['GET' => 0], null, false, false, null]],
        1309 => [[['_route' => 'app_submission_app_submission_update_status', '_controller' => 'App\\Controller\\submission\\SubmissionController::updateStatus'], ['idSubmission'], ['PUT' => 0], null, false, false, null]],
        1335 => [[['_route' => 'app_submission_app_submission_predict_priority', '_controller' => 'App\\Controller\\submission\\SubmissionController::predictPriority'], ['idSubmission'], ['POST' => 0], null, false, false, null]],
        1360 => [[['_route' => 'app_submission_app_submission_update_priority', '_controller' => 'App\\Controller\\submission\\SubmissionController::updatePriority'], ['idSubmission'], ['POST' => 0], null, false, false, null]],
        1370 => [[['_route' => 'app_submission_app_submission_delete', '_controller' => 'App\\Controller\\submission\\SubmissionController::delete'], ['idSubmission'], ['POST' => 0], null, false, true, null]],
        1408 => [[['_route' => 'feedbackdelete', '_controller' => 'App\\Controller\\user\\FeedbackManagementController::delete'], ['idFeedback'], null, null, false, true, null]],
        1426 => [[['_route' => 'user_delete', '_controller' => 'App\\Controller\\user\\UserManagementController::delete'], ['idUser'], null, null, false, true, null]],
        1467 => [[['_route' => 'Front_Submission_delete', '_controller' => 'App\\Controller\\submission\\FrontSubmissionController::delete'], ['idSubmission'], ['POST' => 0], null, false, true, null]],
        1489 => [[['_route' => 'Front_Submission_edit', '_controller' => 'App\\Controller\\submission\\FrontSubmissionController::edit'], ['idSubmission'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        1524 => [[['_route' => 'feedback_delete', '_controller' => 'App\\Controller\\user\\FeedbackController::delFeedback'], ['id'], ['POST' => 0], null, false, true, null]],
        1546 => [[['_route' => 'user_ban', '_controller' => 'App\\Controller\\user\\UserManagementController::ban'], ['idUser'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        1570 => [
            [['_route' => 'user_unban', '_controller' => 'App\\Controller\\user\\UserManagementController::unban'], ['idUser'], ['GET' => 0, 'POST' => 1], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
