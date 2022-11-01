<?php

namespace App\Services;

use Goutte\Client;
use App\Models\Article;

class ArticleService
{


    public function scrape()
    {

        $client = new Client();
        $website = $client->request('GET', 'https://www.spiegel.de/politik/');

        $raw_title = $website->filter('span > .align-middle')->each(function ($node) {
            return  $node->text();
        });
        $title_link = $website->filter('div>header>h2>a')->each(function ($node) {
            return $node->first()->attr('href');
        });
        $title = array_filter($raw_title);

        $excerpt = $website->filter('section>a>.font-serifUI')->each(function ($node) {
            return  $node->text();
        });
        $dates = $website->filter('footer>.flex, .flex-wrap, .items-end')->each(function ($node) {
            return $node->children()->eq(0)->text();
        });
        $clean_dates = $this->clean_date($dates);

        $image_urls = $website->filter('picture>img>.lazyload, .rounded, .entered, .loaded')->each(function ($node) {
            return $node->first()->attr('src');
        });

        $clean_urls =  $this->clean_image($image_urls);

        $data = array_map(null, $title, $title_link, $excerpt, $clean_urls, $clean_dates);
        $this->save_data($data);
    }

    public function save_data($data)
    {

        try {
            foreach ($data as $records) {
                Article::create([
                    'title' => $records[0],
                    'title_link' => $records[1],
                    'excerpt' => $records[2],
                    'image_url' => $records[3],
                    'date' => $records[4],
                ]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function clean_date($dates)
    {
        $clean_dates = [];
        foreach ($dates as $date) {
            if (preg_match('#[0-9]#', $date)) {
                $clean_dates[] = $date;
            }
        }
        return $clean_dates;
    }

    public function clean_image($images)
    {
        $new = [];
        foreach ($images as $image) {
            if ($this->url_array_filter($image) == false) {
                $new[] = $image;
            }
        }
        return  $clean_images =  array_filter($new);
    }
    function url_array_filter($url)
    {
        static $extens = array('.jpg', '.png');
        $ret = true;
        if (!$url) {
            $ret = false;
        } else {
            $path = parse_url($url, PHP_URL_PATH);
            if (in_array(substr($path, -4), $extens)) {
                $ret = false;
            }
        }
        return $ret;
    }
}
