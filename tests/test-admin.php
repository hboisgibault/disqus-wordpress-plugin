<?php
/**
 * Admin plugin tests.
 */

class Test_Admin extends WP_UnitTestCase {

    /**
     * Check that a sync token gets created when instantiating the admin class
     */
    function test_create_sync_token() {
        delete_option( 'disqus_sync_token' );

        new Disqus_Admin( 'disqus', '0.0.0', 'foo' );

        $this->assertEquals( 32, strlen( get_option( 'disqus_sync_token' ) ) );
    }

    /**
     * Check that the REST URL filter doesn't replace the host when they're the same.
     */
    function test_dsq_filter_rest_url_same_host() {
        $admin = new Disqus_Admin( 'disqus', '0.0.0', 'foo' );

        $_SERVER['host'] = 'foo.com';

        $rest_url = $admin->dsq_filter_rest_url( 'https://foo.com/wp-json/disqus/v1' );

        $this->assertEquals( 'https://foo.com/wp-json/disqus/v1', $rest_url );
    }

    /**
     * Check that the REST URL filter does replace the host when they're different.
     */
    function test_dsq_filter_rest_url_different_host() {
        $admin = new Disqus_Admin( 'disqus', '0.0.0', 'foo' );

        $_SERVER['host'] = 'bar.com';

        $rest_url = $admin->dsq_filter_rest_url( 'https://foo.com/wp-json/disqus/v1' );

        $this->assertEquals( 'https://bar.com/wp-json/disqus/v1', $rest_url );
    }

}
