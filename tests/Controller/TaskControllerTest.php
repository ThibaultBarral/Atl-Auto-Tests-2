<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testCreateTask()
    {
        $client = static::createClient();
        $client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => 'pending'
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('New Task', $client->getResponse()->getContent());
    }

    public function testReadTask()
    {
        $client = static::createClient();

        $client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Task to be read',
            'description' => 'Task Description',
            'status' => 'pending'
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $task = json_decode($client->getResponse()->getContent(), true)['task'];
        $client->request('GET', '/tasks/' . $task['id']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Task Description', $client->getResponse()->getContent());
    }

    public function testUpdateTask()
    {
        $client = static::createClient();

        $client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Task to be updated',
            'description' => 'Task Description',
            'status' => 'pending'
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $task = json_decode($client->getResponse()->getContent(), true)['task'];

        $client->request('PUT', '/tasks/' . $task['id'], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'status' => 'completed'
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Updated Task', $client->getResponse()->getContent());
    }

    public function testDeleteTask()
    {
        $client = static::createClient();

        $client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Task to be deleted',
            'description' => 'Task Description',
            'status' => 'pending'
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $task = json_decode($client->getResponse()->getContent(), true)['task'];

        $client->request('DELETE', '/tasks/' . $task['id']);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function testPersistence()
    {
        $client = static::createClient();
        
        $client->request('POST', '/tasks', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Persistent Task',
            'description' => 'Persistent Description',
            'status' => 'pending'
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $task = json_decode($client->getResponse()->getContent(), true)['task'];
        $taskId = $task['id'];

        $client->getContainer()->get('doctrine')->getManager()->clear();

        $client->request('GET', '/tasks/' . $taskId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Persistent Task', $client->getResponse()->getContent());
    }
}
