<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Jobs\ProcessMail;
use App\Models\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class MailTest extends TestCase
{
    private function callMailApiAndDecodeResponse($url)
    {
        $response = $this->getJson($url)
            ->assertSuccessful()
            ->assertStatus(200)
            ->decodeResponseJson();

        $dataKey = $response->offsetGet('data');
        if (isset($dataKey['id'])) {
            return $dataKey;
        }
        return $dataKey['data'];
    }

    private function arrayHasMultipleKeys($keys = [], $array = [])
    {
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $array);
        }
    }

    private function assertArrayEqual($array1, $array2)
    {
        foreach ($array1 as $key1 => $value1) {
            foreach ($array2 as $key2 => $value2) {
                if ($key1 === $key2) {
                    $this->assertEquals($array1[$key1], $array2[$key2]);
                }
            }
        }
    }

    /** @test */
    public function can_create_mail()
    {
        Bus::fake();

        $this->actingAs(User::factory()->create())
            ->postJson('/api/mail', [
                'from' => 'a@gmail.com',
                'to' => 'b@gmail.com',
                'subject' => 'Test email',
                'content' => 'Test Content'
            ])
            ->assertSuccessful()
            ->assertStatus(200)
            ->assertJsonStructure(['data']);

        Bus::assertDispatched(ProcessMail::class);
    }

    /** @test */
    public function can_list_mail()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->postJson('/api/mail', [
                'from' => 'a@gmail.com',
                'to' => 'b@gmail.com',
                'subject' => 'Test email',
                'content' => 'Test Content'
            ]);

        $data = $this->callMailApiAndDecodeResponse('/api/mail');

        $this->assertCount(1, $data);
        $this->arrayHasMultipleKeys(['id', 'from', 'to', 'subject', 'status', 'failure_reason'], $data[0]);
        $this->assertEquals($data[0]['status'], Status::POSTED);
    }

    /** @test */
    public function can_filter_mail()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Mail::factory()->count(10)->create([
            'status' => '0'
        ]);
        Mail::factory()->count(15)->create([
            'status' => '1'
        ]);
        Mail::factory()->count(20)->create([
            'status' => '2'
        ]);
        $data = $this->callMailApiAndDecodeResponse('/api/mail?status=0');
        $this->assertCount(10, $data);
        $data = $this->callMailApiAndDecodeResponse('/api/mail?status=1&limit=20');
        $this->assertCount(15, $data);
        $data = $this->callMailApiAndDecodeResponse('/api/mail?status=2&limit=20');
        $this->assertCount(20, $data);
        $data = $this->callMailApiAndDecodeResponse('/api/mail?status=2&limit=20&search=NothingElseMatters');
        $this->assertCount(0, $data);
        $data = $this->callMailApiAndDecodeResponse('/api/mail?status=2&limit=20&search=b');
        $this->assertGreaterThan(0, count($data));
    }

    /** @test */
    public function can_see_exact_mail_details()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Mail::factory()->count(10)->create([
            'status' => '0',
            'failure_reason' => null,
        ]);
        $mails = Mail::with('attachments')->get()->toArray();
        foreach ($mails as $index => $mail) {
            $id = $mail['id'];
            $mailData = $this->callMailApiAndDecodeResponse("/api/mail/$id");
            $this->assertArrayEqual($mailData, $mails[$index]);
        }
    }

    /** @test */
    public function can_attempt_for_mail_resend()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $mails = Mail::factory()->count(10)->create([
            'status' => '0'
        ]);
        $this->getJson("api/mail/$mails[0]->id/resend")
            ->assertSuccessful()
            ->assertStatus(200);
    }
}
