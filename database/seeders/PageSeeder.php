<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Image;
use App\Models\MetaTag;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\Shipping;
use App\Models\PointsConversion;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PointsConversion::create([
            'points' => 1,
            'value' => 1.00,
            'max_percentage' => 10,
            'vat' => 5,
            'vat_negation' => 5
        ]);
        Shipping::create(['area' => 'Dhaka', 'cost' => 100.00,]);

        Page::truncate();

        Image::where('model_type', 'App\Models\Page')->delete();

        Page::factory()->has(MetaTag::factory()->state([
            'meta_title' => 'Offers'
        ]))->create(['type' => 'offers', 'title' => 'Offers']);

        Page::factory()->has(MetaTag::factory()->state([
            'meta_title' => 'Contact'
        ]))->create(['type' => 'contact', 'title' => 'Contact']);

        $home = Page::factory()->has(MetaTag::factory()->state([
            'meta_title' => 'Home'
        ]))->create([
            'type' => 'home',
            'title' => 'Home',
        ]);
        $search = Page::factory()->has(MetaTag::factory()->state([
            'meta_title' => 'Search'
        ]))->create([
            'type' => 'search',
            'title' => 'Search',
        ]);
        $blog = Page::factory()->has(MetaTag::factory()->state([
            'meta_title' => 'Blog'
        ]))->create([
            'type' => 'blog',
            'title' => 'Blog',
        ]);
        $images =
            [
                [
                    'img' => '/img/banners/banner-carousel-1.jpg',
                    'type' => 'carousel',
                    'sort' => 1,
                    'position' => 'top',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                ],
                [
                    'img' => '/img/banners/banner-carousel-2.jpg',
                    'type' => 'carousel',
                    'sort' => 2,
                    'position' => 'top',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                ],
                [
                    'img' => '/img/banners/banner-carousel-3.jpg',
                    'type' => 'carousel',
                    'sort' => 3,
                    'position' => 'top',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                ],
                [
                    'img' => '/img/banners/banner-carousel-4.jpg',
                    'type' => 'carousel',
                    'sort' => 4,
                    'position' => 'top',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                ],
                [
                    'img' => '/img/banners/banner-carousel-5.jpg',
                    'type' => 'carousel',
                    'sort' => 5,
                    'position' => 'top',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                ],
                [
                    'img' => '/img/banners/banner-carousel-6.jpg',
                    'type' => 'carousel',
                    'sort' => 6,
                    'position' => 'top',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                ],
                [
                    'img' => '/img/banners/banner-carousel-7.jpg',
                    'type' => 'carousel',
                    'sort' => 7,
                    'position' => 'top',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                ],
                [
                    'img' => '/img/banners/banner-home-9.jpg',
                    'type' => 'banner',
                    'position' => 'top',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                    'title' => 'Share the Joy, Earn Rewards!',
                    'alt' => 'Invite your friends to shop with us and earn exclusive rewards for every successful sign-up.',
                ],
                [
                    'img' => '/img/banners/banner-home-10.jpg',
                    'type' => 'banner',
                    'position' => 'top',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                    'title' => 'Your Referral Rewards, All in One Place!',
                    'alt' => 'View your referral history and keep track of your rewards effortlessly on your dashboard.',
                ],
                [
                    'img' => '/img/banners/banner-section-1.jpg',
                    'type' => 'banner',
                    'position' => 'middle',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                ],
                [
                    'img' => '/img/banners/banner-section-2.png',
                    'type' => 'banner',
                    'position' => 'below',
                    'link' => route('home'),
                    'model_id' => $home->id,
                    'model_type' => 'App\Models\Page',
                ],
                [
                    'img' => '/img/banners/banner-sidebar-search.jpg',
                    'type' => 'banner',
                    'position' => 'middle',
                    'link' => route('home'),
                    'model_id' => $search->id,
                    'model_type' => 'App\Models\Page',
                ],
                [
                    'img' => '/img/banners/banner-blog.jpg',
                    'type' => 'banner',
                    'position' => 'middle',
                    'link' => route('offers'),
                    'model_id' => $blog->id,
                    'model_type' => 'App\Models\Page',
                ],
            ];
        foreach ($images as $image) {
            Image::factory()->create($image);
        }
    }
}
