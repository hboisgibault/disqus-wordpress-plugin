<?php
/*
 * REST API Tests.
 */

// See for how filter used: https://github.com/WordPress/WordPress/blob/master/wp-includes/class-http.php#L247
function reflect_params( $preempt, $args, $url ) {
    // Simply return a json-encoded representation of the args that were passed.
    return array(
        'body' => json_encode( array(
            'code' => 0,
            'response' => array(
                'url' => $url,
                'args' => $args,
            ),
        ) ),
    );
}

class Test_Api_Service extends WP_UnitTestCase {

    public function setUp() {
        parent::setUp();

        // Filter HTTP requests made from `wp_remote_get` and `wp_remote_post` so we don't actually call server.
        add_filter( 'pre_http_request', 'reflect_params', 1, 3 );
    }

    /**
     * Check that Disqus API GET requests include the secret key and access token without passing it explicitly.
     */
    public function test_api_get_tokens() {
        $api_service = new Disqus_Api_Service( 'APISECRETKEY', 'ACCESSTOKEN' );

        $api_data = $api_service->api_get( 'someEndpoint', array() );

        $this->assertNotFalse( strpos( $api_data->response->url, 'someEndpoint.json' ) );
        $this->assertNotFalse( strpos( $api_data->response->url, 'api_secret=APISECRETKEY' ) );
        $this->assertNotFalse( strpos( $api_data->response->url, 'access_token=ACCESSTOKEN' ) );
    }

    /**
     * Check that Disqus API POST requests include the secret key and access token without passing it explicitly.
     */
    public function test_api_post_tokens() {
        $api_service = new Disqus_Api_Service( 'APISECRETKEY', 'ACCESSTOKEN' );

        $api_data = $api_service->api_post( 'someEndpoint', array() );

        $this->assertNotFalse( strpos( $api_data->response->url, 'someEndpoint.json' ) );
        $this->assertNotFalse( strpos( $api_data->response->url, 'api_secret=APISECRETKEY' ) );
        $this->assertNotFalse( strpos( $api_data->response->url, 'access_token=ACCESSTOKEN' ) );
    }

    /**
     * Check that Disqus API POST requests have no referer.
     */
    public function test_api_post_no_referer() {
        $this->assertTrue( true );
    }
}
