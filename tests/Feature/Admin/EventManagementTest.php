<?php

namespace Tests\Feature\Admin;



use App\Models\Admin;

use App\Models\Event;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;



class EventManagementTest extends TestCase



{



    use RefreshDatabase;



    public function test_admin_can_see_events_list()

    {

        $admin = Admin::factory()->create();

        $event = Event::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.events.index'));

        $response->assertStatus(200);

        $response->assertSee($event->title);

    }



    public function test_admin_can_create_event()



        {



            $admin = Admin::factory()->create();



            $response = $this->actingAs($admin, 'admin')->post(route('admin.events.store'), [



                'title' => 'New Event',



            ]);



            $this->assertDatabaseHas('events', ['title' => 'New Event']);



            $response->assertRedirect();



        }



    public function test_admin_can_see_single_event()

    {

        $admin = Admin::factory()->create();

        $event = Event::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.events.show', $event));

        $response->assertStatus(200);

        $response->assertSee($event->title);

    }



    public function test_admin_can_update_event()

    {

        $admin = Admin::factory()->create();

        $event = Event::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.events.update', $event), [

            'title' => 'Updated Title',

        ]);

        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Updated Title']);

        $response->assertRedirect();

    }



    public function test_admin_can_delete_event()

    {

        $admin = Admin::factory()->create();

        $event = Event::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.events.destroy', $event));

        $this->assertDatabaseMissing('events', ['id' => $event->id]);

        $response->assertRedirect(route('admin.events.index'));

    }

}
