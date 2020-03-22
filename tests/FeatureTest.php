<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class FeatureTest extends TestCase
{
  use DatabaseTransactions;

  protected $tgturl = "https://fabelio.com/ip/cannes-mirror.html";

  /**
   * join all tests into one transaction
   */
  public function testAllTransaction()
  {
    #test INSERT
    $this->post('/?url='.$this->tgturl);

    $ins = json_decode($this->response->getContent() );

    $this->assertObjectHasAttribute('url', $ins);
    $this->assertEquals($this->tgturl, $ins->url);

    #test DETAIL
    $this->get('/detail?url='.$this->tgturl);

    $resp = json_decode($this->response->getContent() );

    $this->assertObjectHasAttribute('url', $resp);
    $this->assertEquals($this->tgturl, $resp->url);
  
    $this->assertObjectHasAttribute('description', $resp);
    $this->assertNotEmpty($resp->description);
  
    $this->assertObjectHasAttribute('title', $resp);
    $this->assertNotEmpty($resp->title);
  
    $this->assertObjectHasAttribute('prices', $resp);
    $this->assertNotEmpty($resp->prices);

    #test LIST
    $this->get('/');

    $resp = json_decode($this->response->getContent() );

    $this->assertIsIterable($resp);
    $this->assertIsArray($resp);
    foreach($resp as $price){
      $this->assertObjectHasAttribute('url', $price);
      $this->assertNotEmpty($price->url);
    
      $this->assertObjectHasAttribute('description', $price);
      $this->assertNotEmpty($price->description);
    
      $this->assertObjectHasAttribute('title', $price);
      $this->assertNotEmpty($price->title);
    
      $this->assertObjectHasAttribute('last_price', $price);
      $this->assertNotEmpty($price->last_price);
    }
  }
}
