# InvalidArgumentException - Internal Server Error
Unable to locate a class or view for component [guest-layout].

PHP 8.3.6
Laravel 12.34.0
tafeld.test

## Stack Trace

0 - vendor/laravel/framework/src/Illuminate/View/Compilers/ComponentTagCompiler.php:315
1 - vendor/laravel/framework/src/Illuminate/View/Compilers/ComponentTagCompiler.php:235
2 - vendor/laravel/framework/src/Illuminate/View/Compilers/ComponentTagCompiler.php:156
3 - vendor/laravel/framework/src/Illuminate/View/Compilers/ComponentTagCompiler.php:151
4 - vendor/laravel/framework/src/Illuminate/View/Compilers/ComponentTagCompiler.php:90
5 - vendor/laravel/framework/src/Illuminate/View/Compilers/ComponentTagCompiler.php:76
6 - vendor/laravel/framework/src/Illuminate/View/Compilers/BladeCompiler.php:455
7 - vendor/laravel/framework/src/Illuminate/View/Compilers/BladeCompiler.php:287
8 - vendor/laravel/framework/src/Illuminate/View/Compilers/BladeCompiler.php:187
9 - vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php:68
10 - vendor/livewire/livewire/src/Mechanisms/ExtendBlade/ExtendedCompilerEngine.php:10
11 - vendor/laravel/framework/src/Illuminate/View/View.php:208
12 - vendor/laravel/framework/src/Illuminate/View/View.php:191
13 - vendor/laravel/framework/src/Illuminate/View/View.php:160
14 - vendor/laravel/framework/src/Illuminate/Http/Response.php:78
15 - vendor/laravel/framework/src/Illuminate/Http/Response.php:34
16 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:939
17 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:906
18 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
19 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
20 - vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php:50
21 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
22 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php:87
23 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
24 - vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php:48
25 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
26 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:120
27 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:63
28 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
29 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php:36
30 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
31 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php:74
32 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
33 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
34 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
35 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:800
36 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:764
37 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:753
38 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:200
39 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
40 - vendor/livewire/livewire/src/Features/SupportDisablingBackButtonCache/DisableBackButtonCacheMiddleware.php:19
41 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
42 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php:21
43 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php:31
44 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
45 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php:21
46 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php:51
47 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
48 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php:27
49 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
50 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php:109
51 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
52 - vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php:48
53 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
54 - vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php:58
55 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
56 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php:22
57 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
58 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php:26
59 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
60 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
61 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:175
62 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:144
63 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
64 - public/index.php:20

## Request

GET /

## Headers

* **priority**: u=0, i
* **sec-fetch-user**: ?1
* **sec-fetch-site**: none
* **sec-fetch-mode**: navigate
* **sec-fetch-dest**: document
* **upgrade-insecure-requests**: 1
* **cookie**: XDEBUG_SESSION=XDEBUG_ECLIPSE; XSRF-TOKEN=eyJpdiI6InBaTEFQVWFSc3ZKZURoRC91NVhJcGc9PSIsInZhbHVlIjoiUEgvdXhTUExMM0tpL3dLcWJlQVhNMkN0c2NpZ2RFR1VOd00reWRMbURZcEdveTd6S2hNL1R3SVVHRzJqRGlyMUF0ZjkxNDh6UDk1T0owRzdBc28wQmtuVmJlTnMxdkN4U0RUQ2pyVThpQlNsZExFNjVOZ1VUL3lxelJKVGJyUnMiLCJtYWMiOiI3NDI0MTg4OWM2Yjc1MzNhMGM2MjcxMjVmNTIxYjQyZDlhMDdkMGMyNDg3NDVjZTFhNDVhYTRmNmZmYjgwMWY4IiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6IjB5VVBxOEc1Mnp2L1BGL1V6dGZlT1E9PSIsInZhbHVlIjoidzROUWMvdXBNUkREQWF3Umx5dGlFUERuakc4NlI1cE9mbzFXa0kyN21iUlQ5VDBFTUlVaXRlM09zL05uc0MzL1VWSUl6aEpkMytRT2ZzS2RIcVBtQ2k3N1VWb3ppdi9UcWpBZTlxZjZFN09udlRzUG12L1gvNGhTNFY5alVvN0MiLCJtYWMiOiJjMmVlMGU0MTYyOWUzN2E1YmI5N2FlMjM5MjY4ZTBlM2M1ZDM1NGMzN2Y1ZWQzNzgyYjg4MjJjZmRlYjRhN2I5IiwidGFnIjoiIn0%3D
* **connection**: keep-alive
* **accept-encoding**: gzip, deflate, br, zstd
* **accept-language**: de,en-US;q=0.7,en;q=0.3
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0
* **host**: tafeld.test

## Route Context

controller: Closure
route name: home
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

* pgsql - select * from "sessions" where "id" = '8rKXZ2R5cRxukt5phgrvsnF80qrkehLdigpORymx' limit 1 (8 ms)
