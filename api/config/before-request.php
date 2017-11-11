<?php

return function ($event) {
    /** @var \yii\web\Application $app */
    $app = $event->sender;

    if ($app->request->isOptions) {
        $origin = '*';
        $headerOrigin = $app->request->headers->get('Origin');
        if ($headerOrigin) {
            $origin = $headerOrigin;
        } else if ($app->request->referrer) {
            $origin = $app->request->referrer;
        }

        $app->response->headers->set('Access-Control-Allow-Origin', $origin);
        $app->response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
        $app->response->headers->set('Access-Control-Request-Methods', 'GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
        $app->response->headers->set('Access-Control-Request-Headers', '*');
        $app->response->headers->set('Access-Control-Max-Age', 86400);
        $app->response->headers->set('Access-Control-Allow-Credentials', 'true');
        $app->response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $app->end(200);
    } else {
        $app->response->headers->set('Access-Control-Expose-Headers', 'Transfer-Encoding, X-Pagination-Total-Count, X-Pagination-Page-Count, X-Pagination-Current-Page, X-Pagination-Per-Page, Link');
    }
};
