<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('l5-swagger.documentations.' . $documentation . '.api.title') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}">
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-32x32.png') }}"
        sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-16x16.png') }}"
        sizes="16x16" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            background: #fafafa;
        }
    </style>
</head>

<body>
    <div id="swagger-ui"></div>

    <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"></script>
    <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"></script>
    <script>
        window.onload = function() {


            // Build a system
            const ui = SwaggerUIBundle({
                dom_id: '#swagger-ui',
                url: "{!! $urlToDocs !!}",
                operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
                configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
                validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
                oauth2RedirectUrl: "{{ route('l5-swagger.' . $documentation . '.oauth2_callback', [], $useAbsolutePath) }}",

                requestInterceptor: function(request) {
                    if (request.headers.Xdebug === 'PHPSTORM') {
                        // Add XDEBUG_SESSION query parameter to all requests
                        const url = new URL(request.url);
                        url.searchParams.append('XDEBUG_SESSION', 'PHPSTORM');
                        request.url = url.toString();
                    }
                    request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    const token = localStorage.getItem('token');
                    if (token) {
                        request.headers.Authorization = 'Bearer ' + token;
                    }
                    // typeof request.body == 'object' check content-type is multipart/form-data
                    if ((request.method === 'PUT' || request.method === 'PATCH') && typeof request.body ==
                        'object') {
                        const originalMethod = request.method;
                        request.method = 'POST';

                        // Add _method=PUT or _method=PATCH to request body if it's formData or to query params
                        if (request.body) {
                            // Handle multipart/form-data or JSON data
                            if (request.body instanceof FormData) {
                                request.body.append('_method',
                                    originalMethod); // append _method to form data
                            } else if (typeof request.body === 'object') {
                                request.body._method = originalMethod; // add _method to JSON body
                            }
                        } else {
                            // If no body, add _method as a query parameter
                            const url = new URL(request.url);
                            url.searchParams.append('_method', originalMethod);
                            request.url = url.toString();
                        }
                    }
                    return request;
                },

                // Add the responseInterceptor
                responseInterceptor: function(response) {
                    // Check if the request URL is the /login endpoint
                    if (response.status === 200 || response.status === 201) {
                        if (response.url.endsWith('login') || response.url.endsWith('confirm')) {
                            const token = response.body.data.token;

                            // Store the token
                            localStorage.setItem('token', token);
                            // Retrieve the existing object
                            let authorized = JSON.parse(localStorage.getItem('authorized')) || {};
                            // Add the new key to the object
                            authorized['Passport'] = {
                                name: 'passport',
                                value: 'Bearer ' + token,
                                schema: {
                                    type: 'apiKey',
                                    in: 'header',
                                    name: 'Authorization'
                                }
                            };
                            // Store the updated object back in localStorage
                            localStorage.setItem('authorized', JSON.stringify(authorized));

                            // Refresh the page
                            location.reload();
                        }
                    }
                    return response;
                },

                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],

                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],

                layout: "StandaloneLayout",
                docExpansion: "{!! config('l5-swagger.defaults.ui.display.doc_expansion', 'none') !!}",
                deepLinking: true,
                filter: {!! config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false' !!},
                persistAuthorization: "{!! config('l5-swagger.defaults.ui.authorization.persist_authorization') ? 'true' : 'false' !!}",

            })

            window.ui = ui

            @if (in_array('oauth2', array_column(config('l5-swagger.defaults.securityDefinitions.securitySchemes'), 'type')))
                ui.initOAuth({
                    usePkceWithAuthorizationCodeGrant: "{!! (bool) config('l5-swagger.defaults.ui.authorization.oauth2.use_pkce_with_authorization_code_grant') !!}"
                })
            @endif
        }
    </script>
</body>

</html>
