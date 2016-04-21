<?php

use Sunra\PhpSimple\HtmlDomParser;

class CatalogueProcessor extends Processor
{
    protected $total = 0;
    protected $products = array();

    const descriptionSelector = 'div.productText p';
    const priceSelector = '.pricePerUnit';
    const titleSelector = 'h1';

    public function filter($url)
    {
        return preg_match(ProductPagePattern, $url);
    }

    public function process($content, $current_url)
    {
        $html = HtmlDomParser::str_get_html($content);

        $html_price = $this->getElementText($html, self::priceSelector);
        $price = $this->parseUnitPrice($html_price);

        $product = new Product();
        $product->title = $this->getElementText($html, self::titleSelector);
        $product->description = $this->getElementText($html, self::descriptionSelector);
        $product->unit_price = $this->formatPrice($price);
        $product->size = $this->pageSize($content);

        $this->products[] = $product;
        $this->total += $price;
    }

    public function parseUnitPrice($html_price)
    {
        $search = array('&pound;', '/unit');

        return floatval(str_replace($search, '', $html_price));
    }

    public function formatPrice($price)
    {
        return sprintf("%.2f", $price);
    }

    public function pageSize($content)
    {
        return round(strlen($content) / 1000, 1) . "kb";
    }

    public function getElementText($html, $selector)
    {
        return $html->find($selector, 0)->plaintext;
    }

    public function catalogue()
    {
        return array(
            'results' => $this->products,
            'total' => $this->formatPrice($this->total),
        );
    }
}
