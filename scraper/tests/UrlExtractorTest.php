<?php

require_once __DIR__ . '/../include.php';

use ProductsScraper\CatalogueUrlExtractor;

class UrlExtractorTest extends PHPUnit_Framework_TestCase
{
    function testExtractorFindsLinksOnStartPage()
    {
        // Given
        $extractor = new CatalogueUrlExtractor();
        $input = "<div class='productInfo'><a href='http://url_a.com'>text</a></div>".
                 "<div class='productInfo'><a href='http://url_b.com'>text</a></div>";

        // When
        $urls = $extractor->extract($input, START_PAGE_URL);

        // Then
        $this->assertEquals(array('http://url_a.com', 'http://url_b.com'), $urls);
    }
    
    function testExtractorDoesNotFindsLinksWhenNotOnStartPage()
    {
        // Given
        $extractor = new CatalogueUrlExtractor();
        $input = "<div class='productInfo'><a href='http://url_a.com'>text</a></div>".
                 "<div class='productInfo'><a href='http://url_b.com'>text</a></div>";
        
        // When 
        $urls = $extractor->extract($input, 'some_page');
        
        // Then
        $this->assertEquals(array(), $urls);
    }

    function testExtractorHandlesNoLinks()
    {
        // Given
        $extractor = new CatalogueUrlExtractor();
        $input = "<div></div>";

        // When
        $urls = $extractor->extract($input, START_PAGE_URL);

        // Then
        $this->assertEquals(array(), $urls);
    }
}
