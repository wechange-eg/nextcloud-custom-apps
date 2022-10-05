<?php

return [
    'routes' => [
        ['name' => 'Csp#getCsrfToken', 'url' => '/csrftoken', 'verb' => 'GET'],
        ['name' => 'Csp#logout', 'url' => '/logout', 'verb' => 'GET']
    ]
];