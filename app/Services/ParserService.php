<?php

namespace App\Services;

use App\Enums\News\Status;
use App\Models\Category;
use App\Models\News;
use App\Services\Interfaces\Parser;
use Orchestra\Parser\Xml\Facade as XmlParser;

class ParserService implements Parser
{
    private string $link;

    public function setLink(string $link): Parser
    {
        $this->link = $link;

        return $this;
    }

    public function saveParseData(): void
    {
        $parser = XmlParser::load($this->link);

        $data = $parser->parse([
            'title' => [
                'uses' => 'channel.title',
            ],
            'link' => [
                'uses' => 'channel.link',
            ],
            'description' => [
                'uses' => 'channel.description',
            ],
            'image' => [
                'uses' => 'channel.image.url',
            ],
            'news' => [
                'uses' => 'channel.item[title,content:encoded,link,author,description,pubDate,category,enclosure::url]'
            ],
        ]);

        foreach ($data['news'] as $news) {

            $category = Category::query()->firstOrCreate([
                'title' => $news['category'],
            ]);

            News::query()->firstOrCreate([
                'title' => $news['title'],
                'description' => $news['description'],
                'image' => $news['enclosure::url'],
                'category_id' => $category->id,
                'status' => Status::ACTIVE->value,
                'author' => $news['author'],
            ]);

        }
    }
}
