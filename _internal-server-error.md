# Illuminate\Database\Eloquent\MassAssignmentException - Internal Server Error
Add [id] to fillable property to allow mass assignment on [App\Models\Personal].

PHP 8.3.6
Laravel 12.34.0
tafeld.test

## Stack Trace

0 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:624
1 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:713
2 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:1747
3 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:1218
4 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:725
5 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:1959
6 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:725
7 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:712
8 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:740
9 - vendor/laravel/framework/src/Illuminate/Support/Traits/ForwardsCalls.php:23
10 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:2540
11 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:2556
12 - app/Livewire/Personal/Table.php:61
13 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:36
14 - vendor/laravel/framework/src/Illuminate/Container/Util.php:43
15 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:96
16 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:35
17 - vendor/livewire/livewire/src/Wrapped.php:23
18 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:492
19 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:101
20 - vendor/livewire/livewire/src/LivewireManager.php:102
21 - vendor/livewire/volt/src/LivewireManager.php:35
22 - vendor/livewire/livewire/src/Mechanisms/HandleRequests/HandleRequests.php:94
23 - vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:46
24 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:265
25 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:211
26 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:822
27 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
28 - vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php:50
29 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
30 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php:87
31 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
32 - vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php:48
33 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
34 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:120
35 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:63
36 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
37 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php:36
38 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
39 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php:74
40 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
41 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
42 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
43 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:800
44 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:764
45 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:753
46 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:200
47 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
48 - vendor/livewire/livewire/src/Features/SupportDisablingBackButtonCache/DisableBackButtonCacheMiddleware.php:19
49 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
50 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php:27
51 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
52 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php:47
53 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
54 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php:27
55 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
56 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php:109
57 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
58 - vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php:48
59 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
60 - vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php:58
61 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
62 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php:22
63 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
64 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php:26
65 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
66 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
67 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:175
68 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:144
69 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
70 - public/index.php:20

## Request

POST /livewire/update

## Headers

* **priority**: u=4
* **sec-fetch-site**: same-origin
* **sec-fetch-mode**: cors
* **sec-fetch-dest**: empty
* **cookie**: XDEBUG_SESSION=XDEBUG_ECLIPSE; XSRF-TOKEN=eyJpdiI6ImVOQ1hXUDlUeityMnRsQ2VaT3lTckE9PSIsInZhbHVlIjoiTVFhZnNLQlYxTWcvVm1RQjMyVTd0b3h6ZFRzVUk0NjFSVHI4T2prR2RwU0ZyWUlqcU11K0drdTN4RCtUY3ZkcS9xTG5lY3pOL2FHaWZXZXJGQWxXV3gvNVU4eFNJWVRpbWJTbUM3OWNUaXFoYXM4SU0zODFjbnZZYmpZTnN1b1oiLCJtYWMiOiIwZDM0ZmI2MTM2ZDM1ZmM1YTI1YmJmMzNiODAxNDFjYWQ5NjQ5ZWI4ZTkyOTE5NGRmZjE0ZGVkNzA5MGY4MmEzIiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6InNlUHVrMXhpRU93cWV4bnJWc0NiS0E9PSIsInZhbHVlIjoiYmlpQW1CeVc5ZUlTa09ydkFYMFhNc0gvMDJnbFkrV0lRclkrVVJyMUplUFE2OGF0K1JoTFFzMEhqYUd1NWUzNVdDMTE1Sy9laWJiQWdlRlhSQmllYzRKWExLYkJ1SVVvUG90WXJBUDhsWFFlZDlhNEFZd09jeVlUV3EyR3hZWlYiLCJtYWMiOiJlNjA4YjZhZDI1Y2QxYThkZmNlYTcxMTc2NGQwZWUyZTVmNDQ3N2JiZmYzNTE4ZjAwNTQ5OTJkM2I2MGJkMjJmIiwidGFnIjoiIn0%3D
* **connection**: keep-alive
* **origin**: https://tafeld.test
* **content-length**: 809
* **x-livewire**: 
* **content-type**: application/json
* **referer**: https://tafeld.test/personal
* **accept-encoding**: gzip, deflate, br, zstd
* **accept-language**: de,en-US;q=0.7,en;q=0.3
* **accept**: */*
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0
* **host**: tafeld.test

## Route Context

controller: Livewire\Mechanisms\HandleRequests\HandleRequests@handleUpdate
route name: livewire.update
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

* pgsql - select * from "sessions" where "id" = 'lHHnD032mgvl9RUExS976Bt7B2DWnWhgHpnvQdlQ' limit 1 (10.12 ms)
* pgsql - select * from "users" where "id" = 1 limit 1 (0.98 ms)
* pgsql - select * from "personals" where ("id" is null) limit 1 (0.83 ms)
