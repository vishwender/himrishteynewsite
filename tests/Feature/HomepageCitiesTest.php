<?php

namespace Tests\Feature;

use App\Services\HomepageCities;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomepageCitiesTest extends TestCase
{
    public function test_each_site_only_lists_cities_from_its_state(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('state_id');
            $table->string('name');
        });
        $expected = [
            'himrishtey.com' => ['Himachal Pradesh', 'Shimla'],
            'gallpakki.com' => ['Punjab', 'Amritsar'],
            'dogririshtey.com' => ['Jammu and Kashmir', 'Jammu'],
            'devbhoomirishte.com' => ['Uttarakhand', 'Dehradun'],
        ];
        foreach ($expected as [$state, $city]) {
            $id = DB::table('states')->insertGetId(['name' => $state]);
            DB::table('cities')->insert(['state_id' => $id, 'name' => $city]);
        }
        foreach ($expected as $site => [$state, $city]) {
            $configuration = config('site.sites')[$site];
            $this->assertSame($state, $configuration['search_state']);
            config(['site.current' => $configuration]);
            $this->assertSame([$city], app(HomepageCities::class)->get()->all());
        }
        config(['site.current' => null]);
        $this->assertTrue(app(HomepageCities::class)->get()->isEmpty());
    }
}
