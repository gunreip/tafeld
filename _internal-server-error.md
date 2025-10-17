# Livewire\Features\SupportMultipleRootElementDetection\MultipleRootElementsDetectedException - Internal Server Error
Livewire only supports one HTML element per component. Multiple root elements detected for component: [personal]

PHP 8.3.6
Laravel 12.34.0
tafeld.test

## Stack Trace

0 - vendor/livewire/livewire/src/Features/SupportMultipleRootElementDetection/SupportMultipleRootElementDetection.php:27
1 - vendor/livewire/livewire/src/Features/SupportMultipleRootElementDetection/SupportMultipleRootElementDetection.php:16
2 - vendor/livewire/livewire/src/EventBus.php:73
3 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:72
4 - vendor/livewire/livewire/src/LivewireManager.php:73
5 - vendor/livewire/volt/src/LivewireManager.php:23
6 - vendor/livewire/livewire/src/Features/SupportPageComponents/HandlesPageComponents.php:17
7 - vendor/livewire/livewire/src/Features/SupportPageComponents/SupportPageComponents.php:117
8 - vendor/livewire/livewire/src/Features/SupportPageComponents/HandlesPageComponents.php:14
9 - vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:46
10 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:265
11 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:211
12 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:822
13 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
14 - vendor/laravel/framework/src/Illuminate/Auth/Middleware/EnsureEmailIsVerified.php:41
15 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
16 - vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php:50
17 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
18 - vendor/laravel/framework/src/Illuminate/Auth/Middleware/Authenticate.php:63
19 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
20 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php:87
21 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
22 - vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php:48
23 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
24 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:120
25 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:63
26 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
27 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php:36
28 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
29 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php:74
30 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
31 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
32 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
33 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:800
34 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:764
35 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:753
36 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:200
37 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
38 - vendor/livewire/livewire/src/Features/SupportDisablingBackButtonCache/DisableBackButtonCacheMiddleware.php:19
39 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
40 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php:21
41 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php:31
42 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
43 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php:21
44 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php:51
45 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
46 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php:27
47 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
48 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php:109
49 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
50 - vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php:48
51 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
52 - vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php:58
53 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
54 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php:22
55 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
56 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php:26
57 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
58 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
59 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:175
60 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:144
61 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
62 - public/index.php:20

## Request

GET /personal

## Headers

* **priority**: u=0, i
* **sec-fetch-user**: ?1
* **sec-fetch-site**: none
* **sec-fetch-mode**: navigate
* **sec-fetch-dest**: document
* **upgrade-insecure-requests**: 1
* **cookie**: XDEBUG_SESSION=XDEBUG_ECLIPSE; XSRF-TOKEN=eyJpdiI6Ikh1ZFNxNHhJMENhZkl0cGhOVXJGekE9PSIsInZhbHVlIjoiU3FkclkwbVFpYzVWdld0aGxwVTE1UmVHSEdFVlpSNlp6aCtlWEQ5bnFKRHZMN251TTJpTTZkU3QxL1E2aVJDNDFkQzlrZEdDTUdRd1czOHRlZ3pkL1hGbzlUQmMxc0RKbVVtWldRNFBScHlla2tGSWthL0VqN3NuNE9DdXdNamYiLCJtYWMiOiIyMzRkNmFhZGY3MDYzNDA5MTU4NWI0NjQ4NWY1NjdjYTI2MTFkN2M4ZmRkMTViYmI1M2UxYjVhMjZhMTRlZWI1IiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6ImxoSjRmZWpFZWlqbGE5OW5JOUdteEE9PSIsInZhbHVlIjoia0lkVlZwUzE4ajF3SkFqZ1Ftc2U5eW5Kbmh2dWxsaFczUDVlbFhDWTFhdVFsQkFqSWVndlFXY2VBL3FaT1dyMi9oRXJLZHdRTWluc0NKeExMZXUzVzVJOVdRZndRc0I1Mm1yWERVUklWbE1CdXpzYkdtK0pkcUc1eW9yUHBxOXgiLCJtYWMiOiI1MjFjMDNlZDM0NmY1ZGJkZjBiNzYzYzI4MTA4ZThmNjU3YzA1MGUwNDM5ZTQzMGM2ZmY3MGM3OGIzY2YzY2FmIiwidGFnIjoiIn0%3D
* **connection**: keep-alive
* **accept-encoding**: gzip, deflate, br, zstd
* **accept-language**: de,en-US;q=0.7,en;q=0.3
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0
* **host**: tafeld.test

## Route Context

controller: App\Livewire\Personal\Index
route name: personal.index
middleware: web, auth, verified

## Route Parameters

No route parameter data available.

## Database Queries

* pgsql - select * from "sessions" where "id" = 'lHHnD032mgvl9RUExS976Bt7B2DWnWhgHpnvQdlQ' limit 1 (8.98 ms)
* pgsql - select * from "users" where "id" = 1 limit 1 (0.76 ms)
